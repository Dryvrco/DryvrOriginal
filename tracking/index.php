<?php
/*
 * Heat Map Tracker Developer License
 * (c) 2013. HeatMapTracker
 * http://HeatMapTracker.com
 */
if ( ! isset( $_GET["ipn"] ) && ! isset( $_GET["hmtrackerjs"] ) && ! isset( $_GET["hmtrackerdata"] ) ) {
	if ( ! isset( $_SESSION ) ) {
		session_start();
	}
}
error_reporting( 0 );
ini_set( 'display_errors', 0 );
ini_set( 'log_errors', 0 );

// Debug Paypal transaction using paypal sandbox
define( 'PAYPAL_DEBUG', false );
define( 'DEBUG', false );
define( 'CURRENT_VERSION', "3.1.07" );
define( 'COOKIE_EXPIRY', 3600 ); // 3600 = 1 hour

class HMTrackerPro_class {

	var $PLUGIN_URL;
	var $PLUGIN_PATH;
	var $MARKUP_PATH;
	var $COMMON_FUNCTIONS_PATH;

	var $PAYPAL_URL;
	var $UPDATE_URL;

	var $OPTION_NAME;
	var $OPTIONS;

	var $PROJECTS_NAME;
	var $PROJECTS;

	var $PACKAGES_NAME;
	var $PACKAGES;

	var $USERSTATUS_NAME;
	var $USERSTATUS;

	var $USER_DOMAINS_NAME;
	var $USER_DOMAINS;

	var $BANNED_LOGINS_NAME;
	var $BANNED_LOGINS;

	var $VIBER_INIT;
	var $MAIN_STR;
	var $MEMORY_ERROR;
	var $UpdateChecker;

	public function __construct() {
		define( 'HMT_STARTED', true );
		define( 'LOGIN_ATTEMPTS', 4 );
		define( 'HMT_LOG_IPN', true );

		$this->PLUGIN_PATH           = dirname( __FILE__ ) . DIRECTORY_SEPARATOR;
		$this->COMMON_MARKUP_PATH    = $this->PLUGIN_PATH . 'includes/markup/';
		$this->COMMON_FUNCTIONS_PATH = $this->PLUGIN_PATH . 'includes/functions/';
		$this->PROJECTS_NAME         = "heatmaptracker_projects";

		require_once( $this->COMMON_FUNCTIONS_PATH . 'fn-functions.php' );

		$this->PLUGIN_URL         = admin_url();
		$this->OPTION_NAME        = "heatmaptracker";
		$this->BANNED_LOGINS_NAME = "heatmaptracker_banned_logins";
		$this->PACKAGES_NAME      = "heatmaptracker_packages";
		$this->BANNED_LOGINS      = false;
		$this->PACKAGES           = false;

		$GLOBALS["HMTrackerPro_PLUGIN_PATH"]        = $this->PLUGIN_PATH;
		$GLOBALS["HMTrackerPro_PLUGIN_URL"]         = $this->PLUGIN_URL;
		$GLOBALS["HMTrackerPro_OPTION_NAME"]        = $this->OPTION_NAME;
		$GLOBALS["HMTrackerPro_BANNED_LOGINS_NAME"] = $this->BANNED_LOGINS_NAME;
		$GLOBALS['loggedin_user']                   = array();

		if ( file_exists( $this->PLUGIN_PATH . '/config.php' ) ) {
			require_once( dirname( __FILE__ ) . '/includes/db/db.class.php' );
			$mail_config = dirname( __FILE__ ) . '/mail_config.php';
			if ( file_exists( $mail_config ) ) {
				require_once( $mail_config );
			} else {
				require_once( dirname( __FILE__ ) . '/mail_config_sample.php' );
			}
			require_once( dirname( __FILE__ ) . '/config.php' );

			$extra_port = explode( ":", DB_HOST );
			if ( isset( $extra_port[1] ) ) {
				$config['db_name']     = DB_NAME;
				$config['db_user']     = DB_USER;
				$config['db_password'] = DB_PASSWORD;
				$config['db_host']     = $extra_port[0];
				$config['db_port']     = $extra_port[1];
				$config['db_prefix']   = T_PREFIX;
				$errors                = writeDatabaseConfig( $this->PLUGIN_PATH . 'config.php', $config );
				sleep( 1 );
				header( "Location: " . siteURL( false ) );
				die();
			}

			if ( ! defined( "DB_PORT" ) ) {
				define( "DB_PORT", 3306 );
			}
			$GLOBALS["wpdb"]                 = new DB( DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_PORT );
			$this->OPTIONS                   = get_option( $this->OPTION_NAME );
			$GLOBALS["HMTrackerPro_PACKAGE"] = $this->OPTIONS['heatmap_package'];

		}

		if ( isset( $this->OPTIONS['heatmap_package'] ) && is_agency() ) {
			if ( ! file_exists( "agency" ) ) {
				die( "Missing agency files. Unable to continue. <br />Please contact support" );
			}
			if ( DEBUG ) {
				$this->UPDATE_URL = "http://192.168.0.10/heatmap_tracker/updates/heatmap_tracker/3.0.1/developer.update";
			} else {
				$this->UPDATE_URL = "http://heatmaptracker.com/update/developer.update";
			}
			$this->PLANS_NAME        = "heatmaptracker_plans";
			$this->USERSTATUS_NAME   = "heatmaptracker_userstatuses";
			$this->USER_DOMAINS_NAME = "heatmaptracker_user_domains";

			$this->AGENCY_MARKUP_PATH    = $this->PLUGIN_PATH . 'agency/markup/';
			$this->AGENCY_FUNCTIONS_PATH = $this->PLUGIN_PATH . 'agency/functions/';

			$this->OPTIONS['dbtable_name']          = "main";
			$this->OPTIONS['dbtable_name_clicks']   = "clicks";
			$this->OPTIONS['dbtable_name_mmove']    = "mmove";
			$this->OPTIONS['dbtable_name_scroll']   = "scroll";
			$this->OPTIONS['dbtable_name_popular']  = "popular";
			$this->OPTIONS['dbtable_name_users']    = "heatmaptracker_users";
			$this->OPTIONS['dbtable_name_payments'] = "heatmaptracker_payments";
			$this->OPTIONS['dbtable_name_ipn']      = "heatmaptracker_ipn_log";
			update_option( $this->OPTION_NAME, $this->OPTIONS );

			$GLOBALS["HMTrackerPro_PROJECTS_NAME"]     = $this->PROJECTS_NAME;
			$GLOBALS["HMTrackerPro_PACKAGES_NAME"]     = $this->PACKAGES_NAME;
			$GLOBALS["HMTrackerPro_USERSTATUS_NAME"]   = $this->USERSTATUS_NAME;
			$GLOBALS["HMTrackerPro_USER_DOMAINS_NAME"] = $this->USER_DOMAINS_NAME;

			require_once( $this->AGENCY_FUNCTIONS_PATH . 'fn-functions.php' );
		} elseif ( isset( $this->OPTIONS['heatmap_package'] ) && is_personal() ) {
			if ( DEBUG ) {
				$this->UPDATE_URL = "http://192.168.0.10/heatmap_tracker/updates/heatmap_tracker/3.0.1/single.update";
			} else {
				$this->UPDATE_URL = "http://heatmaptracker.com/update/single.update";
			}
			$this->PERSONAL_MARKUP_PATH    = $this->PLUGIN_PATH . 'personal/markup/';
			$this->PERSONAL_FUNCTIONS_PATH = $this->PLUGIN_PATH . 'personal/functions/';

			$this->OPTIONS['dbtable_name']         = "heatmaptracker";
			$this->OPTIONS['dbtable_name_clicks']  = "heatmaptracker_clicks";
			$this->OPTIONS['dbtable_name_mmove']   = "heatmaptracker_mmove";
			$this->OPTIONS['dbtable_name_scroll']  = "heatmaptracker_scroll";
			$this->OPTIONS['dbtable_name_popular'] = "heatmaptracker_popular";
			update_option( $this->OPTION_NAME, $this->OPTIONS );
		}

		$this->hmtrackerspy_registerConfig();

		if ( defined( 'PAYPAL_DEBUG' ) && PAYPAL_DEBUG ) {
			$this->PAYPAL_URL = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
			$this->PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr";
		}

	}

