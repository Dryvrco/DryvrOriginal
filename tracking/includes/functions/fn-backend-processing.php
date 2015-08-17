<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */

if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}

/*
 * Public requests
 */
//activation processing
if ( isset( $_GET["hmtrackerregister"] ) ) {
	if ( isset( $_POST['fldTask'] ) && $_POST['fldTask'] == 'deregister' ) {
		if ( hmtrackerspy_regpost() ) {
			$option                = get_option( $this->OPTION_NAME );
			$option["license"]     = "";
			$option["license_key"] = "";
			update_option( $this->OPTION_NAME, $option );
			die( "Deregistered Successfully" );
		}
		die();
	}
}
//restoring processing
if ( isset( $_GET["hmtrackerrestore"] ) ) {
	if ( hmtrackerspy_restore_access() ) {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-messagepage-startrestore-success.php' );
	} else {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-messagepage-startrestore-fail.php' );
	}
	die();
}
if ( isset( $_GET["rkey"] ) ) {
	if ( hmtrackerspy_restore_access_key() ) {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-restore-page.php' );
	} else {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-messagepage-keysteprestore-fail.php' );
	}
	die();
}
if ( isset( $_GET["restoreit"] ) ) {
	if ( hmtrackerspy_restore_access_at_the_end() ) {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-messagepage-restore-success.php' );
	} else {
		require_once( $this->COMMON_MARKUP_PATH . 'mk-messagepage-restore-fail.php' );
	}
	die();
}
//activation page
if ( ! IS_KEY_VALID ) {
	$backendregister = true;
	require_once( $this->COMMON_MARKUP_PATH . 'mk-registerconfig-page.php' );
	die();
}

