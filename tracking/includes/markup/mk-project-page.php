<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
?>
<?php
if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}

global $loggedin_user, $wpdb;

if ( ! is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
}

$main_table  = T_PREFIX . $this->OPTIONS['dbtable_name'];
$click_table = T_PREFIX . $this->OPTIONS['dbtable_name_clicks'];
if ( is_agency() ) {
	$user          = current_user();
	$cur_status_id = detect_user_status( $user );
	$ui_enabled    = validate_user_status( $cur_status_id );
	if ( ! $ui_enabled ) {
		die( "Subscription status issue" );
	}
	$main_table .= "_{$user->user_key}";
	$click_table .= "_{$user->user_key}";
}


$project      = $_GET["name"];
$_GET["name"] = rawurlencode( $_GET["name"] );

$pages_count_q = "SELECT COUNT( distinct `page_url`) FROM `" . $click_table . "` WHERE `project` = '" . $_GET['name'] . "'";
$pages_count   = $wpdb->queryUniqueValue( $pages_count_q );

$session_count_q = "SELECT COUNT( `session_id`) FROM `" . $main_table . "` WHERE `project` = '" . $_GET['name'] . "'";
$session_count   = $wpdb->queryUniqueValue( $session_count_q );


$usr_uniq_q     = "SELECT COUNT( distinct `user_id` ) FROM `" . $main_table . "` WHERE `project` = '" . $_GET['name'] . "'";
$usr_uniq_res   = $wpdb->queryUniqueValue( $usr_uniq_q );

$session_q            = "SELECT distinct `user_id`, `country_code`, COUNT(`user_id`) as `count` FROM `" . $main_table . "` WHERE `project` = '" . $_GET["name"] . "' group by `user_id`";
$session_res          = $wpdb->get_results( $session_q );
$countries_collection = array();
foreach ( $session_res as $session_res_value ) {
	$c_code = strtolower( $session_res_value->country_code );
	if ( ! isset( $countries_collection[ $c_code ] ) ) {
		$countries_collection[ $c_code ] = 0;
	}
	$countries_collection[ $c_code ] += $session_res_value->count;
}
$_data_str = '{';
foreach ( $countries_collection as $country_code => $country_count ) {
	$_data_str .= '"' . $country_code . '":"' . $country_count . '",';
}
$_data_str .= '}';

include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content">
	<div class="info-block">
		<div class="info-box green">
			<span>TOTAL VISITORS</span>
			<strong><?php echo $usr_uniq_res; ?></strong>
		</div>
		<div class="info-box orange">
			<span>TRACKING PAGES</span>
			<strong><?php echo $pages_count; ?></strong>
		</div>
		<div class="info-box blue">
			<span>TOTAL SESSIONS</span>
			<strong><?php echo $session_count; ?></strong>
		</div>
	</div>
	<div class="code-block">
		<span class="text">COPY HEAT MAP TRACKING CODE:</span>
		<strong class="title">Your Heat Map Tracking Code</strong>

		<div class="holder">
			<textarea class="code" rows="3"><?php include( 'views/mk-hmtrackerjs.php' ); ?></textarea>
			<a class="btn-code" href="#">SELECT ALL</a>
		</div>
	</div>
	<div class="graphs-block">
		<div class="graph-box">
			<h2>20 Days Activity</h2>

			<div id="site_statistics_content" class="hide">
				<div id="site_statistics" class="chart"></div>
			</div>
		</div>
		<div class="graph-box">
			<h2>Regional Stats (All Time)</h2>

			<div id="region_statistics_content" class="hide">
				<div class="btn-toolbar no-top-space clearfix">
					<div class="btn-group pull-right">
						<button class="btn btn-mini dropdown-t	oggle" data-toggle="dropdown">
							Select Region
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li><a href="javascript:;" id="regional_stat_world">World</a></li>
							<li><a href="javascript:;" id="regional_stat_usa">USA</a></li>
							<li><a href="javascript:;" id="regional_stat_europe">Europe</a></li>
							<li><a href="javascript:;" id="regional_stat_russia">Russia</a></li>
							<li><a href="javascript:;" id="regional_stat_germany">Germany</a></li>
						</ul>
					</div>
				</div>
				<div id="vmap_world" class="vmaps  chart hide"></div>
				<div id="vmap_usa" class="vmaps chart hide"></div>
				<div id="vmap_europe" class="vmaps chart hide"></div>
				<div id="vmap_russia" class="vmaps chart hide"></div>
				<div id="vmap_germany" class="vmaps chart hide"></div>
			</div>
		</div>
	</div>
