<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}

global $wpdb, $loggedin_user;
if ( ! is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
}

$click_table  = T_PREFIX . $this->OPTIONS['dbtable_name_clicks'];
$mmove_table  = T_PREFIX . $this->OPTIONS['dbtable_name_mmove'];
$scroll_table = T_PREFIX . $this->OPTIONS['dbtable_name_scroll'];
if ( is_agency() ) {
	$user          = current_user();
	$cur_status_id = detect_user_status( $user );
	$ui_enabled    = validate_user_status( $cur_status_id );
	if ( ! $ui_enabled ) {
		die( "Subscription status issue" );
	}
	$click_table .= "_{$loggedin_user[2]}";
	$mmove_table .= "_{$loggedin_user[2]}";
	$scroll_table .= "_{$loggedin_user[2]}";
}

$_GET["name"] = rawurlencode( $_GET["name"] );

include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content">
	<div class="analytics-block">
		<h2><span>HEAT MAP GENERATOR</span> Heat Maps</h2>

		<div class="table-holder">
			<form action="<?php echo admin_url() ?>" method="GET" class="form-horizontal" id="heatmap_form"
			      target="_blank">
				<input name="url" value="" type="hidden"/>
				<input name="from" value="" type="hidden"/>
				<input name="to" value="" type="hidden"/>
				<input name="layout" value="" type="hidden"/>
				<input name="hmtrackerheatmap" value="" type="hidden"/>
				<input name="map" value="" type="hidden"/>
				<input name="uniq" value="" type="hidden"/>

				<div class="control-group">
					<label class="control-label">Heat Map Type</label>

					<div class="controls">
						<div class="btn-group all-special btn-heatmap" data-toggle="buttons-radio">
							<button type="button"
							        class="btn btn-success btn-mini active btn-primary btn-h-click"
							        data-value="clicks">
								Clicks
							</button>
							<button type="button" class="btn btn-primary btn-mini btn-h-move"
							        data-value="eyetracking">
								Eye-tracking
							</button>
							<button type="button" class="btn btn-primary btn-mini btn-h-scroll"
							        data-value="scroll">
								Scroll
							</button>
						</div>
						<a class="help-ico" data-trigger="hover" rel="popover"
						   data-original-title="Heat Map Type"
						   data-content="Choose heat map type to generate">lnk</a>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Available Heat Maps</label>

					<div class="controls">
						<select class="input-xlarge heat-urls" size="10" style="width: 600px !important;">
							<?php
							$urls      = $wpdb->get_results( "SELECT DISTINCT `page_url` FROM $click_table where `project` = '" . $_GET["name"] . "'" );
							$urlArray1 = array();
							foreach ( $urls as $key1 => $value1 ) {
								$urlArray1[] = $value1->page_url;
							}

							$urls      = $wpdb->get_results( "SELECT DISTINCT `page_url` FROM $mmove_table  where `project` = '" . $_GET["name"] . "'" );
							$urlArray2 = array();
							foreach ( $urls as $key2 => $value2 ) {
								$urlArray2[] = $value2->page_url;
							}

							$urls      = $wpdb->get_results( "SELECT DISTINCT `page_url` FROM $scroll_table  where `project` = '" . $_GET["name"] . "'" );
							$urlArray3 = array();
							foreach ( $urls as $key3 => $value3 ) {
								$urlArray3[] = $value3->page_url;
							}

							?>

							<optgroup label="By URL" id="click_urls">
								<?php
								foreach ( $urlArray1 as $key4 => $value4 ) {
									?>
									<option
										value="<?php echo str_replace( ".", "~", $value4 ) ?>"><?php echo $value4 ?></option>
								<?php
								}
								?>
							</optgroup>

							<optgroup label="By URL" id="move_urls">
								<?php
								foreach ( $urlArray2 as $key5 => $value5 ) {
									?>
									<option
										value="<?php echo str_replace( ".", "~", $value5 ) ?>"><?php echo $value5 ?></option>
								<?php
								}
								?>
							</optgroup>

							<optgroup label="By URL" id="scroll_urls">
								<?php
								foreach ( $urlArray3 as $key6 => $value6 ) {
									?>
									<option
										value="<?php echo str_replace( ".", "~", $value6 ) ?>"><?php echo $value6 ?></option>
								<?php
								}
								?>
							</optgroup>
						</select>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Data Source</label>

					<div class="controls">
						<div class="btn-group all-special btn-d" data-toggle="buttons-radio">
							<button type="button"
							        class="btn active btn-success btn-primary btn-d-day btn-mini"
							        data-value="2">
								Last Day
							</button>
							<button type="button" class="btn btn-primary btn-d-week btn-mini"
							        data-value="2">
								Last Week
							</button>
							<button type="button" class="btn btn-primary btn-d-month btn-mini"
							        data-value="2">
								Last Month
							</button>
							<button type="button" class="btn btn-primary btn-d-range btn-mini"
							        data-value="2">
								Date Range
							</button>
						</div>
						<a class="help-ico" data-trigger="hover" rel="popover"
						   data-original-title="Data Source"
						   data-content="For the better analyzing heat map you can choose date range">lnk</a>
					</div>
					<br/>

					<div class="controls date-range-buttons">
						<button disabled type="button"
						        class="btn btn-primary from-date-heatmap width-auto btn-mini" id="dp4"
						        data-date-format="yyyy-mm-dd" data-date="2012-02-20">
							<strong>From</strong> <span>2012-02-20</span>
						</button>
						<button disabled type="button"
						        class="btn btn-primary to-date-heatmap width-auto btn-mini" id="dp5"
						        data-date-format="yyyy-mm-dd" data-date="2012-02-23">
							<strong>To</strong> <span>2012-02-23</span>
						</button>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Page layout</label>

					<div class="controls">
						<div class="btn-group all-special btn-layout" data-toggle="buttons-radio">
							<button type="button" class="btn btn-primary btn-mini" data-value="left">
								Left Aligned
							</button>
							<button type="button" class="btn active btn-success btn-primary btn-mini"
							        data-value="center">
								Centered
							</button>
							<button type="button" class="btn btn-primary btn-mini" data-value="right">
								Right Aligned
							</button>
						</div>
						<a class="help-ico" data-trigger="hover" rel="popover"
						   data-original-title="Page layout"
						   data-content="IMPORTANT! Specify how a tracked page is aligned relative to the browser window size in order to get an accurate heat map">lnk</a>
					</div>
				</div>
				<div class="form-actions">
					<button type="button" class="btn btn-primary generate-heatmap width-auto"
					        data-loading-text="Checking data...">
						Generate Heat Map
					</button>
				</div>
			</form>

		</div>
	</div>
