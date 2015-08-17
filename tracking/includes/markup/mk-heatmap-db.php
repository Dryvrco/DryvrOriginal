<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2014/06/30
 * Time: 2:51 PM
 */

require_once( '../../config.php' );
require_once( '../db/db.class.php' );

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

$wpdb = new DB( DB_NAME, DB_HOST, DB_USER, DB_PASSWORD );

$click_table  = T_PREFIX . $_POST['clicks_table'];
$mmove_table  = T_PREFIX . $_POST['mmove_table'];
$scroll_table = T_PREFIX . $_POST['scroll_table'];
if ( $_POST['package'] == "agency" ) {
	$click_table .= "_{$_POST['session']}";
	$mmove_table .= "_{$_POST['session']}";
	$scroll_table .= "_{$_POST['session']}";
}

$type = '';
switch ( $_POST['map'] ) {
	case 'click':
		$type     = 'Click Heatmap';
		$clicks   = $wpdb->get_results( "SELECT `click_data` FROM $click_table WHERE `page_url` = '$_POST[url]' AND  date >= '$_POST[from]' AND date <= '$_POST[to]'" );
		$clickArr = array();
		foreach ( $clicks as $key => $value ) {
			$ex = explode( "|", $value->click_data );
			unset( $ex[0] );
			$clickArr = array_merge( $clickArr, $ex );
		}
		break;
	case 'mmove':
		$type     = 'Eyescroll Heatmap';
		$clicks   = $wpdb->get_results( "SELECT `mmove_data` FROM $mmove_table WHERE `page_url` = '$_POST[url]' AND  date >= '$_POST[from]' AND date <= '$_POST[to]'" );
		$clickArr = array();
		foreach ( $clicks as $key => $value ) {
			$ex = explode( "|", $value->mmove_data );
			unset( $ex[0] );
			$clickArr = array_merge( $clickArr, $ex );
		}
		break;

	case 'scroll':
		$type     = 'Scroll Heatmap';
		$clicks   = $wpdb->get_results( "SELECT `scroll_data` FROM $scroll_table WHERE `page_url` = '$_POST[url]' AND  date >= '$_POST[from]' AND date <= '$_POST[to]'" );
		$clickArr = array();
		foreach ( $clicks as $key => $value ) {
			$clickArr = array_merge( $clickArr, explode( "|", $value->scroll_data ) );
		}
		break;
}