	#-------------------------------------------------------------------------------------------
	public function hmtrackerspy_install() { #install plugin
		#-------------------------------------------------------------------------------------------
		//echo "2 ";

		$option = $this->OPTIONS;
		if ( false === $option ) {
			global $heatmap_package;

			$option                    = array();
			$option['heatmap_package'] = $heatmap_package;
			$option['software']        = check_package( $heatmap_package );
			$option['update']          = time();
			$option['key']             = md5( time() . time() . time() . time() . time() );
			$option['version']         = CURRENT_VERSION;
			$option['last_info']       = "";
			$option['changelog']       = array(
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				),
				"3.1.04" => array(
					"Fix 1" => "Removed duplicate logo appearing in thank you page for free packages",
					"Fix 2" => "Removed paypal subscription message during package upgrade when upgrading from free user.",
					"Fix 3" => "Change label of logo in Admin Settings to reflect the correct size logo should be. Also forced css to limit size to 154x58"
				),
				"3.1.05" => array(
					"Fix 1" => "Some servers break when viewing Heatmaps when \"=\" sign is in post variables during ajax call.",
				),
				"3.1.06" => array(
					"Fix 1" => "Corrected page height output logic in session playback",
				),
				"3.1.07" => array(
					"Fix 1" => "Improved error detection when licensing server times out",
				)
			);

			$option['license_key']    = "";
			$option['license']        = "";
			$option['opt_record_all'] = "true";

			$option['restore_key']  = array();
			$option['brandname']    = "Heat Map Tracker";
			$option['brandlogo']    = $this->PLUGIN_URL . "images/hmtracker-logo.png";
			$option['brandsupport'] = "http://support.digitalkickstart.com/";
			$option['help_area']    = "&lt;iframe width=&quot;800&quot; height=&quot;600&quot; src=&quot;//www.youtube.com/embed/ubiH3nK0YHk&quot; frameborder=&quot;0&quot; allowfullscreen&gt;&lt;/iframe&gt;";
			$option['favicon']      = $this->PLUGIN_URL . "/images/favicon.ico";

			global $wpdb;

			if ( $option['heatmap_package'] == "agency" ) {
				$option['dbtable_name']         = "main";
				$option['dbtable_name_clicks']  = "clicks";
				$option['dbtable_name_mmove']   = "mmove";
				$option['dbtable_name_scroll']  = "scroll";
				$option['dbtable_name_popular'] = "popular";

				$option['dbtable_name_users']    = "heatmaptracker_users";
				$option['dbtable_name_payments'] = "heatmaptracker_payments";
				$option['dbtable_name_ipn']      = "heatmaptracker_ipn_log";

				$table_users = T_PREFIX . $option['dbtable_name_users'];
				$users_table = "CREATE TABLE IF NOT EXISTS $table_users (
					      id int(12) NOT NULL AUTO_INCREMENT,
					      email VARCHAR(200) DEFAULT '' NOT NULL,
					      password VARCHAR(200) DEFAULT '' NOT NULL,
					      business_name VARCHAR(200) DEFAULT '' NOT NULL,
					      website VARCHAR(200) DEFAULT '' NOT NULL,
					      user_key VARCHAR(200) DEFAULT '' NOT NULL,
					      plans text,
					      status int(3) NOT NULL,
					      last_status_check bigint(99) NULL,
					      KEY email (email),
					      KEY password (password),
					      KEY user_key (user_key),
						  PRIMARY KEY (`id`)
						    ) ENGINE=INNODB;";
				$wpdb->query( $users_table );

				$table_payments = T_PREFIX . $option['dbtable_name_payments'];
				$payments_table = "CREATE TABLE IF NOT EXISTS $table_payments (
						  `id` bigint(20) NOT NULL AUTO_INCREMENT,
						  `txnid` varchar(20) NOT NULL,
						  `payment_amount` decimal(7,2) NOT NULL,
						  `payment_status` varchar(25) NOT NULL,
						  `txn_type` varchar(25) NULL,
						  `createdtime` datetime NOT NULL,
						  `user` VARCHAR(200) DEFAULT '' NOT NULL,
						  `user_id` bigint(20) NOT NULL,
						  `plan_id` varchar(32) NULL,
						  PRIMARY KEY (`id`),
						  KEY(`user_id`),
						  KEY(`plan_id`)
						) ENGINE=INNODB;";
				$wpdb->query( $payments_table );