//login page
if ( isset( $_GET["login"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-login-page.php' );
	die();
}

//when we generate js
if ( isset( $_GET["hmtrackerjs"] ) ) {
	require_once( $this->COMMON_FUNCTIONS_PATH . '/fn-js-processing.php' );
	die();
}

//when we get data from js
if ( isset( $_GET["hmtrackerdata"] ) ) {
	require_once( $this->COMMON_FUNCTIONS_PATH . '/fn-data-processing.php' );
	die();
}

//sign up
if ( isset( $_GET['package'] ) && ! empty( $_GET['package'] ) ) {
	require_once( $this->COMMON_FUNCTIONS_PATH . '/captcha.php' );
	require_once( $this->AGENCY_MARKUP_PATH . 'mk-registeruser-page.php' );
	die();
}

//paypal thankyou redirect - ALWAYS handle it BEFORE ipn handler,
//as paypal sends same transaction vars to thankyou url...
if ( isset( $_GET['paypal_thankyou'] ) ) {
	require_once( $this->AGENCY_MARKUP_PATH . 'mk-thankyou-page.php' );
	die();
}

//ipn async
if ( isset( $_GET['ipn'] ) && ( ! empty( $_POST ) || true ) ) {
	require_once( $this->AGENCY_FUNCTIONS_PATH . '/fn-ipn.php' );
	die();
}


/*
 * Private requests
 */

//when we login
if ( isset( $_POST["username"] ) && isset( $_POST["password"] ) ) {
	$banned_ip = HMTrackerFN::getRealIp();
	if ( is_personal() || ( ( $banned_ip != "" && isset( $this->BANNED_LOGINS ) && is_array( $this->BANNED_LOGINS ) && array_key_exists( $banned_ip, $this->BANNED_LOGINS ) && $this->BANNED_LOGINS[ $banned_ip ] < LOGIN_ATTEMPTS ) || $banned_ip == "" || ! array_key_exists( $banned_ip, $this->BANNED_LOGINS ) ) ) {
		if ( loginCheck( HMTrackerFN::hmtracker_secure( $_POST["username"] ), $_POST["password"], "Login or Password incorrect" ) ) {
			unset( $this->BANNED_LOGINS[ $banned_ip ] );
			update_option( $this->BANNED_LOGINS_NAME, $this->BANNED_LOGINS );
			header( 'location: ' . admin_url() );
			die();
		} else {
			if ( isset( $this->BANNED_LOGINS[ $banned_ip ] ) ) {
				$this->BANNED_LOGINS[ $banned_ip ] ++;
			} else {
				$this->BANNED_LOGINS[ $banned_ip ] = 1;
			}
			update_option( $this->BANNED_LOGINS_NAME, $this->BANNED_LOGINS );
			if ( $this->BANNED_LOGINS[ $banned_ip ] < LOGIN_ATTEMPTS ) {
				logout( 'login', 'Login or Password incorrect.<br />You have ' . ( LOGIN_ATTEMPTS - $this->BANNED_LOGINS[ $banned_ip ] ) . " login attempts left." );
			} else {
				wp_mail( $this->OPTIONS["email"], LOGIN_ATTEMPTS . " Failed Login Attempts", "From the following IP " . LOGIN_ATTEMPTS . " failed attempts was detected: \n\n" . $banned_ip . "\n\nHeatMapTracker\n" . admin_url() );
				if ( $this->OPTIONS["name"] == $_POST["username"] ) {
					$_POST['uemail'] = $this->OPTIONS["email"];
					hmtrackerspy_restore_access();
				}
				logout( 'login', 'Sorry, your IP was blocked. Please, contact support to unblock' );
			}
		}
	} else {
		logout( 'login', 'Sorry, your IP was blocked. Please, contact support to unblock' );
	}
}

//when we logout
if ( isset( $_GET["logout"] ) ) {
	logout();
	header( "Location:" . admin_url() );
	die();
}

//regular user access
ensure_logged_in();

if ( isset( $_GET["changeudata"] ) ) {
	changeUserData();
	die();
}

//User Payments
if ( isset( $_GET["upayments"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-upayments-page.php' );
	die();
}
//Change Package
if ( isset( $_GET["changepackage"] ) ) {
	require_once( $this->AGENCY_MARKUP_PATH . 'mk-changepackage-page.php' );
	die();
}

//user settings
if ( isset( $_GET["usersettings"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-usersettings-page.php' );
	die();
}
//help videos
if ( isset( $_GET["helpvideos"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-videos-page.php' );
	die();
}
//project pages
if ( isset( $_GET["project"] ) ) {
	//heavy geo class - include only if really needed
	require_once( $this->COMMON_FUNCTIONS_PATH . '/geoip.php' );
	require_once( $this->COMMON_MARKUP_PATH . 'mk-project-page.php' );
	die();
}
if ( isset( $_GET["analytics"] ) ) {
	//heavy geo class - include only if really needed
	require_once( $this->COMMON_FUNCTIONS_PATH . '/geoip.php' );
	require_once( $this->COMMON_MARKUP_PATH . 'mk-analytics-page.php' );
	die();
}
if ( isset( $_GET["hmaps"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-heatmaps-page.php' );
	die();
}
if ( isset( $_GET["ppages"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-popularpages-page.php' );
	die();
}
if ( isset( $_GET["settings"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-settings-page.php' );
	die();
}
if ( isset( $_GET["mdata"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-managedata-page.php' );
	die();
}

//when we process ajax ections
if ( isset( $_GET["hmtrackeractions"] ) ) {
	require_once( $this->COMMON_FUNCTIONS_PATH . '/fn-actions-processing.php' );
	if ( is_agency() ) {
		require_once( $this->AGENCY_FUNCTIONS_PATH . '/fn-actions-processing.php' );
	}
	die();
}

if ( isset( $_GET["player_frame"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'track/mk-player-frame.php' );
	die();
}
if ( isset( $_GET["heatmap_frame"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'track/mk-hmap-frame.php' );
	die();
}

//when we see user actions
if ( isset( $_GET["hmtrackerview"] ) ) {
	//heavy geo class - include only if really needed
	require_once( $this->COMMON_FUNCTIONS_PATH . '/geoip.php' );
	require_once( $this->COMMON_MARKUP_PATH . 'track/mk-player-view.php' );
	die();
}
//heatmaps interface
if ( isset( $_GET["hmtrackerheatmap"] ) ) {
	// Check if we need to redirect because of schema mismatch
	$u = parse_url( $_GET['url'] );
	$h = parse_url( siteURL( false ) );
	if ( $h['scheme'] != $u['scheme'] ) {
		header( "Location: {$u['scheme']}://{$h['host']}{$h['path']}?{$h['query']}" );
		die();
	}
	require_once( $this->COMMON_MARKUP_PATH . 'mk-heatmap-page.php' );
	die();
}
//save settings
if ( isset( $_GET["hmtrackersettings"] ) ) {
	require_once( $this->COMMON_FUNCTIONS_PATH . '/fn-settings-processing.php' );
	die();
}

if ( isset( $_GET['return_admin'] ) && isset( $_SESSION['return_to_admin'] ) && $_SESSION['return_to_admin'] ) {
	$option = get_option( $this->OPTION_NAME );
	loginCheck( $option['email'], $option['password'], "Login or Password incorrect", false );
	header( "Location: " . home_url() );
	die();
}

if ( isset( $_GET["adminsettings"] ) ) {
	if ( isset( $_POST['form'] ) && $_POST["form"] == 'admin' ) {
		$_POST['fldBHelp'] = trim( htmlentities( stripslashes( $_POST['fldBHelp'] ), ENT_QUOTES ) );
		changeAdminData();
		header( "Location: " . home_url() . "?adminsettings&submit=true" );
		die();
	}
	require_once( $this->COMMON_MARKUP_PATH . 'mk-adminsettings-page.php' );
	die();
}

if ( isset( $_GET["about"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-about-page.php' );
	die();
}

//automatic software updater
if ( isset( $_GET["update_start"] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-update-page.php' );
	die();
}

if ( isset( $_GET['rds'] ) ) {
	require_once( $this->COMMON_MARKUP_PATH . 'mk-rds-page.php' );
	die();
}