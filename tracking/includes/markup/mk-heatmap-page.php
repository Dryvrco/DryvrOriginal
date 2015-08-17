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

if ( is_agency() ) {
	$user          = current_user();
	$cur_status_id = detect_user_status( $user );
	$ui_enabled    = validate_user_status( $cur_status_id );
	if ( ! $ui_enabled ) {
		die( "Subscription status issue" );
	}

}
//registered user
if ( ! is_user_logged_in( $loggedin_user ) ) {
	die( "Only admin can access this section" );
}
//secure get vars
foreach ( $_GET as $key => $value ) {
	$_GET[ $key ] = HMTrackerFN::hmtracker_secure( $value );
}

$_GET['url'] = str_replace( "~", ".", $_GET['url'] );
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php echo $this->OPTIONS['brandname'] ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getFavIcon(); ?>"/>
	<?php
	$this->includeCSS();
	$this->includeJS();
	?>
	<link href="<?php echo $this->PLUGIN_URL ?>css/heatmaps.css" rel="stylesheet"/>
	<script type="text/javascript" src="<?php echo $this->PLUGIN_URL; ?>js/heatmap.js"></script>
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			var ajax_call = null;

			function process(result) {
				$("#loader").css("display", "none");
				var max_h = parseInt(result.max_h);
				var grid_step = parseInt(result.grid_step);

				$('.heat-holder').append('<div id="scroll_grid_container_wrapper" style="position: absolute !important; z-index:9999 !important; top: 0 !important; left: 0 !important; width:100%; height:' + max_h + 'px;" ></div>');
				$("#scroll_grid_container_wrapper").data();

//			if ($('#spy-iframe').contents().find("#wpadminbar").length > 0) {
//				$('#heatmapArea').css("top", "23px");
//			}

				var grid_levels_count = result.grid_count;

				for (var i = 0; i < grid_levels_count; i++) {
					$("#scroll_grid_container_wrapper").append('<div class="scroll_grid_container" style="position: absolute !important; z-index:9999 !important; top: ' + (i * result.grid_step) + 'px !important; opacity:0.2; left: 0 !important; width:100%; height:' + result.grid_step + 'px;" ></div>');
				}

				$(".scroll_grid_container").each(function () {
					$(this).css("background", result.colors[$(this).index()]);
					$(this).append('<span style="position: absolute !important; display: block !important; bottom: 3px !important; left: 3px !important; font: 12px sans-serif; color: #000 !important">' + result.percents[$(this).index()] + '%</span>');
				});
				$(".scroll_grid_container").hover(
					function () {
						$(this).css("opacity", "0.6");
						$(this).css("border-bottom", "2px #000 dashed");
						$(this).find("span").css("background-color", "#fff");
					},
					function () {
						$(this).css("opacity", "0.2");
						$(this).css("border-bottom", "none");
						$(this).find("span").css("background", "none");
					}
				);
				$("#loader").css("display", "none");
			}

			function get_view(grid) {

				if (typeof grid === "undefined") {
					var grid = false;
				}

				var data = {
					brandLogo: '<?php echo $this->getBrandLogo(); ?>',
					from: '<?php echo $_GET['from']; ?>',
					grid_step: $("#grd_step").val() ? $("#grd_step").val() : 50,
					home_url: '<?php echo home_url(); ?>',
					layout: '<?php echo $_GET['layout']; ?>',
					map: '<?php echo $_GET['map']; ?>',
					session: '<?php echo $loggedin_user[2]; ?>',
					to: '<?php echo $_GET['to']; ?>',
					url: '<?php echo $_GET['url']; ?>',
					variance: $("#variance").val() ? $("#variance").val() : 5,
					min_point_count: $("#min_point_count").val() ? $("#min_point_count").val() : 1,
					clicks_table:<?php echo json_encode($this->OPTIONS['dbtable_name_clicks']); ?>,
					mmove_table:<?php echo json_encode($this->OPTIONS['dbtable_name_mmove']); ?>,
					scroll_table:<?php echo json_encode($this->OPTIONS['dbtable_name_scroll']); ?>,
					package:<?php echo json_encode($this->OPTIONS['heatmap_package']); ?>
				}
				if (ajax_call) {
					ajax_call.abort();
				}
				ajax_call = $.ajax({
					type: 'POST',
					dataType: 'json',
					url: "includes/markup/mk-heatmap-ajax-view.php",
					data: data
				}).success(function (result) {
					ajax_call = null;
					if (result.view) {
						if (!grid) {
							$('body').html(result.view);
							$('#spy-iframe').load(function () {
								$("#loader-text").html("Generating Heatmap:");
								<?php if($_GET['map'] == 'scroll') { ?>
								process(result);
								<?php } else { ?>
								<?php switch ($_GET['layout']) {
									case 'left': ?>
								setTimeout(function () {
									$("html, body").scrollLeft(0);
									$('#spy-iframe').animate({opacity: 1}, 200);
								}, 500);
								<?php break;
							case 'center':  ?>
								setTimeout(function () {
									$("html, body").scrollLeft(($("body")[0].scrollWidth - $("body")[0].clientWidth) / 2);
									$('#spy-iframe').animate({opacity: 1}, 200);
								}, 500);
								<?php break;
							case 'right':  ?>
								setTimeout(function () {
									$("html, body").scrollLeft($("body")[0].scrollWidth);
									$('#spy-iframe').animate({opacity: 1}, 200);
								}, 500);
								<?php break;
								} ?>
								// heatmap configuration
								var config = {
									container: document.getElementById('heatmapArea'),
									gradient: {0.05: "rgb(0,0,255)", 0.35: "rgb(0,255,255)", 0.55: "rgb(0,255,0)", 0.75: "yellow", 1.0: "rgb(255,0,0)"}
								};

								//creates and initializes the heatmap
								var heatmap = h337.create(config);

								// let's get some data
								var data = {
									min: 0,
									max: result.max,
									data: result.data
								};

								heatmap.setData(data);

								$("#loader").css("display", "none");
								<?php } ?>
							});
						} else {
							$("#loader").css("display", "block");
							$("#loader-text").html("Generating Heatmap:");
							switch (grid) {
								case 1:
									$("#scroll_grid_container_wrapper").remove();
									process(result);
									break;
								case 2:
									$(".heatmap-canvas").remove();
									$("#ie_message span").html(result.count);
									if (result.data.length > 0) {
										// heatmap configuration
										var config = {
											container: document.getElementById('heatmapArea'),
											gradient: {0.05: "rgb(0,0,255)", 0.35: "rgb(0,255,255)", 0.55: "rgb(0,255,0)", 0.75: "yellow", 1.0: "rgb(255,0,0)"}
										};

										//creates and initializes the heatmap
										var heatmap = h337.create(config);

										// let's get some data
										var data = {
											min: 0,
											max: result.max,
											data: result.data
										};

										heatmap.setData(data);
									}
									break;
							}
							$("#loader").css("display", "none");
						}
					}
				}).error(function (jqxhr, settings, thrownError) {
					if (jqxhr.statusText === "abort") {
						return;
					}
					alert("ERROR (" + jqxhr.status + "): " + thrownError);
				});
			}

			<?php if($_GET['map'] == "scroll") { ?>
			$("#grd_step").live("change", function () {
				if ($(this).val() >= 50 && $(this).val() <= 500) {
					get_view(1);
				} else {
					alert("Please select a value in the range of 50 to 500.");
				}
			})
			<?php } else {?>
			try {
				if ($.browser.msie) {
					if (parseInt($.browser.version, 10) < 9) {
						$('#ie_message').html("Use IE9+ to see this heat map");
					}
				}
			} catch (e) {

			}
			$("#variance").live("change", function () {
				if ($(this).val() >= 0 && $(this).val() <= 20) {
					get_view(2);
				} else {
					alert("Please select a value in the range of 0 to 20.");
				}
			});

			$("#min_point_count").live("change", function () {
				if ($(this).val() >= 0 && $(this).val() <= 10) {
					get_view(2);
				} else {
					alert("Please select a value in the range of 1 to 10.");
				}
			});


			<?php } ?>
			get_view();
		});
	</script>
	<style type="text/css">
		body {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", sans-serif;
			background: #f5f5f5;
		}

		.spy-frame {
			display: block;
			width: 0;
			z-index: 1;
		}

		iframe {
			-moz-box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
			-webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
			box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
		}
	</style>
</head>
<body>
<div class="navbar navbar-fixed-top" style="z-index: 20">
	<div class="navbar-inner">
		<div class="container" style="margin-left: 10px">
			<a class="brand" href="javascript: window.close()" style="padding-top: 0; padding-bottom: 0">
				<img src="<?php echo $this->getBrandLogo(); ?>" alt="logo" style="max-height: 40px;" class="center"/>
			</a>

			<div id="loader" style="margin-top: 10px;">
				<span id="loader-text">Retrieving Data:</span>&nbsp;&nbsp;<img src="<?php echo home_url(); ?>images/loader.gif"/>
			</div>
		</div>
	</div>
</div>
</body>
</html>