</div>
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo $this->PLUGIN_URL ?>js/bootstrap-datepicker.js"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/alerter.js" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/index.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function () {
		App.init(); // initlayout and core plugins
		Index.init();

		//all pages/special page buttons
		jQuery('.all-special button').click(function () {
			if (!jQuery(this).hasClass("active")) {
				jQuery(this).parent().find("button").removeClass("btn-success");
				jQuery(this).addClass("btn-success")

				if (jQuery(this).hasClass("btn-special")) {
					jQuery(".pagesposts").removeAttr("disabled");
				} else {
					jQuery(".pagesposts").attr("disabled", "");
				}
			}
		})

		//heatmap urls
		//datepickers
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
		var yyyy = today.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}
		if (mm < 10) {
			mm = '0' + mm
		}
		var _today = yyyy + '-' + mm + '-' + dd;
		var __today = yyyy + '-' + mm + '-' + dd;
		//day
		var dayago = new Date(today.getTime() - 1 * 24 * 60 * 60 * 1000);
		dd = dayago.getDate();
		mm = dayago.getMonth() + 1; //January is 0!
		yyyy = dayago.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}
		if (mm < 10) {
			mm = '0' + mm
		}
		var _dayago = yyyy + '-' + mm + '-' + dd;
		var __dayago = yyyy + '-' + mm + '-' + dd;
		//week
		var weekago = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
		dd = weekago.getDate();
		mm = weekago.getMonth() + 1; //January is 0!
		yyyy = weekago.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}
		if (mm < 10) {
			mm = '0' + mm
		}
		var _weekago = yyyy + '-' + mm + '-' + dd;
		var __weekago = yyyy + '-' + mm + '-' + dd;
		//month
		var monthago = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
		dd = monthago.getDate();
		mm = monthago.getMonth() + 1; //January is 0!
		yyyy = monthago.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}
		if (mm < 10) {
			mm = '0' + mm
		}
		var _monthago = yyyy + '-' + mm + '-' + dd;
		var __monthago = yyyy + '-' + mm + '-' + dd;

		var _from = _dayago, _to = _today;
		var __from = __dayago, __to = __today;

		jQuery('.from-date-heatmap').attr("data-date", _weekago).find("span").html(_weekago);
		jQuery('.to-date-heatmap').attr("data-date", _today).find("span").html(_today);

		jQuery('#dp4').datepicker()
			.on('changeDate', function (ev) {
				jQuery(this).find("span").html(jQuery(this).data('date'));
				_from = jQuery(this).data('date');
				jQuery('#dp4').datepicker('hide');
			});
		jQuery('#dp5').datepicker()
			.on('changeDate', function (ev) {
				jQuery(this).find("span").html(jQuery(this).data('date'));
				jQuery('#dp5').datepicker('hide');
				_to = jQuery(this).data('date');
			});

		jQuery(".btn-d-day").click(function () {
			_from = _dayago;
			_to = _today
		});
		jQuery(".btn-d-week").click(function () {
			_from = _weekago;
			_to = _today
		});
		jQuery(".btn-d-month").click(function () {
			_from = _monthago;
			_to = _today
		});

		var _map = "click";
		var _click_opts = jQuery('#click_urls').html();
		var _move_opts = jQuery('#move_urls').html();
		var _scroll_opts = jQuery('#scroll_urls').html();

		jQuery('#move_urls').remove();
		jQuery('#scroll_urls').remove();

		jQuery(".btn-h-click").click(function () {
			_map = "click";
			jQuery('.heat-urls').html("");
			jQuery('.heat-urls').append('<optgroup label="By URL">' + _click_opts + '</optgroup>')
		});
		jQuery(".btn-h-move").click(function () {
			_map = "mmove";
			jQuery('.heat-urls').html("");
			jQuery('.heat-urls').append('<optgroup label="By URL">' + _move_opts + '</optgroup>')
		});
		jQuery(".btn-h-scroll").click(function () {
			_map = "scroll";
			jQuery('.heat-urls').html("");
			jQuery('.heat-urls').append('<optgroup label="By URL">' + _scroll_opts + '</optgroup>')
		});


		jQuery(".btn-d button").click(function () {
			setTimeout(function () {
				if (jQuery(".btn-d-range").hasClass("active")) {
					jQuery(".date-range-buttons button").removeAttr("disabled");
				} else {
					jQuery(".date-range-buttons button").attr("disabled", "");
				}
			}, 100);
		})

