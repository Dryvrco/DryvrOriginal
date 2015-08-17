<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */

if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}
global $loggedin_user, $wpdb;
if ( ! is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
}

$main_table = T_PREFIX . $this->OPTIONS['dbtable_name'];
$click_table = T_PREFIX . $this->OPTIONS['dbtable_name_clicks'];
$mmove_table = T_PREFIX . $this->OPTIONS['dbtable_name_mmove'];
$scroll_table = T_PREFIX . $this->OPTIONS['dbtable_name_scroll'];
$popular_table = T_PREFIX . $this->OPTIONS['dbtable_name_popular'];
if ( is_agency() ) {
	$user          = current_user();
	$cur_status_id = detect_user_status( $user );
	$ui_enabled    = validate_user_status( $cur_status_id );
	if ( ! $ui_enabled ) {
		die( "Subscription status issue" );
	}

	$main_table .= "_{$loggedin_user[2]}";
	$click_table .= "_{$loggedin_user[2]}";
	$mmove_table .= "_{$loggedin_user[2]}";
	$scroll_table .= "_{$loggedin_user[2]}";
	$popular_table .= "_{$loggedin_user[2]}";
}
$_GET["name"] = rawurlencode( $_GET["name"] );

$points_src    = $wpdb->get_results( "SELECT * FROM $popular_table WHERE `project` = '" . $_GET["name"] . "' ORDER BY `points` DESC LIMIT 50" );
$points_total  = $wpdb->queryUniqueValue( "SELECT sum(`points`) FROM $popular_table WHERE `project` = '" . $_GET["name"] . "'" );

include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content">
	<div class="analytics-block">
		<h2>Popular Pages</h2>

		<div class="table-holder">
			<?php if ( ! empty( $points_src ) ) { ?>
				<h5>Top 10 <a class="help-ico" data-trigger="hover" rel="popover" data-original-title="Top 10" data-content="10 most popular pages. The percentage is based on the total viewing time.">lnk</a></h5>

				<div id="pie" style="width:100%; height: 400px; margin: 0 auto">
					Pie Chart
				</div>
				<h5>Top 50 <a class="help-ico" data-trigger="hover" rel="popover" data-original-title="Top 50" data-content="50 most popular pages">lnk</a></h5>
				<?php
				foreach ( $points_src as $key => $value ) {
					?>
					<div class="rating">
						<div class="progress progress-info">
							<a href="<?php echo $value->page_url ?>" target="_blank"
							   class="l-up"><?php echo HMTrackerFN::sec2hms( $value->points ) ?>
								| <?php echo $value->page_url; ?></p></a>

							<div class="bar"
							     style="width: <?php echo round( $value->points * 100 / $points_total ); ?>%"><a
									href="<?php echo $value->page_url ?>" target="_blank"
									class="l-down"><?php echo HMTrackerFN::sec2hms( $value->points ) ?>
									| <?php echo $value->page_url ?></p></a></div>
						</div>
					</div>
				<?php
				}
			} else {
				?>
				No data found.
			<?php } ?>
		</div>
	</div>
</div>
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo $this->PLUGIN_URL ?>js/jquery.flot.js"></script>
<script type="text/javascript" src="<?php echo $this->PLUGIN_URL ?>js/jquery.flot.pie.js"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/index.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function () {
		App.init(); // initlayout and core plugins
		Index.init();

		//popular pages
		<?php
		//manage data
		if(isset($_POST['manage']) && $_POST['manage'] == "tables"){
			
			switch($_POST['what']){
				case 'sessions':
					$wpdb->get_results("DELETE FROM $main_table WHERE `project` = '".$_GET["name"]."' AND  session_time >= '".strtotime($_POST['from'])."' AND session_time <= '".(strtotime($_POST['to'])+82800+3599)."'");
					break;
				case 'clicks':
					$wpdb->get_results("DELETE FROM $click_table WHERE `project` = '".$_GET["name"]."'  date >= '$_POST[from]' AND date <= '$_POST[to]'");
					break;
				case 'eye':
					$wpdb->get_results("DELETE FROM $mmove_table WHERE `project` = '".$_GET["name"]."'  date >= '$_POST[from]' AND date <= '$_POST[to]'");
					break;
				case 'scroll':
					$wpdb->get_results("DELETE FROM $scroll_table WHERE `project` = '".$_GET["name"]."'  date >= '$_POST[from]' AND date <= '$_POST[to]'");
					break;
			}
			
		}
		echo $points_total;
		?>

		var data = [
			<?php
					$total = 9;
					foreach ($points_src as $key => $value) {
						if($total < 0) {break;}
					?>
			{label: "<?php echo ((strlen($value->page_url) > 73) ? substr($value->page_url,0,70).'...' : $value->page_url)  ?>", data: <?php echo round($value->points*100/$points_total); ?>},
			<?php
				$total--;
			}
			?>
		];

		jQuery.plot(jQuery("#pie"), data,
			{
				series: {
					pie: {
						show: true,
						offset: {
							left: -300
						},
						radius: 0.8,
						label: {
							show: true,
							radius: 1,
							formatter: function (label, series) {
								return '<div style="font-size:8pt;text-align:center;padding:2px;">' + Math.round(series.percent) + '%</div>';
							},
							background: {opacity: 0.8}
						}
					}
				}, grid: {
				hoverable: true,
				clickable: true
			}
			});

		jQuery('.help-ico').popover({'placement': 'right'});

	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
