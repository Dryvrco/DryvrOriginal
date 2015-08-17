<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */

if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}

global $loggedin_user;
if ( ! is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
}
include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content-holder">
	<div class="analytics-block">
		<h2>Help Videos</h2>

		<div class="table-holder">
			<h3></h3>
			<?php echo html_entity_decode( $this->OPTIONS['help_area'] ) ?>
			<h3></h3>
		</div>
	</div>
</div>
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
