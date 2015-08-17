<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
?>
<?php if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
} ?>
<?php
global $loggedin_user;

function delete_project_data( $project, $options ) {
	global $wpdb;

	$table1 = T_PREFIX . $options['dbtable_name'];
	$table2 = T_PREFIX . $options['dbtable_name_clicks'];
	$table3 = T_PREFIX . $options['dbtable_name_mmove'];
	$table4 = T_PREFIX . $options['dbtable_name_scroll'];
	$table5 = T_PREFIX . $options['dbtable_name_popular'];
	if ( is_agency() ) {
		$user = current_user();
		$table1 .= "_{$user->user_key}";
		$table2 .= "_{$user->user_key}";
		$table3 .= "_{$user->user_key}";
		$table4 .= "_{$user->user_key}";
		$table5 .= "_{$user->user_key}";
	}
	$wpdb->query( "DELETE FROM $table1 WHERE `project` = '" . $project . "'" );
	$wpdb->query( "DELETE FROM $table2 WHERE `project` = '" . $project . "'" );
	$wpdb->query( "DELETE FROM $table3 WHERE `project` = '" . $project . "'" );
	$wpdb->query( "DELETE FROM $table4 WHERE `project` = '" . $project . "'" );
	$wpdb->query( "DELETE FROM $table5 WHERE `project` = '" . $project . "'" );
}

$project_name = $this->PROJECTS_NAME;
$domain_name  = $this->USER_DOMAINS_NAME;
if ( is_agency() ) {
	$project_name .= $loggedin_user[2];
	$domain_name .= $loggedin_user[2];
	if ( is_user() ) {
		$user = current_user();
	}
}

//regular user ajax actions
switch ( $_POST['action'] ) {
	case 'create': //project
		$this->PROJECTS[ $_POST['name'] ]                                        = array();
		$this->PROJECTS[ $_POST['name'] ]['description']                         = $_POST['description'];
		$this->PROJECTS[ $_POST['name'] ]['settings']                            = array();
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_black_ips']           = array( '127.0.0.1' );
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_status']       = true;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_all']          = "true";
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_special']      = array();
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_mousemove']    = true;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_pagescroll']   = true;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_interval']     = 1;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_kill_session'] = 100;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_record_tz']           = @date_default_timezone_get();
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_ignore_query']        = 0;
		$this->PROJECTS[ $_POST['name'] ]['settings']['opt_mask_ip']             = 0;
		update_option( $project_name, $this->PROJECTS );
		die( 'ok' );
		break;
	case 'delete': //project and data
		delete_project_data( $_POST['name'], $this->OPTIONS );

		unset( $this->PROJECTS[ $_POST['name'] ] );
		update_option( $project_name, $this->PROJECTS );
		die( 'ok' );
		break;
	case 'deletedata': //project data only
		delete_project_data( $_POST['name'], $this->OPTIONS );
		die( 'ok' );
		break;
	case 'add_ip':
		$option = $this->PROJECTS[ $_POST['opt_record_to'] ]["settings"];
		if ( ! isset( $option['opt_black_ips'] ) || ! is_array( $option['opt_black_ips'] ) ) {
			$option['opt_black_ips'] = array();
		}
		$option['opt_black_ips'][ $_POST['ip'] ]               = $_POST['ip'];
		$this->PROJECTS[ $_POST['opt_record_to'] ]["settings"] = $option;
		update_option( $project_name, $this->PROJECTS );
		die( $_POST['ip'] );
		break;
	case 'del_ip':
		$option = $this->PROJECTS[ $_POST['opt_record_to'] ]["settings"];
		unset( $option['opt_black_ips'][ $_POST['ip'] ] );
		$this->PROJECTS[ $_POST['opt_record_to'] ]["settings"] = $option;
		update_option( $project_name, $this->PROJECTS );
		die( 'ok' );
		break;
	case 'check_subscr':
		$is_closed = is_plan_closed( current_user(), $_POST['plan_id'] );
		die( $is_closed ? 'ok' : 'no' );
		break;
	case 'add_tracking_domain':
		$domain    = $_POST['domain'];
		$domains   = &$this->USER_DOMAINS['opt_tracking_domains'];
		$exists    = in_array( $domain, $domains );
		$has_slots = count( $domains ) < $this->USER_DOMAINS['opt_max_tracking_domains'];
		if ( ! $exists && $has_slots ) {
			$domains[] = $domain;
			update_option( $domain_name, $this->USER_DOMAINS );
			die( $domain );
		} else if ( $exists ) {
			die( 'exists' );
		} else if ( ! $has_slots ) {
			die( 'overflow' );
		}
		break;
	case 'del_tracking_domain':
		$domain  = $_POST['domain'];
		$domains = &$this->USER_DOMAINS['opt_tracking_domains'];
		foreach ( $domains as $i => $d ) {
			if ( $d == $domain ) {
				unset( $domains[ $i ] );
				update_option( $domain_name, $this->USER_DOMAINS );
				break;
			}
		}
		die( 'ok' );
		break;
	case 'check_subscr':
		$is_closed = is_plan_closed( current_user(), $_POST['plan_id'] );
		die( $is_closed ? 'ok' : 'no' );
		break;
	case "cancel_upgrade":
		$user = current_user();
		remove_plan( $user, $_POST['plan_id'] );
		update_user( $user );
		die( 'ok' );
		break;
	default:
		if ( is_personal() ) {
			die( 'wrong option' );
		}
		break;
}