</div>
<!-- END PAGE CONTAINER-->
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/jquery.vmap.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery.peity.min.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/flot/jquery.flot.js" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/index.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	<?php echo 'var sample_data = '.$_data_str.';';?>
	var activity = [];
	var dates = [];
	var time = [];
	var time_format = [];
	<?php
	if(isset($this -> PROJECTS[$_GET['name']]['settings']['opt_record_tz'])) {
		date_default_timezone_set($this -> PROJECTS[$_GET['name']]['settings']['opt_record_tz']);
	}
	for ( $i = 19, $j = 1; $i >= 0; $i --, $j ++ ) {

		$delta_data_min = strtotime( "today", strtotime( "-" . ( $i ) . " day" ) ) ;
		$delta_data_max = strtotime( "today", strtotime( "-" . ( $i - 1 ) . " day" ) ) - 1;

		//Activity
		$act_q = "SELECT COUNT(*) FROM `".$main_table."` WHERE `project` = '".$_GET["name"]."' AND `session_start` >  ".($delta_data_min)." AND `session_start` <  ".($delta_data_max);
		$time_q = "SELECT sum(`session_end` - `session_start`) FROM `".$main_table."` WHERE `project` = '".$_GET["name"]."' AND `session_start` >  ".($delta_data_min)." AND `session_start` <  ".($delta_data_max);
		$act_res = $wpdb->queryUniqueValue($act_q);
		$time_res = $wpdb->queryUniqueValue($time_q);
		echo "activity[".$j."]=[".$j.",".$act_res."]; \n";
		echo "dates[".$j."]='".date("M d, Y",$delta_data_min)."'; \n";
		echo "time[".$j."]=[".$j.",".(empty($time_res)?0:$time_res/100)."]; \n";
		echo "time_format[".$j."]='".HMTrackerFN::sec2hms($time_res)."'; \n";
	}
	?>
	jQuery(document).ready(function () {
		App.init(); // initlayout and core plugins
		Index.init();
		Index.initJQVMAP(); // init index page's custom scripts init index page's custom scripts
		Index.initCharts(); // init index page's custom scripts

		jQuery(".code-block textarea").focus(function () {
			jQuery(this).select();
		});

		jQuery('.btn-code').click(function () {
			jQuery(this).parent().find('.code').focus().select();
			return false;
		})

		jQuery('#createproject').click(function () {
			if (jQuery('input[name="projectname"]').val() == "") {
				jQuery('input[name="projectname"]').focus();
				return false
			}
			if (jQuery('textarea[name="projectdescription"]').val() == "") {
				jQuery('textarea[name="projectdescription"]').focus();
				return false
			}

			var post = {}
			post.action = 'create';
			post.name = encodeURIComponent(jQuery('input[name="projectname"]').val());
			post.description = jQuery('textarea[name="projectdescription"]').val();

			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>?hmtrackeractions', post, function (data) {
				jQuery('#createproject').button('reset')
				if (data == 'ok') {
					location.reload();
				}
			});
		})
		jQuery('.delproject').click(function () {
			jQuery('#delprojtitle').text(jQuery(this).attr('data-value'));
			jQuery('#delprojectaction').attr('data-value', jQuery(this).attr('data-value'));
		})

		jQuery('#delprojectaction').click(function () {
			var post = {}
			post.action = 'delete';
			post.name = jQuery(this).attr('data-value');

			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>?hmtrackeractions', post, function (data) {
				jQuery('#createproject').button('reset')
				if (data == 'ok') {
					location.reload();
				}
			});
		})


	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
