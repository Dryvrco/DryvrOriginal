<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
?>
<?php if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}
//secure check $_POST variables
$_GET['hmtrackerdata'] = rawurlencode( $_GET["hmtrackerdata"] );
$_REQUEST["user"]      = HMTrackerFN::hmtracker_secure( $_REQUEST["user"] );
include( __DIR__ . DIRECTORY_SEPARATOR . "geoip.php" );

function calculate_user_time( $session_array ) {
	$time = 0;
	$sum  = 0;
	foreach ( $session_array as $session_id => $tracking_data ) {
//		echo "K: {$session_id}\n";
		foreach ( $tracking_data as $page => $page_data ) {
//			echo "KK: {$page}\n";
			foreach ( $page_data as $action => $action_data ) {
//				echo "KKK: {$action}\n";
				if ( $action != "responsetive" ) {
					foreach ( $action_data as $event => $event_data ) {
						if ( $time < (int) $event_data[0] ) {
							$time = (int) $event_data[0];
//							echo "TIME: {$time}\n";
						}
					}
				}
			}
		}

		$sum += (int) $time;
//		echo "SUM: {$sum}\n";
		$time = 0;
	}

	return $sum;
}

//save spy data to db
if ( isset( $_REQUEST['user'] ) ) {

	global $wpdb;

	$option = $this->OPTIONS;

	if ( is_agency() ) {
//fetch user
		$user = get_user_by( 'user_key', HMTrackerFN::hmtracker_secure( $_GET['uid'] ) );

//fetch project settings and check if we can track domain
		$general_opts   = array( $this->PROJECTS_NAME . $user->user_key );
		$opts           = get_options( $general_opts );
		$this->PROJECTS = $opts[ $this->PROJECTS_NAME . $user->user_key ];
	}
	$settings = $this->PROJECTS[ $_GET['hmtrackerdata'] ]['settings'];

	if ( isset( $settings['opt_record_tz'] ) ) {
		date_default_timezone_set( $settings['opt_record_tz'] );
	}

//stack the data
	$sessions    = array();
	$click_arr   = array();
	$mmove_arr   = array();
	$scroll_arr  = array();
	$popular_arr = array();
	$referrer    = null;
	if ( isset( $_REQUEST['data'] ) ) {
		$data = json_decode( base64_decode( $_REQUEST['data'] ) );

//		echo base64_decode( $_REQUEST['data'] ) . "\n";
		foreach ( $data as $session_id => $tracking_data ) {

//			echo "SESSION: $session_id\n";

			if ( ! isset( $sessions[ $session_id ]["time"] ) ) {
				$sessions[ $session_id ]["time"] = 0;
			}

			foreach ( $tracking_data as $page => $page_data ) { //pages lvl

//				echo "PAGE: $page\n";
				//for special pages
				if ( ( $settings["opt_record_all"] == "false" && ! ( in_array( $page, $settings['opt_record_special'] ) ) ) ) {
					continue;
				}

				// Parse the url
				$parsed_url = parse_url( $page );
				// If we have a query string
				if ( isset( $parsed_url['query'] ) ) {
					// Find gclid and remove it
					$q_options = explode( "&", $parsed_url['query'] );
					foreach ( $q_options as $qkey => $q_option ) {
						$o = explode( "=", $q_option );
						if ( $o[0] == "gclid" ) {
							unset( $q_options[ $qkey ] );
						}
					}
					$parsed_url['query'] = implode( "&", $q_options );

					// Rework the url to exclude the gclid query option
					$page = "{$parsed_url['scheme']}://{$parsed_url['host']}{$parsed_url['path']}";
					// If user wants to include othre query string options then add to url
					if ( isset( $settings['opt_ignore_query'] ) && $settings['opt_ignore_query'] == 0 ) {
						$page .= "?{$parsed_url['query']}";
					}
				}

				$scroll_arr[ $page ]["height"]      = 0;
				$scroll_arr[ $page ]["page_height"] = 0;
				$scroll_arr[ $page ]["maxscroll"]   = 0;
				$popular_arr[ $page ]               = $settings["opt_record_interval"];
				foreach ( $page_data as $action => $action_data ) { //event lvl
					if ( $action == "referrer" ) {
						if ( $referrer == null || $referrer != "" ) {
							$referrer = $action_data;
							unset( $page_data->$action );
						}
						continue;
					}
					foreach ( $action_data as $event => $event_data ) { //events arr lvl
						if ( $action != "responsetive" ) {
							if ( $sessions[ $session_id ]["time"] < (int) ( $event_data[0] ) ) {
								$sessions[ $session_id ]["time"] = (int) ( $event_data[0] );
							}
						}
						if ( $action == "mouse_click" ) {
							if ( isset( $click_arr[ $page ] ) ) {
								$click_arr[ $page ] .= $wpdb->escape_String( "|" . $event_data[2] . " " . $event_data[3] . " " . $event_data[6] . " " . $event_data[7] );
							} else {
								$click_arr[ $page ] = $wpdb->escape_String( "|" . $event_data[2] . " " . $event_data[3] . " " . $event_data[6] . " " . $event_data[7] );
							}
						}
						if ( $action == "mouse_move" ) {
							if ( isset( $mmove_arr[ $page ] ) ) {
								$mmove_arr[ $page ] .= $wpdb->escape_String( "|" . $event_data[1] . " " . $event_data[2] . " " . $event_data[3] . " " . $event_data[4] );
							} else {
								$mmove_arr[ $page ] = $wpdb->escape_String( "|" . $event_data[1] . " " . $event_data[2] . " " . $event_data[3] . " " . $event_data[4] );
							}
						}
						if ( $action == "page_scroll" ) {
							if ( $scroll_arr[ $page ]["maxscroll"] < $event_data[1] ) {
								$scroll_arr[ $page ]["maxscroll"] = $wpdb->escape_String( $event_data[1] );
							}
						}
						if ( $action == "window_size" ) {
							if ( $scroll_arr[ $page ]["height"] < $event_data[1] ) {
								$scroll_arr[ $page ]["height"] = $wpdb->escape_String( $event_data[1] );
							}
							if ( $scroll_arr[ $page ]["page_height"] < $event_data[3] ) {
								$scroll_arr[ $page ]["page_height"] = $wpdb->escape_String( $event_data[3] );
							}
						}
					}
				}
				$sessions[ $session_id ]['data'][] = array( $page => $page_data );
			}
		}
	}

//	print_r( $sessions );

//	print_r($click_arr);;
//	echo "\n";

	$main_table    = T_PREFIX . $option['dbtable_name'];
	$click_table   = T_PREFIX . $option['dbtable_name_clicks'];
	$mmove_table   = T_PREFIX . $option['dbtable_name_mmove'];
	$scroll_table  = T_PREFIX . $option['dbtable_name_scroll'];
	$popular_table = T_PREFIX . $option['dbtable_name_popular'];
	if ( is_agency() ) {
		$main_table .= "_{$_GET['uid']}";
		$click_table .= "_{$_GET['uid']}";
		$mmove_table .= "_{$_GET['uid']}";
		$scroll_table .= "_{$_GET['uid']}";
		$popular_table .= "_{$_GET['uid']}";
	}
	//put clicks to DB
	foreach ( $click_arr as $key => $value ) {

		$clicks = $wpdb->get_row( "SELECT * FROM $click_table WHERE `page_url` = '$key' AND `project` = '" . $_GET['hmtrackerdata'] . "' ORDER BY `id` DESC LIMIT 1" );

		if ( ! $clicks ) {
			$q = "INSERT INTO `" . $click_table . "` (`page_url`,`click_data`,`date`,`project`) VALUES ('" . HMTrackerFN::hmtracker_secure( $key ) . "','" . HMTrackerFN::hmtracker_secure( $value ) . "', NOW(),'" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "')";
			$wpdb->query( $q );
		} else {
			if ( $clicks->click_data != "" ) {
				$clickStr = $clicks->click_data;
			}
			if ( strlen( $clickStr ) > 600 || date( "m.d.y" ) != date( "m.d.y", strtotime( $clicks->date ) ) ) {
				$q = "INSERT INTO `" . $click_table . "` (`page_url`,`click_data`,`date`,`project`) VALUES ('" . HMTrackerFN::hmtracker_secure( $key ) . "','" . HMTrackerFN::hmtracker_secure( $value ) . "', NOW(),'" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "')";
				$wpdb->query( $q );
			} else {
				$clickStrMerged = $clickStr . $value;
				$q              = "UPDATE `" . $click_table . "` SET  `click_data` = '" . HMTrackerFN::hmtracker_secure( $clickStrMerged ) . "' WHERE `page_url` = '" . $key . "' ORDER BY `id` DESC LIMIT 1 ";
				$wpdb->query( $q );
			}
		}
	}

	//put mmove to DB
	foreach ( $mmove_arr as $key => $value ) {

		$clicks = $wpdb->get_row( "SELECT * FROM $mmove_table WHERE `page_url` = '$key' AND `project` = '" . $_GET['hmtrackerdata'] . "' ORDER BY `id` DESC LIMIT 1" );
		if ( ! $clicks ) {
			$q = "INSERT INTO `" . $mmove_table . "` (`page_url`,`mmove_data`,`date`,`project`) VALUES ('" . HMTrackerFN::hmtracker_secure( $key ) . "','" . HMTrackerFN::hmtracker_secure( $value ) . "', NOW(),'" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "')";
			$wpdb->query( $q );
		} else {
			if ( $clicks->mmove_data != "" ) {
				$clickStr = $clicks->mmove_data;
			}
			if ( strlen( $clickStr ) > 600 || date( "m.d.y" ) != date( "m.d.y", strtotime( $clicks->date ) ) ) {
				$q = "INSERT INTO `" . $mmove_table . "` (`page_url`,`mmove_data`,`date`,`project`) VALUES ('" . HMTrackerFN::hmtracker_secure( $key ) . "','" . HMTrackerFN::hmtracker_secure( $value ) . "', NOW(),'" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "')";
				$wpdb->query( $q );
			} else {
				$clickStrMerged = $clickStr . $value;
				$q              = "UPDATE `" . $mmove_table . "` SET  `mmove_data` = '" . HMTrackerFN::hmtracker_secure( $clickStrMerged ) . "' WHERE `page_url` = '" . $key . "' ORDER BY `id` DESC LIMIT 1 ";
				$wpdb->query( $q );
			}
		}
	}

	//put scroll to DB
	foreach ( $scroll_arr as $key => $value ) {

		$clicks = $wpdb->get_row( "SELECT * FROM $scroll_table WHERE `page_url` = '$key' AND `project` = '" . $_GET['hmtrackerdata'] . "' ORDER BY `id` DESC LIMIT 1" );
		if ( ! $clicks ) {
			$q = "INSERT INTO `" . $scroll_table . "` (`page_url`,`scroll_data`,`date`,`project`) VALUES ('" . $key . "','" . ( $value["height"] + $value["maxscroll"] ) . " " . $value["page_height"] . "', NOW(),'" . $_GET['hmtrackerdata'] . "')";
			$wpdb->query( $q );
		} else {
			if ( $clicks->scroll_data != "" ) {
				$clickStr = $clicks->scroll_data;
			}
			if ( strlen( $clickStr ) > 600 || date( "m.d.y" ) != date( "m.d.y", strtotime( $clicks->date ) ) ) {
				$q = "INSERT INTO `" . $scroll_table . "` (`page_url`,`scroll_data`,`date`,`project`) VALUES ('" . $key . "','" . ( $value["height"] + $value["maxscroll"] ) . " " . $value["page_height"] . "', NOW(),'" . $_GET['hmtrackerdata'] . "')";
				$wpdb->query( $q );
			} else {
				$clickStrMerged = $clickStr . "|" . ( $value["height"] + $value["maxscroll"] ) . " " . $value["page_height"];
				$q              = "UPDATE `" . $scroll_table . "` SET  `scroll_data` = '" . HMTrackerFN::hmtracker_secure( $clickStrMerged ) . "' WHERE `page_url` = '" . $key . "' ORDER BY `id` DESC LIMIT 1 ";
				$wpdb->query( $q );
			}
		}
	}

	//put popular to DB
	foreach ( $popular_arr as $key => $value ) {
		$clicks = $wpdb->get_row( "SELECT * FROM $popular_table WHERE `page_url` = '$key' AND `project` = '" . $_GET['hmtrackerdata'] . "' ORDER BY `id` DESC LIMIT 1" );
		if ( ! $clicks ) {
			$q   = "INSERT INTO `" . $popular_table . "` (`date`,`page_url`,`points`,`project`) VALUES ('" . date( "Y-m-d" ) . "','" . HMTrackerFN::hmtracker_secure( $key ) . "'," . HMTrackerFN::hmtracker_secure( $value ) . ",'" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "')";
			$res = $wpdb->query( $q );

		} else {
			$pnts = 0;
			if ( $clicks->points != "" ) {
				$pnts = $clicks->points;
			}
			$pnts += $value;
			$q   = "UPDATE $popular_table SET `points` = " . HMTrackerFN::hmtracker_secure( $pnts ) . " WHERE `page_url` = '" . $key . "' LIMIT 1 ";
			$res = $wpdb->query( $q );
		}
	}


	foreach ( $sessions as $session_id => $post ) {

//		echo "{$session_id}<br />";

		$data          = $post['data'];
		$session       = $wpdb->get_row( "SELECT * FROM $main_table WHERE session_id = '$session_id' AND `project` = '" . $_GET['hmtrackerdata'] . "'" );
		$session_array = array();
		// if session number exist:
		if ( $session != null ) {
			if ( isset( $session->session_spydata ) ) {
				$session_array = json_decode( $session->session_spydata );
			}
			$db_session_id = $session->id;
			//get last page key
			$lastkey = "";
			foreach ( $session_array[ count( $session_array ) - 1 ] as $key => $value ) {
				$lastkey = $key;
				break;
			}
			//get first page key
			$firstkey = "";
			foreach ( $data[0] as $kkey => $vvalue ) {
				$firstkey = $kkey;
				break;
			}


			$start = ( $firstkey == $lastkey ) ? 1 : 0;
			foreach ( $data as $kkey => $vvalue ) {
				if ( $kkey >= $start ) {
					$session_array[] = $vvalue;
				} else {
					foreach ( $data[0] as $k2 => $v2 ) {
						foreach ( $v2 as $k3 => $v3 ) {
							if ( ! isset( $session_array[ count( $session_array ) - 1 ]->$k2->$k3 ) ) {
								$session_array[ count( $session_array ) - 1 ]->$k2->$k3 = array();
							}
							$session_array[ count( $session_array ) - 1 ]->$k2->$k3 = array_merge( $session_array[ count( $session_array ) - 1 ]->$k2->$k3, $v3 );
						}
					}
				}
			}


			$sum = calculate_user_time( $session_array );
			//update db record

//			print_r($session_array);
			$q = "UPDATE `" . $main_table . "` SET `session_end` = '" . ( $session->session_start + $sum + 1 ) . "', `session_spydata` = '" . json_encode( $session_array ) . "' WHERE `session_id` = '" . $session_id . "'";
			$wpdb->query( $q );
		} // if we have new session just insert new record
		else if ( $post['time'] > 0 || ! empty( $click_arr ) || ! empty( $mmove_arr ) || ! empty( $scroll_arr ) ) {
			if ( $post['time'] < 1 ) {
				$post['time'] = 1;
			}
			$country      = "not found";
			$country_code = null;
			$objGeoIP     = new hmtracker_GeoIP();
			$ip           = explode( "~", $_REQUEST['user'], 2 );

			$objGeoIP->search_ip( $ip[0] );

			if ( $objGeoIP->found() ) {
				$country      = $objGeoIP->getCountryName();
				$country_code = $objGeoIP->getCountryCode();
			}
			if ( isset( $this->PROJECTS[ $_GET['hmtrackerdata'] ]['settings']['opt_mask_ip'] ) && $this->PROJECTS[ $_GET['hmtrackerdata'] ]['settings']['opt_mask_ip'] == 1 ) {
				$_REQUEST['user'] = "xxx.xxx.xxx.xxx~" . $ip[1];
			}

			$q = "INSERT INTO `" . $main_table . "` (`user_id`,`session_id`,`session_start`,`session_end`,`session_time`,`session_spydata`,`project`,`country`,`country_code`,`referrer`) VALUES ('" . HMTrackerFN::hmtracker_secure( $_REQUEST['user'] ) . "','" . HMTrackerFN::hmtracker_secure( $session_id ) . "','" . ( time() - $post['time'] ) . "','" . time() . "','" . time() . "','" . json_encode( $data ) . "','" . HMTrackerFN::hmtracker_secure( $_GET['hmtrackerdata'] ) . "', '" . HMTrackerFN::hmtracker_secure( $country ) . "', '" . HMTrackerFN::hmtracker_secure( $country_code ) . "', '" . $wpdb->escape_String( $referrer ) . "')";
			$wpdb->query( $q );
			$db_session_id = $wpdb->lastInsertedId();
		}

	}
	//end foreach


}//end if

header( "Content-type: application/javascript" );
echo $_GET['callback'] . "([{$db_session_id},{$session_id}])";
?>