//		var form_action = '<?php //echo admin_url() ?>//';
		//generate heatmap
		jQuery(".generate-heatmap").click(function () {
			//validate form
			if (jQuery('.heat-urls').find(":selected").val() == undefined) {
				jQuery("#heatmap_form").prepend(alerter("Please, choose at least one page url", 1));
				jQuery('.heat-urls').focus();
				return false;
			}

			var get = {}
			get.url = jQuery('.heat-urls').find(":selected").val();
//			var u = parse_url(get.url.replace("~", "."));
//			var a = parse_url(form_action.replace("~", "."));

			get.from = _from;
			get.to = _to;
			get.map = _map;
			get.layout = jQuery(".btn-layout").find(".active").attr("data-value");

//			jQuery("#heatmap_form").attr("action", u.scheme + "://" + a.host + a.path);
			jQuery("#heatmap_form input[name$='url']").val(get.url);
			jQuery("#heatmap_form input[name$='from']").val(get.from);
			jQuery("#heatmap_form input[name$='to']").val(get.to);
			jQuery("#heatmap_form input[name$='layout']").val(get.layout);
			jQuery("#heatmap_form input[name$='map']").val(get.map);
			jQuery("#heatmap_form input[name$='uniq']").val(Math.random());
			jQuery("#heatmap_form").submit();

		});
		jQuery('.help-ico').popover({'placement': 'right'});


	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