				$table_ipn     = T_PREFIX . $option['dbtable_name_ipn'];
				$structure_ipn = "CREATE TABLE IF NOT EXISTS {$table_ipn} (
					`log_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`tx_id` bigint(20) NOT NULL,
					`user_id` bigint(20) NOT NULL,
					`pay_email` varchar(100) NOT NULL,
					`paysys` varchar(100) NOT NULL DEFAULT 'paypal',
					`status` tinyint(1) NOT NULL,
					`postdata` longtext NOT NULL,
					`date` int(11) NOT NULL,
					UNIQUE KEY id (log_id)
	    		)  ENGINE=INNODB DEFAULT CHARSET=utf8;";
				$wpdb->query( $structure_ipn );

			} elseif ( $option['heatmap_package'] == "personal" ) {
				$option['dbtable_name']         = "heatmaptracker";
				$option['dbtable_name_clicks']  = "heatmaptracker_clicks";
				$option['dbtable_name_mmove']   = "heatmaptracker_mmove";
				$option['dbtable_name_scroll']  = "heatmaptracker_scroll";
				$option['dbtable_name_popular'] = "heatmaptracker_popular";

				$table                = T_PREFIX . $option['dbtable_name'];
				$heatmaptracker_table = "CREATE TABLE IF NOT EXISTS $table (
					      id int(99) NOT NULL AUTO_INCREMENT,
					      project VARCHAR(200) DEFAULT '' NOT NULL,
					      user_id VARCHAR(200) DEFAULT '' NOT NULL,
					      session_id VARCHAR(200) DEFAULT '' NOT NULL,
					      session_spydata text,
					      session_start int(9) NOT NULL,
					      session_end int(9) NOT NULL,
					      session_time int(9) NOT NULL,
					      country varchar(40),
					      country_code varchar(2),
					      referrer text,
					      KEY project (project),
					      UNIQUE KEY id (id),
					      UNIQUE INDEX session_id (session_id)
					    ) ENGINE=InnoDB;";
				$wpdb->query( $heatmaptracker_table );

				$table_click  = T_PREFIX . $option['dbtable_name_clicks'];
				$clicks_table = "CREATE TABLE IF NOT EXISTS $table_click (
					      id int(99) NOT NULL AUTO_INCREMENT,
					      project VARCHAR(200) DEFAULT '' NOT NULL,
					      date DATE NOT NULL,
					      page_url VARCHAR(500) DEFAULT '' NOT NULL,
					      click_data text,
					      UNIQUE KEY id (id),
					      KEY project (project),
					      KEY page_url (page_url),
						  KEY date (`date`)
					    ) ENGINE=InnoDB;";
				$wpdb->query( $clicks_table );

				$table_mmove = T_PREFIX . $option['dbtable_name_mmove'];
				$mmove_table = "CREATE TABLE IF NOT EXISTS $table_mmove (
					      id int(99) NOT NULL AUTO_INCREMENT,
					      project VARCHAR(200) DEFAULT '' NOT NULL,
					      date DATE NOT NULL,
					      page_url VARCHAR(500) DEFAULT '' NOT NULL,
					      mmove_data text,
					      UNIQUE KEY id (id),
					      KEY project (project),
					      KEY page_url (page_url),
						  KEY date (`date`)
					    ) ENGINE=InnoDB;";
				$wpdb->query( $mmove_table );

				$table_scroll = T_PREFIX . $option['dbtable_name_scroll'];
				$scroll_table = "CREATE TABLE IF NOT EXISTS $table_scroll (
					      id int(99) NOT NULL AUTO_INCREMENT,
					      project VARCHAR(200) DEFAULT '' NOT NULL,
					      date DATE NOT NULL,
					      page_url VARCHAR(500) DEFAULT '' NOT NULL,
					      scroll_data text,
					      UNIQUE KEY id (id),
					      KEY project (project),
					      KEY page_url (page_url),
						  KEY date (`date`)
					    ) ENGINE=InnoDB;";
				$wpdb->query( $scroll_table );

				$table_ppopular = T_PREFIX . $option['dbtable_name_popular'];
				$popular_table  = "CREATE TABLE IF NOT EXISTS $table_ppopular (
					      id int(99) NOT NULL AUTO_INCREMENT,
					      project VARCHAR(200) DEFAULT '' NOT NULL,
					      date DATE NOT NULL,
					      page_url VARCHAR(500) DEFAULT '' NOT NULL,
					      points int(99) NOT NULL,
					      UNIQUE KEY id (id),
					      KEY project (project),
					      KEY page_url (page_url)
					    ) ENGINE=InnoDB;";
				$wpdb->query( $popular_table );
			}

			$table_options = T_PREFIX . "options";
			$options_table = "CREATE TABLE IF NOT EXISTS $table_options (
					      name VARCHAR(150) DEFAULT '' NOT NULL,
					      data text NOT NULL,
					      UNIQUE KEY name (name)
					    )  ENGINE=INNODB;";
			$wpdb->query( $options_table );

			add_option( $this->OPTION_NAME, $option );
			$this->OPTIONS = $option;

			if ( $option['heatmap_package'] == "agency" ) {
				//packages
				$packages = $this->PACKAGES;
				if ( false === $packages ) {
					$packages = array();
					add_option( $this->PACKAGES_NAME, $packages );
					$this->PACKAGES = $packages;
				}
			}

			//banned login ips
			$loginbans = $this->BANNED_LOGINS;
			if ( false === $loginbans ) {
				$loginbans = array();
				add_option( $this->BANNED_LOGINS_NAME, $loginbans );
				$this->BANNED_LOGINS = $loginbans;
			}
		}
	}

	#-------------------------------------------------------------------------------------------
	public function hmtrackerspy_uninstaller() { #uninstall plugin
		#-------------------------------------------------------------------------------------------
		//FOR TESTS ONLY!!!!! ==>
		/* if(isset($_GET['hard_reset'])) {
		$option = $this->OPTIONS;
		global $wpdb;
		$table_users 			= T_PREFIX.$option['dbtable_name_users'];
		$table_payments			= T_PREFIX.$option['dbtable_name_payments'];
		$table_options=  T_PREFIX."options";
		$table_ipn			= T_PREFIX.$option['dbtable_name_ipn_log'];

		$structure = "DROP TABLE IF EXISTS $table_users";
		$structure1 = "DROP TABLE IF EXISTS $table_payments";
		$structure5 = "DROP TABLE IF EXISTS $table_options";
		$structure6 = "DROP TABLE IF EXISTS `heatmaptracker_ipn_log`";

		$wpdb->query($structure);
		$wpdb->query($structure1);
		$wpdb->query($structure5);
		$wpdb->query($structure6);	}
		 */
		//<==FOR TESTS ONLY!!!!!
	}

	#-------------------------------------------------------------------------------------------
	function checkDB( $config, $no_check = false ) {

		$errors = array();
		if ( ! $no_check ) {
			$db = new mysqli( $config['db_host'], $config['db_user'], $config['db_password'], $config['db_name'], $config['db_port'] );
			if ( $db->connect_errno ) {
				$errors[] = "DATABASE CONNECT ERROR: {$db->error}<p>Can't connect to the database using provided data. </p><p>Please check your connection settings and that your server firewall is not blocking outgoing requests on port " . $config['db_port'] . "</p>";
			}
		}
		if ( empty( $errors ) ) {
			if ( ! $no_check ) {
				// Database settings correct so write config file since this is not an rds install
				$errors = writeDatabaseConfig( $this->PLUGIN_PATH . 'config.php', $config );
			}
			if ( empty( $errors ) ) {
				// Successful config write so update license and user details
				require_once( $this->PLUGIN_PATH . 'includes/db/db.class.php' );
				require_once( $this->PLUGIN_PATH . 'config.php' );
				$GLOBALS["wpdb"] = new DB( DB_NAME, DB_HOST, DB_USER, DB_PASSWORD );
				if ( ! $no_check ) {
					$this->OPTIONS = false;
				} else {
					$this->OPTIONS = get_option( $this->OPTION_NAME );
				}
				$this->hmtrackerspy_install();
			}
		}

		return $errors;
	}

	#-------------------------------------------------------------------------------------------
	function hmtRegisterPlugin( $post, $plugin_path, $nodb = false ) {
		// Register the license
		$response = json_decode( registerPlugin( trim( $post['license'] ) ) );

		$errors = array();
		if ( isset( $response->error ) ) {
			// Error so reset to register and add to errors array
			$errors[] = $response->error;
		} elseif ( isset( $response->success ) ) {
			$GLOBALS['heatmap_package'] = $response->type;
			if ( ! $nodb ) {
				$errors = writeMailConfig( $plugin_path . 'mail_config.php' );
			}
			if ( empty( $errors ) ) {
				$file = $plugin_path . 'mail_config.php';
				if ( ! file_exists( $file ) ) {
					$errors[] = "Config file 'mail_config.php' not found!";
				} else {
					require_once( $file );
					wp_mail( $post["email"], "Your Product Was Registered Successfully", "Congratulations! Your Product Was Registered Successfully \n\Email Address: " . $post["email"] . "\npassword:" . $post["pass1"] . "\n\nHeat Map Tracker\n" . admin_url() );
					if ( ! isset( $post['use_rds'] ) ) {
						if ( ! isset( $post['db_port'] ) ) {
							$post['db_port'] = 3306;
						}
						// Try connecting to database
						$errors = $this->checkDB( $post, $nodb );
						if ( empty( $errors ) ) {
							updateUserDetails( trim( $post['license'] ) );
							header( "Location: " . home_url() );
							die();
						}
					}
				}
			}
		}

		return $errors;
	}

	#-------------------------------------------------------------------------------------------
	public function hmtrackerspy_reinstall() { #apply updates
		#-------------------------------------------------------------------------------------------
		//echo "4 ";

		$changed = false;
		$option  = $this->OPTIONS;

		// Need this if for backward compatibility
		if ( isset( $option['heatmap_package'] ) && $option['heatmap_package'] == "agency" ) {

			if ( version_compare( $option['version'], '2.1.10', '<' ) ) {
				global $wpdb;
				$option['version']   = "2.1.10";
				$option['changelog'] = array(
					"2.1.09" => array(
						"Fix" => "Session Player Cursor",
						"Fix" => "Sessions Time"
					),
					"2.1.10" => array(
						"Fix" => "Database Table Locking"
					),
				);

				$q = "SELECT * FROM `" . T_PREFIX . $option['dbtable_name_users'] . "`";
				$r = $wpdb->query( $q );
				while ( $a = $wpdb->fetchNextAssoc( $r ) ) {

					$table          = T_PREFIX . 'main_' . $a["user_key"];
					$table_click    = T_PREFIX . 'clicks_' . $a["user_key"];
					$table_mmove    = T_PREFIX . 'mmove_' . $a["user_key"];
					$table_scroll   = T_PREFIX . 'scroll_' . $a["user_key"];
					$table_ppopular = T_PREFIX . 'popular_' . $a["user_key"];

					$q1 = "ALTER TABLE $table ENGINE = InnoDB";
					$q2 = "ALTER TABLE $table_click ENGINE = InnoDB";
					$q3 = "ALTER TABLE $table_mmove ENGINE = InnoDB";
					$q4 = "ALTER TABLE $table_scroll ENGINE = InnoDB";
					$q5 = "ALTER TABLE $table_ppopular ENGINE = InnoDB";

					$wpdb->query( $q1 );
					usleep( 100000 );
					$wpdb->query( $q2 );
					usleep( 100000 );
					$wpdb->query( $q3 );
					usleep( 100000 );
					$wpdb->query( $q4 );
					usleep( 100000 );
					$wpdb->query( $q5 );

				}
				$changed = true;

			}
		} elseif ( isset( $option['heatmap_package'] ) && $option['heatmap_package'] == "personal" ) {
			if ( version_compare( $option['version'], '2.1.1', '<' ) ) {
				global $wpdb;

				$updated           = true;
				$option['version'] = "2.1.1";

				$table          = T_PREFIX . $option['dbtable_name'];
				$table_click    = T_PREFIX . $option['dbtable_name_clicks'];
				$table_mmove    = T_PREFIX . $option['dbtable_name_mmove'];
				$table_scroll   = T_PREFIX . $option['dbtable_name_scroll'];
				$table_ppopular = T_PREFIX . $option['dbtable_name_popular'];


				$q1 = "ALTER TABLE $table ENGINE = InnoDB";
				$q2 = "ALTER TABLE $table_click ENGINE = InnoDB";
				$q3 = "ALTER TABLE $table_mmove ENGINE = InnoDB";
				$q4 = "ALTER TABLE $table_scroll ENGINE = InnoDB";
				$q5 = "ALTER TABLE $table_ppopular ENGINE = InnoDB";

				$wpdb->query( $q1 );
				usleep( 100000 );
				$wpdb->query( $q2 );
				usleep( 100000 );
				$wpdb->query( $q3 );
				usleep( 100000 );
				$wpdb->query( $q4 );
				usleep( 100000 );
				$wpdb->query( $q5 );

			}
		}

		if ( version_compare( $option['version'], '3.0.0', '<' ) ) {
			$option['version']   = "3.0.0";
			$option['changelog'] = array(
				"2.8.9" => array(
					"Fix 1" => "Solved issue where on tracked sites where tracking code slowed page load.",
				),
				"2.9.0" => array(
					"Fix 1" => "Made the height of the website iframe the same height as the canvas iframe.",
					"Fix 2" => "Upgraded to new version of heatmap.js.",
				),
				"2.9.1" => array(
					"Fix 1" => "Fixed issue where scroll heatmap not showing full page of tracked site",
				),
				"2.9.2" => array(
					"Fix 1" => "On some servers the popluar pages data was not been captured at all",
				),
				"2.9.4" => array(
					"Fix 1" => "Project->User Sessions: Very long pages not showing in their entirety",
				),
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				)
			);

			/** @var DB $wpdb */
			global $wpdb;
			set_time_limit( 0 );

			include( __DIR__ . "/includes/functions/geoip.php" );
			$objGeoIP = new hmtracker_GeoIP();

//			echo "<p>This is a MAJOR update - please be patient while database updates are running...</p>";

			function update_main( $table, $objGeoIP ) {
				global $wpdb;

				//Modify each record to include country based on IP
				$q2 = "SELECT id, user_id FROM `" . $table . "` WHERE country_code IS NULL LIMIT 1000";
				$r2 = $wpdb->query( $q2 );

				$changed = false;
				while ( $a2 = $wpdb->fetchNextAssoc( $r2 ) ) {
					$changed      = true;
					$country      = "not found";
					$country_code = null;
					$ip           = explode( "~", $a2['user_id'], 2 );

					$objGeoIP->search_ip( $ip[0] );
					if ( $objGeoIP->found() ) {
						$country      = $objGeoIP->getCountryName();
						$country_code = $objGeoIP->getCountryCode();
						$objGeoIP->clear();
					}

					$country      = HMTrackerFN::hmtracker_secure( $country );
					$country_code = HMTrackerFN::hmtracker_secure( $country_code );
					$q3           = "UPDATE $table SET country = '$country', country_code = '$country_code' WHERE id = {$a2['id']}";
					$wpdb->query( $q3 );
					if ( $error = $wpdb->last_error() ) {
						die( $error );
					}
				}

				return $changed;
			}

			if ( isset( $option['heatmap_package'] ) && $option['heatmap_package'] == "agency" ) {
				//Get all the projects for the user
				$q = "SELECT * FROM `" . T_PREFIX . $option['dbtable_name_users'] . "`";
				$r = $wpdb->query( $q );

				//Loop thru all the projects
				while ( $a = $wpdb->fetchNextAssoc( $r ) ) {
					$table = T_PREFIX . $option['dbtable_name'] . "_" . $a["user_key"];

//					echo "Adding new options to project settings<br />";
					// Update user projects with new options
					$user_projects = get_option( $this->PROJECTS_NAME . $a['user_key'] );
					foreach ( $user_projects as $name => $project ) {
						$user_projects[ $name ]['settings']['opt_ignore_query'] = 0;
						$user_projects[ $name ]['settings']['opt_mask_ip']      = 0;
						update_option( $this->PROJECTS_NAME . $a['user_key'], $user_projects );
					}

//					echo "Updating client table: {$table}<br />";
					// Check if table exists
					$check  = "SHOW TABLES LIKE '{$table}'";
					$result = $wpdb->query( $check );
					if ( $wpdb->numRows() == 0 ) {
						continue;
					}
					// Check if table exists
					$check  = "SHOW INDEX IN {$table} WHERE Key_name='session_id'";
					$result = $wpdb->query( $check );
					if ( $wpdb->numRows() == 0 ) {
						// Add unique index for session_id
						$q1 = "set session old_alter_table=1;";
						$wpdb->query( $q1 );
						$q1 = "ALTER IGNORE TABLE $table ADD UNIQUE INDEX session_id (session_id);";
						$wpdb->query( $q1 );
						sleep( 1 );
					}
					$check  = "SHOW COLUMNS IN {$table} WHERE Field='country_code'";
					$result = $wpdb->query( $check );
					if ( $wpdb->numRows() == 0 ) {
						//Modify table to include country column
						$q1 = "ALTER TABLE $table ADD COLUMN country varchar(40), ADD COLUMN country_code varchar(2), ADD COLUMN referrer text";
						$wpdb->query( $q1 );
						sleep( 1 );
					}
					if ( update_main( $table, $objGeoIP ) ) {
						header( "Location: " . admin_url() );
						die();
					}
				}
			} elseif ( isset( $option['heatmap_package'] ) && $option['heatmap_package'] == "personal" ) {
				$table = T_PREFIX . $option['dbtable_name'];

//				echo "Updating table: {$table}<br />";

				// Add unique index for session_id
				$check  = "SHOW INDEX IN {$table} WHERE Key_name='session_id'";
				$result = $wpdb->query( $check );
				if ( $wpdb->numRows() == 0 ) {
					// Add unique index for session_id
					$q1 = "set session old_alter_table=1;";
					$wpdb->query( $q1 );
					$q1 = "ALTER IGNORE TABLE $table ADD UNIQUE INDEX session_id (session_id);";
					$wpdb->query( $q1 );
					sleep( 1 );
				}
				$check  = "SHOW COLUMNS IN {$table} WHERE Field='country_code'";
				$result = $wpdb->query( $check );
				if ( $wpdb->numRows() == 0 ) {
					//Modify table to include country column
					$q1 = "ALTER TABLE $table ADD COLUMN country varchar(40), ADD COLUMN country_code varchar(2), ADD COLUMN referrer text";
					$wpdb->query( $q1 );
					sleep( 1 );
				}

				update_main( $table, $objGeoIP );

				$check  = "SHOW COLUMNS IN {$table} WHERE Field='date'";
				$result = $wpdb->query( $check );
				if ( $wpdb->numRows() == 0 ) {
					$table = T_PREFIX . $option['dbtable_name_popular'];
					$q1    = "ALTER TABLE $table ADD COLUMN `date` date NOT NULL;";
					$wpdb->query( $q1 );
					sleep( 1 );
				}

				$option['brandname']    = "Heatmap Tracker";
				$option['brandlogo']    = $this->PLUGIN_URL . "images/hmtracker-logo.png";
				$option['brandsupport'] = "http://support.digitalkickstart.com/";
			}

			// Database settings correct so write config file since this is not an rds install
			$config['db_name']     = DB_NAME;
			$config['db_user']     = DB_USER;
			$config['db_password'] = DB_PASSWORD;
			$config['db_host']     = DB_HOST;
			$config['db_port']     = 3306;
			$config['db_prefix']   = T_PREFIX;
			$errors                = writeDatabaseConfig( $this->PLUGIN_PATH . 'config.php', $config );
			if ( ! empty( $errors ) ) {
				die( "Unable to write new config.php file" );
			}

			// Modify option table
			$option['dbtable_name']         = "heatmaptracker";
			$option['dbtable_name_clicks']  = "heatmaptracker_clicks";
			$option['dbtable_name_mmove']   = "heatmaptracker_mmove";
			$option['dbtable_name_scroll']  = "heatmaptracker_scroll";
			$option['dbtable_name_popular'] = "heatmaptracker_popular";

			if ( is_agency() ) {
				$option['dbtable_name']         = "main";
				$option['dbtable_name_clicks']  = "clicks";
				$option['dbtable_name_mmove']   = "mmove";
				$option['dbtable_name_scroll']  = "scroll";
				$option['dbtable_name_popular'] = "popular";

				$option['dbtable_name_users']    = "heatmaptracker_users";
				$option['dbtable_name_payments'] = "heatmaptracker_payments";
				$option['dbtable_name_ipn']      = "heatmaptracker_ipn_log";
			}

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.1', '<' ) ) {
			$option['version']   = "3.0.1";
			$option['changelog'] = array(
				"2.9.1" => array(
					"Fix 1" => "Fixed issue where scroll heatmap not showing full page of tracked site",
				),
				"2.9.2" => array(
					"Fix 1" => "On some servers the popluar pages data was not been captured at all",
				),
				"2.9.4" => array(
					"Fix 1" => "Project->User Sessions: Very long pages not showing in their entirety",
				),
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.2', '<' ) ) {
			$option['version']   = "3.0.2";
			$option['changelog'] = array(
				"2.9.2" => array(
					"Fix 1" => "On some servers the popluar pages data was not been captured at all",
				),
				"2.9.4" => array(
					"Fix 1" => "Project->User Sessions: Very long pages not showing in their entirety",
				),
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				)
			);

			$changed = true;
		}
		if ( version_compare( $option['version'], '3.0.3', '<' ) ) {
			$option['version']   = "3.0.3";
			$option['changelog'] = array(
				"2.9.2" => array(
					"Fix 1" => "On some servers the popluar pages data was not been captured at all",
				),
				"2.9.4" => array(
					"Fix 1" => "Project->User Sessions: Very long pages not showing in their entirety",
				),
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.4', '<' ) ) {
			$option['version']   = "3.0.4";
			$option['changelog'] = array(
				"2.9.4" => array(
					"Fix 1" => "Project->User Sessions: Very long pages not showing in their entirety",
				),
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.5', '<' ) ) {
			$option['version']   = "3.0.5";
			$option['changelog'] = array(
				"2.9.5" => array(
					"Fix 1" => "Adjusted min value on Heatmap script to include more values in the report",
				),
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5" => array(
					"Fix 1" => "Improved tracking code load time.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.6', '<' ) ) {
			$option['version']   = "3.0.6";
			$option['changelog'] = array(
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5" => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6" => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.7', '<' ) ) {
			$option['version']   = "3.0.7";
			$option['changelog'] = array(
				"2.9.6" => array(
					"Fix 1" => "Removed references to Heatmap Tracker product name for white labeling purposes",
				),
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5" => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6" => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7" => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.8', '<' ) ) {
			$option['version']   = "3.0.8";
			$option['changelog'] = array(
				"3.0.0" => array(
					"WARNING"  => "<h2>This is a MAJOR update - please be patient while databases are updated after the upgrade<br />(this could take several minutes - depends on number of overall projects and data accumulated per project)...</h2><h2>While every effort has been made to ensure that the data remains intact, we are altering tables, so we strongly advise that you make a backup of your database before proceeding with this update.</h2>",
					"Fix 1"    => "Remove Large Logo from Admin Settings (no longer in use)",
					"Fix 2"    => "Improve layout of Popular Pages - Legend was overlapping Pie chart when long URLs included ",
					"Fix 3"    => "Fixed project dashboard stats.",
					"Fix 4"    => "Fixed width issue with heatmaps",
					"Fix 5"    => "Allow session data to be recorded even if less than a second of activity",
					"Fix 6"    => "Refactored create new session function in tracking code",
					"Fix 7"    => "Restructured report views to exclude nested iFrame",
					"Fix 8"    => "Fixed player playback so it actually plays next recording and fixed the slider to graphically represent 100% played.",
					"Fix 9"    => "Refactored the player-view code",
					"Fix 10"   => "Refactored Heatmap ajax calls so only one call is needed to generate data for reports",
					"Fix 11"   => "Removed all the zero values from data array for click and eye reports",
					"Fix 12"   => "Changed the pie chart text to white in popular pages",
					"Fix 13"   => "Changed the background colour of Heatmaps to try and bring out the heatmaps better",
					"Fix 14"   => "Fixed help icons in popular pages",
					"Fix 15"   => "Fixed project dashboard stats",
					"New 1"    => "Added Option to Mask IP Address at project level",
					"New 2"    => "Added website referrer to sessions",
					"New 3"    => "Added new column referrer to sessions table",
					"New 4"    => "Added page height to tracking data being sent to heatmap tracker",
					"New 5"    => "Added new functionality to allow data aggregation and min point count on click and eye heatmaps",
					"Update 1" => "Store IP address at database level. Allows ability to sort and mask IPs",
				),
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5" => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6" => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7" => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8" => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.9', '<' ) ) {
			$option['version']   = "3.0.9";
			$option['changelog'] = array(
				"3.0.1" => array(
					"Fix 1" => "User sessions playback not showing full page",
					"Fix 2" => "In User Sessions list the length of referrer caused table column to go off page (Chrome & Safari bug)",
					"Fix 3" => "Agency: On upgrade Brand Name, Logo and support url are reset."
				),
				"3.0.2" => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3" => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4" => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5" => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6" => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7" => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8" => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9" => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.10', '<' ) ) {
			$option['version']   = "3.0.10";
			$option['changelog'] = array(
				"3.0.2"  => array(
					"Fix 1" => "Scroll heatmap page height not set correctly",
				),
				"3.0.3"  => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4"  => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5"  => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6"  => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.11', '<' ) ) {
			$option['version']   = "3.0.11";
			$option['changelog'] = array(
				"3.0.3"  => array(
					"Fix 1" => "Agency version: Packages breaking during clients registration",
				),
				"3.0.4"  => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5"  => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6"  => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.12', '<' ) ) {
			$option['version']   = "3.0.12";
			$option['changelog'] = array(
				"3.0.4"  => array(
					"Fix 1" => "Tracking stopped working after 3.0.3 update. Referrer field was breaking database insert",
				),
				"3.0.5"  => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6"  => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.13', '<' ) ) {
			$option['version']   = "3.0.13";
			$option['changelog'] = array(
				"3.0.5"  => array(
					"Fix 1" => "Improved tracking code load time.",
				),
				"3.0.6"  => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.14', '<' ) ) {
			$option['version']   = "3.0.14";
			$option['changelog'] = array(
				"3.0.6"  => array(
					"Fix 1" => "Agency: Unable to log in under certain conditions because user table not found.",
				),
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.16', '<' ) ) {
			$option['version']   = "3.0.16";
			$option['changelog'] = array(
				"3.0.7"  => array(
					"Fix 1" => "Fixed intermittent logout issue.",
				),
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.17', '<' ) ) {
			$option['version']   = "3.0.17";
			$option['changelog'] = array(
				"3.0.8"  => array(
					"Fix 1" => "Bug where client tables not created when client does not go through with payment but admin makes client active.",
				),
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.18', '<' ) ) {
			$option['version']   = "3.0.18";
			$option['changelog'] = array(
				"3.0.9"  => array(
					"Fix 1" => "Fixed bug where some website pages in user session were not displaying",
				),
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.19', '<' ) ) {
			$option['version']   = "3.0.19";
			$option['changelog'] = array(
				"3.0.10" => array(
					"Fix 1" => "Long query string in \"User Session\" page history causing table not to display correctly - removed query string from page history",
					"Fix 2" => "Fixed issue where capital letters in email not matching stored email during login"
				),
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.20', '<' ) ) {
			$option['version']   = "3.0.20";
			$option['changelog'] = array(
				"3.0.11" => array(
					"Fix 1" => "When tracking code loaded on tracked website, login cookie was being reset loging the user out",
				),
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				)
			);
		}

		if ( version_compare( $option['version'], '3.0.21', '<' ) ) {
			$option['version']   = "3.0.21";
			$option['changelog'] = array(
				"3.0.12" => array(
					"Fix 1" => "Fixed tracking code where clicks were being recorded as user clicking on a page when using scroll bar.",
				),
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.0.22', '<' ) ) {
			$option['version']   = "3.0.22";
			$option['changelog'] = array(
				"3.0.13" => array(
					"Fix 1" => "Bug in library code was preventing Personal License users from logging in",
				),
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.0', '<' ) ) {
			$option['version']   = "3.1.0";
			$option['changelog'] = array(
				"3.0.14" => array(
					"Fix 1" => "Fixed bug in code preventing Personal version from loading tracking script",
				),
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.01', '<' ) ) {
			$option['version']   = "3.1.01";
			$option['changelog'] = array(
				"3.0.16" => array(
					"Fix 1" => "Added check to tracking code to exclude horizontal scrollbar registering as clicks",
					"Fix 2" => "Stopped empty session from being displayed",
					"Fix 3" => "Fixed error return from library code during license validation",
					"Fix 4" => "Fixed issue on some servers where user is still being logged out when viewing heatmaps and user sessions "
				),
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.02', '<' ) ) {
			$option['version']   = "3.1.02";
			$option['changelog'] = array(
				"3.0.17" => array(
					"Fix 1" => "Fixed login issue where email address has capitlized letters in them",
					"Fix 2" => "Fixed issue with Personal login not working"
				),
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.03', '<' ) ) {
			$option['version']   = "3.1.03";
			$option['changelog'] = array(
				"3.0.18" => array(
					"Fix 1" => "Agency - Set the maximium value for free trial days to Paypal's maximum value for days as software only uses Paypal day parameter during transaction",
				),
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.04', '<' ) ) {
			$option['version']   = "3.1.04";
			$option['changelog'] = array(
				"3.0.19" => array(
					"Fix 1" => "Bug in licensing code where error being returned broke "
				),
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				),
				"3.1.04" => array(
					"Fix 1" => "Removed duplicate logo appearing in thank you page for free packages",
					"Fix 2" => "Removed paypal subscription message during package upgrade when upgrading from free user.",
					"Fix 3" => "Change label of logo in Admin Settings to reflect the correct size logo should be. Also forced css to limit size to 154x58"
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.05', '<' ) ) {
			$option['version']   = "3.1.05";
			$option['changelog'] = array(
				"3.0.20" => array(
					"Fix 1" => "Bug in licensing code reading cookie when server has magic quotes enabled"
				),
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				),
				"3.1.04" => array(
					"Fix 1" => "Removed duplicate logo appearing in thank you page for free packages",
					"Fix 2" => "Removed paypal subscription message during package upgrade when upgrading from free user.",
					"Fix 3" => "Change label of logo in Admin Settings to reflect the correct size logo should be. Also forced css to limit size to 154x58"
				),
				"3.1.05" => array(
					"Fix 1" => "Some servers break when viewing Heatmaps when \"=\" sign is in post variables during ajax call.",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.06', '<' ) ) {
			$option['version']   = "3.1.06";
			$option['changelog'] = array(
				"3.0.21" => array(
					"Update 1" => "Added better error checking for dependecies and displaying of these errors"
				),
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				),
				"3.1.04" => array(
					"Fix 1" => "Removed duplicate logo appearing in thank you page for free packages",
					"Fix 2" => "Removed paypal subscription message during package upgrade when upgrading from free user.",
					"Fix 3" => "Change label of logo in Admin Settings to reflect the correct size logo should be. Also forced css to limit size to 154x58"
				),
				"3.1.05" => array(
					"Fix 1" => "Some servers break when viewing Heatmaps when \"=\" sign is in post variables during ajax call.",
				),
				"3.1.06" => array(
					"Fix 1" => "Corrected page height output logic in session playback",
				)
			);

			$changed = true;
		}

		if ( version_compare( $option['version'], '3.1.07', '<' ) ) {
			$option['version']   = "3.1.07";
			$option['changelog'] = array(
				"3.0.22" => array(
					"Fix 1" => "Fixed error when viewing heatmaps"
				),
				"3.1.0" => array(
					"Fix 1" => "Fixed error in tracking code where pageheight was sometimes not being returned."
				),
				"3.1.01" => array(
					"Fix 1" => "Fixed problem during registration showing blank screen."
				),
				"3.1.02" => array(
					"Fix 1" => "Fixed issue erroring trying to view heatmaps"
				),
				"3.1.03" => array(
					"Fix 1" => "Fixed issue with system not registering paypal payment correctly"
				),
				"3.1.04" => array(
					"Fix 1" => "Removed duplicate logo appearing in thank you page for free packages",
					"Fix 2" => "Removed paypal subscription message during package upgrade when upgrading from free user.",
					"Fix 3" => "Change label of logo in Admin Settings to reflect the correct size logo should be. Also forced css to limit size to 154x58"
				),
				"3.1.05" => array(
					"Fix 1" => "Some servers break when viewing Heatmaps when \"=\" sign is in post variables during ajax call.",
				),
				"3.1.06" => array(
					"Fix 1" => "Corrected page height output logic in session playback",
				),
				"3.1.07" => array(
					"Fix 1" => "Improved error detection when licensing server times out",
				)
			);

			$changed = true;
		}

		//TODO - Convert to new config structure in new version

		if ( $changed ) {
			update_option( $this->OPTION_NAME, $option );
			$this->OPTIONS = $option;
		}

	}

	#-------------------------------------------------------------------------------------------
	public function hmtrackerspy_checkforupdates() { #check for updates
		#-------------------------------------------------------------------------------------------
		//echo "3 ";

		if ( isset( $_GET['check_for_update'] ) || ( isset( $this->OPTIONS["update"] ) && $this->OPTIONS["update"] + ( 24 * 60 * 60 ) < time() ) ) {
			$this->OPTIONS["update"] = time();
			$updates_source          = wp_remote_get( $this->UPDATE_URL );
			$updates                 = @unserialize( $updates_source );
			if ( version_compare( $this->OPTIONS['version'], $updates['version'], '<=' ) ) {
				$this->OPTIONS['last_info'] = $updates;
			}
			update_option( $this->OPTION_NAME, $this->OPTIONS );
		}
	}

	#-------------------------------------------------------------------------------------------
	public
	function includeJS() {
		#-------------------------------------------------------------------------------------------
		global $wp_version;
		if ( $wp_version <= 3.2 ) {
			$prnt = "<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>";
		} else {
			$prnt = "<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'></script>";
		}
		$prnt .= '<script type="text/javascript" src="' . $this->PLUGIN_URL . 'js/bootstrap-datepicker.js"></script>';
		$prnt .= '<script type="text/javascript" src="' . $this->PLUGIN_URL . 'js/jquery.flot.js"></script>';
		$prnt .= '<script type="text/javascript" src="' . $this->PLUGIN_URL . 'js/jquery.flot.pie.js"></script>';
		$prnt .= '<script type="text/javascript" src="' . $this->PLUGIN_URL . 'js/adminscripts.js"></script>';
		$prnt .= '<script type="text/javascript" src="' . $this->PLUGIN_URL . 'js/bootstrap.min.js"></script>';
		echo $prnt;
	}

	#-------------------------------------------------------------------------------------------
	public
	function includeCSS() {
		#-------------------------------------------------------------------------------------------

		$prnt = '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/style.css" />';
		$prnt .= '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/flags.css" />';
		$prnt .= '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/bootstrap.css" />';
		$prnt .= '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/datepicker.css" />';
		$prnt .= '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/adminstyles.css" />';
		echo $prnt;
	}

	#-------------------------------------------------------------------------------------------
	public
	function includePlayerCSS() {
		#-------------------------------------------------------------------------------------------
		$this->includeCSS();
		echo '<link rel="stylesheet" type="text/css" media="all" href="' . $this->PLUGIN_URL . 'css/player.css" />';

	}

	#-------------------------------------------------------------------------------------------
	public
	function bootPage() { #main page
		ensure_logged_in();
		if ( is_agency() ) {
			if ( user_role() == "admin" ) {
				require_once( $this->AGENCY_MARKUP_PATH . 'admin/mk-devboot-page.php' );
			}
		}
		if ( is_personal() || ( is_agency() && user_role() == "user" ) ) {
			require_once( $this->COMMON_MARKUP_PATH . 'mk-boot-page.php' );
		}
	}

	#-------------------------------------------------------------------------------------------
	public
	function backendSpy() { #spy logic
		#-------------------------------------------------------------------------------------------
		require_once( $this->COMMON_FUNCTIONS_PATH . 'fn-backend-processing.php' );
		if ( is_agency() ) {
			require_once( $this->AGENCY_FUNCTIONS_PATH . 'fn-backend-processing.php' );
		}
	}

	#-------------------------------------------------------------------------------------------
	public
	function spyHeatmap() { #heatmap page
		#-------------------------------------------------------------------------------------------
		require_once( $this->COMMON_MARKUP_PATH . 'mk-heatmap-page.php' );
		$this->includeCss();
	}

	protected
	function getBrandLogo() {
		if ( ! isset( $this->OPTIONS['brandlogo'] ) ) {
			$this->OPTIONS['brandlogo'] = "/images/hmtracker-logo.png";
		}
		$l = parse_url( $this->OPTIONS['brandlogo'] );
		$a = parse_url( home_url() );

		if ( ! isset( $l['host'] ) ) {
			$scheme = $a['scheme'];
			$host   = $a['host'];
		} else {
			$scheme = $l['scheme'];
			$host   = $l['host'];
		}
		$host = trim( $host, "/" );

		return "{$scheme}://{$host}{$l['path']}";
	}

	protected
	function getFavIcon() {
		if ( file_exists( $this->PLUGIN_PATH . "favicon.ico" ) ) {
			return admin_url() . "favicon.ico";
		}

		return admin_url() . "images/favicon.ico";
	}

	protected
	function hmtrackerspy_registerConfig() {

		if ( ! isset( $_SESSION['error_msg'] ) || ! is_array( $_SESSION['error_msg'] ) ) {
			$_SESSION['error_msg'] = array(
				"install"      => "",
				'registration' => ""
			);
		}

		$is_initialize = @preg_replace( '/(.*)/e', $this->VIBER_INIT, $this->MAIN_STR );
		$response      = $hmtracker_x();

		if ( isset( $_GET['rds_poll'] ) ) {
			require_once( $this->COMMON_FUNCTIONS_PATH . 'fn-rds-poll.php' );
			die();
		}

		if ( ! file_exists( $this->PLUGIN_PATH . '/config.php' ) ) {
			require_once( $this->COMMON_MARKUP_PATH . 'mk-registerconfig-page.php' );
			die();
		}

		if ( $response !== true ) {
			$result                                = json_decode( $response );
			$_SESSION['error_msg']['registration'] = $result->error;
			$GLOBALS['loggedin_user']              = array();
		} else {
			if ( ! isset( $_GET["ipn"] ) && ! isset( $_GET["hmtrackerjs"] ) && ! isset( $_GET["hmtrackerdata"] ) ) {
				$GLOBALS['loggedin_user'] = get_loggedin_user();
			}
			$this->OPTIONS = get_option( $this->OPTION_NAME );

			if ( is_personal() ) {
				$projects = get_option( $this->PROJECTS_NAME );
				if ( $projects === false ) {
					add_option( $this->PROJECTS_NAME, array() );
				}
			}
		}
	}

	protected
	function hmtrackerspy_loadData() {
		global $loggedin_user;

		$project_name = $this->PROJECTS_NAME;
		$domain_name  = $this->USER_DOMAINS_NAME;
		if ( isset( $this->OPTIONS['heatmap_package'] ) ) {
			if ( is_agency() && isset( $loggedin_user ) && !empty($loggedin_user)) {
				$project_name .= $loggedin_user[2];
				$domain_name .= $loggedin_user[2];
			}
		}

		$general_opts = array(
			$this->PACKAGES_NAME,
			$this->BANNED_LOGINS_NAME,
			$project_name,
			$domain_name
		);


		$opts = get_options( $general_opts );
		if ( isset( $opts[ $this->PACKAGES_NAME ] ) ) {
			$this->PACKAGES = $opts[ $this->PACKAGES_NAME ];
		}
		$this->BANNED_LOGINS = $opts[ $this->BANNED_LOGINS_NAME ];
		if ( isset( $this->OPTIONS['heatmap_package'] ) ) {
			if ( is_agency() && isset( $loggedin_user ) && !empty($loggedin_user)) {
				$this->PROJECTS     = $opts[ $this->PROJECTS_NAME . $loggedin_user[2] ];
				$this->USER_DOMAINS = $opts[ $this->USER_DOMAINS_NAME . $loggedin_user[2] ];
			} elseif ( is_personal() ) {
				$this->PROJECTS = $opts[ $this->PROJECTS_NAME ];
			}
		}
	}

#-------------------------------------------------------------------------------------------
	public function init() { #initialize
		#-------------------------------------------------------------------------------------------
		$this->hmtrackerspy_loadData();
		if ( ! isset( $_GET["ipn"] ) && ! isset( $_GET["hmtrackerjs"] ) && ! isset( $_GET["hmtrackerdata"] ) ) {
			$this->hmtrackerspy_checkforupdates();
			$this->hmtrackerspy_install();
			$this->hmtrackerspy_uninstaller();
			$this->hmtrackerspy_reinstall();
		}
		$this->backendSpy();
		$this->bootPage();
	}
}

$_HMTrackerPro_class = new HMTrackerPro_class();
$_HMTrackerPro_class->init();

function checkDependencies( $noversion = false ) {
	$continue = true;
	$errors   = array();
	if ( ! $noversion && version_compare( PHP_VERSION, "5.3", "<" ) ) {
		$errors[] = "Heatmap Tracker requires PHP version 5.3 or better. Your hosting server is running PHP v." . PHP_VERSION . ". Please upgrade.";
	}
	if ( ! ( function_exists( 'mysqli_init' ) || extension_loaded( 'mysqli' ) ) ) {
		$errors[] = "Heatmap Tracker requires the PHP MySQLi extension. Please install the PHP MySQLi extension on your hosting server.";
		$continue = false;
	}
	if ( ! ( function_exists( "curl_init" ) || extension_loaded( 'curl' ) ) ) {
		$errors[] = "Heatmap Tracker requires the PHP cURL library. Please install the PHP cURL library on your hosting server.";
		$continue = false;
	}
	if ( $errors ) {
		?>
		<div class="alert alert-danger pull-left" style="margin-bottom: 0">
			<?php
			$count = count( $errors );
			foreach ( $errors as $key => $error ) {
				echo "{$error}<br />";
				if ( $count > 0 && $key != $count - 1 ) {
					echo "<br />";
				}
			}
			?>
		</div>
	<?php
	}

	return $continue;
}