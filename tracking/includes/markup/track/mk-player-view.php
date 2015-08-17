<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */

if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}

$table = T_PREFIX . $this->OPTIONS['dbtable_name'];
global $wpdb, $loggedin_user;
if ( is_agency() ) {
//check if user can see this page
	if ( ! is_user_logged_in( $loggedin_user ) ) {
		die( "Only admin can access this section" );
	}
	$table .= "_{$loggedin_user[2]}";
}
$option = $this->OPTIONS;
//$table = T_PREFIX . 'main_' . $_SESSION["login_user"][2];
//$table   = T_PREFIX . 'main_' . $loggedin_user[2];
$query   = "SELECT * FROM $table WHERE id = " . $_GET['session'];
$session = $wpdb->get_row( $query );
//extract viewed pages
$page_history = "";
$arr_data     = json_decode( $session->session_spydata );
if ( isset( $this->PROJECTS[ $session->project ]['settings']['opt_record_tz'] ) ) {
	date_default_timezone_set( $this->PROJECTS[ $session->project ]['settings']['opt_record_tz'] );
}
//build pages list

if ( ! empty( $arr_data ) ) {
	$u = parse_url( key( $arr_data[0] ) );
	$h = parse_url( siteURL( false ) );
	if ( $h['scheme'] != $u['scheme'] ) {
		header( "Location: {$u['scheme']}://{$h['host']}{$h['path']}?{$h['query']}" );
		die();
	}
}


$time = array();
foreach ( $arr_data as $key => $value ) {
	foreach ( $value as $kkey => $vvalue ) {
		foreach ( $vvalue as $kkkey => $vvvalue ) {
			foreach ( $vvvalue as $kkkkey => $vvvvalue ) {
				if ( ! isset( $time[ $key ] ) ) {
					$time[ $key ] = 0;
				}
				if ( $time[ $key ] < $vvvvalue[0] ) {
					$time[ $key ] = ceil( $vvvvalue[0] );
				}
			}
		}
		if ( $time[ $key ] == 0 ) {
			continue;
		}
		$str = explode( "/", $kkey );
		if ( $str[ count( $str ) - 1 ] != "" ) {
			$page_history .= '<option value="' . $key . '">' . $str[ count( $str ) - 1 ] . ' (' . HMTrackerFN::sec2hms( ( $time[ $key ] ) ) . ')</option>';
		} else {
			$page_history .= '<option value="' . $key . '">' . $str[ count( $str ) - 2 ] . ' (' . HMTrackerFN::sec2hms( ( $time[ $key ] ) ) . ')</option>';
		}
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php echo $this->OPTIONS['brandname'] ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getFavIcon(); ?>"/>
	<?php
	$this->includePlayerCSS();
	?>
	<script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>

	<script type="text/javascript">
		var pageurl = [];
		<?php
		//JS array to get URL by id
		$first_page = "";
		foreach ($arr_data as $key => $value) {
			foreach ($value as $kkey => $vvalue){ if($key == 0) {$first_page = $kkey;}?>
		pageurl[<?php echo $key; ?>] = "<?php echo $kkey; ?>";
		<?php }
		}
		?>
		//main JS object for recorded data
		var playdata = new Object();
		playdata.mouse_move = [];
		playdata.page_scroll = [];
		playdata.mouse_click = [];
		playdata.window_size = [];
		playdata.resonsetive = [];

		var heights = [];
		var $height = 0;
		<?php
			$idx = -1;
			$uri = "";
			foreach ($arr_data as $tracking => $tracking_data)  {
				foreach ($tracking_data as $page => $page_data) {
					foreach ($page_data as $action => $action_data) {
						$uri = $page;
						if($action == "mouse_move") {
							?>
		if (typeof playdata.mouse_move[<?php echo $tracking; ?>] === "undefined") {
			playdata.mouse_move[<?php echo $tracking; ?>] = [];
		}
		<?php
		foreach ($action_data as $event => $event_data) { ?>
		playdata.mouse_move[<?php echo $tracking; ?>].push([<?php echo $event_data[0].",".$event_data[1].",".$event_data[2]; ?>]);
		<?php }
		}
		if($action == "page_scroll") {
		?>
		if (typeof playdata.page_scroll[<?php echo $tracking; ?>] === "undefined") {
			playdata.page_scroll[<?php echo $tracking; ?>] = [];
		}
		<?php
		foreach ($action_data as $event => $event_data) { ?>
		playdata.page_scroll[<?php echo $tracking; ?>].push([<?php echo $event_data[0].",".$event_data[1].",".$event_data[2]; ?>]);
		<?php	}
		}
		if($action == "mouse_click") {
		?>
		if (typeof playdata.mouse_click[<?php echo $tracking; ?>] === "undefined") {
			playdata.mouse_click[<?php echo $tracking; ?>] = [];
		}
		<?php
		foreach ($action_data as $event => $event_data) { ?>
		playdata.mouse_click[<?php echo $tracking; ?>].push([<?php echo $event_data[0].",".$event_data[1].",".$event_data[2].",".$event_data[3].",".$event_data[4].",".$event_data[5]; ?>]);
		<?php	}
		}
		if($action == "window_size") {
		?>
		if (typeof playdata.window_size[<?php echo $tracking; ?>] === "undefined") {
			playdata.window_size[<?php echo $tracking; ?>] = [];
		}
		<?php
		foreach ($action_data as $event => $event_data) {
			if(isset($event_data[3]) && $height < $event_data[3]) {
				$height = $event_data[3];
			}
		?>
		playdata.window_size[<?php echo $tracking; ?>].push([<?php echo $event_data[0].",".$event_data[1].",".$event_data[2]; ?>]);
		<?php } ?>
		if (typeof heights[<?php echo $tracking; ?>] === "undefined") {
			heights[<?php echo $tracking; ?>] = 0;
		}
		<?php } ?>
		heights[<?php echo $tracking; ?>] = <?php echo (int)($height == 0 ? 99999 : $height );?>;
		<?php if($action == "responsetive") { ?>
		if (typeof playdata.resonsetive[<?php echo $tracking; ?>] === "undefined") {
			playdata.resonsetive[<?php echo $tracking; ?>] = [];
		}
		<?php
		foreach ($action_data as $event => $event_data) {
			$v4 = "{";
			foreach ($event_data[3] as $k => $v) {
				$v4 .= "'".$k."':'".$v."',";
			} $v4 .= "}";

			$v5 = "{";
			foreach ($event_data[4] as $k5 => $v6) {
				$v5 .= "'".$k5."':'".$v6."',";
			} $v5 .= "}";

			?>
		playdata.resonsetive[<?php echo $tracking; ?>].push(['<?php echo $event_data[0]."',".$event_data[1].",".$event_data[2].",".$v4.",".$v5;?>]);
			<?php	}
		}
		}
		}
		}
		?>

			//convert seconds to HH:MM:SS format
			function secondsToTime(secs) {
				var hours = Math.floor(secs / (60 * 60));
				var divisor_for_minutes = secs % (60 * 60);
				var minutes = Math.floor(divisor_for_minutes / 60);
				var divisor_for_seconds = divisor_for_minutes % 60;
				var seconds = Math.ceil(divisor_for_seconds);
				if (hours < 10) hours = "0" + hours;
				if (minutes < 10) minutes = "0" + minutes;
				if (seconds < 10) seconds = "0" + seconds;
				return hours + ":" + minutes + ":" + seconds;
			}
			//initialize player
			function mainPlay() {
				if (typeof noinit === "undefined") {
					var noinit = false;
				}
				interval_init = false;
				//initialize undefined arrays
				if (playdata.mouse_click[page] == undefined) {
					playdata.mouse_click[page] = [0];
					playdata.mouse_click[page][0] = [0]
				}
				if (playdata.page_scroll[page] == undefined) {
					playdata.page_scroll[page] = [0];
					playdata.page_scroll[page][0] = [0]
				}
				if (playdata.mouse_move[page] == undefined) {
					playdata.mouse_move[page] = [0];
					playdata.mouse_move[page][0] = [0]
				}
				if (playdata.window_size[page] == undefined) {
					playdata.window_size[page] = [0];
					playdata.window_size[page][0] = [0]
				}
				//find and set max time

				max_time = 0;

				jQuery.each(playdata.window_size[page], function (key, value) {
					if (max_time < value[0]) max_time = value[0];
				});
				jQuery.each(playdata.mouse_click[page], function (key, value) {
					if (max_time < value[0]) max_time = value[0];
				});
				jQuery.each(playdata.page_scroll[page], function (key, value) {
					if (max_time < value[0]) max_time = value[0];
				});
				jQuery.each(playdata.mouse_move[page], function (key, value) {
					if (max_time < value[0]) max_time = value[0];
				});

				//show max time
				jQuery("#seqtime").text(secondsToTime((max_time * 10 - time) / 10));
				//set iframe defaults
				jQuery(".spy-frame").css("width", playdata.window_size[page][0][2] + "px");
				if (playdata.window_size[page][0][2] != undefined)
					jQuery(".spy-frame").css("margin-left", "-" + (playdata.window_size[page][0][2] / 2) + "px");
				else
					jQuery(".spy-frame").css("margin-left", "-" + (jQuery(".spy-frame").width() / 2) + "px");
				jQuery(".spy-frame").css("height", playdata.window_size[page][0][1] + "px");
				jQuery("#winsize").text(playdata.window_size[page][0][2] + " x " + playdata.window_size[page][0][1]);
				//remove white lines from the progressbar if exist
				jQuery(".points").remove();
				//convert recorded data to separate objects
				//and add white lines to the progress bar
				page_scroll = [];
				jQuery(".progress").append('<div class="points" style=" position: relative; z-index: 10;"></div>');
				for (var i = 0; i < playdata.page_scroll[page].length; i++) {
					page_scroll[playdata.page_scroll[page][i][0] * 10] = [playdata.page_scroll[page][i][1], playdata.page_scroll[page][i][2]];
					if ((playdata.page_scroll[page][i][0] * 10) != 0) {
						var obj = jQuery(".probress div.points").append('<img class="points" id="pointps_' + i + '" src="<?php echo $this -> PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 0px;" />');
						jQuery('#pointps_' + i).css("left", (jQuery(".probress div").parent().width() / (max_time * 10)) * (playdata.page_scroll[page][i][0] * 10) + "px");
					}
				}
				mouse_move = [];
				for (var i = 0; i < playdata.mouse_move[page].length; i++) {
					mouse_move[playdata.mouse_move[page][i][0] * 10] = [playdata.mouse_move[page][i][1], playdata.mouse_move[page][i][2]];
					if ((playdata.mouse_move[page][i][0] * 10) != 0) {
						var obj = jQuery(".probress div.points").append('<img class="points" id="pointmm_' + i + '" src="<?php echo $this -> PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 0px;" />');
						jQuery('#pointmm_' + i).css("left", (jQuery(".probress div").parent().width() / (max_time * 10)) * (playdata.mouse_move[page][i][0] * 10) + "px");
					}
				}
				mouse_click = [];
				for (var i = 0; i < playdata.mouse_click[page].length; i++) {
					mouse_click[playdata.mouse_click[page][i][0] * 10] = [playdata.mouse_click[page][i][1], playdata.mouse_click[page][i][2], playdata.mouse_click[page][i][3], playdata.mouse_click[page][i][4], playdata.mouse_click[page][i][5]];
					var color = (playdata.mouse_click[page][i][1] == 1) ? "red" : (playdata.mouse_click[page][i][1] == 3) ? "blue" : "";
					if ((playdata.mouse_click[page][i][0] * 10) != 0) {
						var obj = jQuery(".probress div.points").append('<img class="points" id="pointmc_' + i + '" src="<?php echo $this -> PLUGIN_URL; ?>/images/point' + color + '.gif" style="position: absolute; top: 0px;" />');
						jQuery('#pointmc_' + i).css("left", (jQuery(".probress div").parent().width() / (max_time * 10)) * (playdata.mouse_click[page][i][0] * 10) + "px");
					}
				}
				//<script>
				window_size = [];
				for (var i = 0; i < playdata.window_size[page].length; i++) {
					window_size[playdata.window_size[page][i][0] * 10] = [playdata.window_size[page][i][1], playdata.window_size[page][i][2], playdata.window_size[page][i][3]];
					if ((playdata.window_size[page][i][0] * 10) != 0) {
						var obj = jQuery(".probress div.points").append('<img class="points" id="pointws_' + i + '" src="<?php echo $this -> PLUGIN_URL; ?>/images/point.gif" style="position: absolute; top: 0px;" />');
						jQuery('#pointws_' + i).css("left", (jQuery(".probress div").parent().width() / (max_time * 10)) * (playdata.window_size[page][i][0] * 10) + "px");
					}
				}

				resonsetive = [];
				if (playdata.resonsetive[page] != undefined)
					for (var i = 0; i < playdata.resonsetive[page].length; i++) {
						resonsetive[playdata.resonsetive[page][i][1] * 10] = [playdata.resonsetive[page][i][0], playdata.resonsetive[page][i][4]];
						resonsetive[playdata.resonsetive[page][i][2] * 10] = [playdata.resonsetive[page][i][0], playdata.resonsetive[page][i][3]]
					}
//				console.log("PAGE SCROLL: ", page_scroll);
//				console.log("MOUSE MOUVE: ", mouse_move);
//				console.log("MOUSE CLICK: ", mouse_click);
//				console.log("WINDOW SIZE: ", window_size);
//				console.log("RESONSETIVE: ", resonsetive);
			}
		var interval = 100,
			time = 0,
			max_time = 0,
			page = 0,
			speed = 1,
			totalpages = pageurl.length - 1,
			playflag = false,
			pause = false,
			page_scroll = [],
			mouse_move = [],
			mouse_click = [],
			resonsetive = [],
			window_size = [],
			interval_id = null,
			interval_init = false;


		window.onresize = function () {
			jQuery(".spy-frame").css("top", jQuery(".container").height() + "px");
		}
		jQuery(document).ready(function () {

			setTimeout(function () {
				jQuery(".spy-frame").css("top", jQuery(".container").height() + "px");
			}, 500);

			//set pages list event
			jQuery("#play_page").change(function () {
				jQuery(".btn-on").removeClass("btn-success").removeClass("active");
				jQuery(".btn-warn").removeClass("btn-warning").removeClass("active");
				jQuery(".spy-cap").animate({opacity: 1});
				playflag = false;
				time = 0;
				jQuery('.progress .bar').css("width", (jQuery('.progress .bar').parent().width() / (max_time * 10) * time) + "px");
				page = jQuery(this).val();
				var height = heights[page];

//				if (height < 9999) {
//					height = 9999;
//				}
				jQuery("#spy-iframe-lvl2").css("height", height + "px");
				jQuery("#spy-iframe-lvl2").attr("src", pageurl[page]);
				interval_id = setInterval(function () {
					if (jQuery('#spy-iframe').width() > 0) {
						mainPlay();
						clearInterval(interval_id);
					}
				}, 300)
			});
			//set speed change event
			jQuery("#play_speed").change(function () {
				speed = jQuery(this).val();
			});


			jQuery("#spy-iframe-lvl2").attr("src", '<?php echo $first_page; ?>');
			var height = heights[page];
			jQuery("#spy-iframe-lvl2").css("height", height + "px");
			jQuery("#spy-iframe-lvl2").css("width", "100%");

			jQuery(".spy-frame").css("margin-left", "-" + (jQuery(".spy-frame").width() / 2) + "px");
			var start = 0;
			//prepare player
			interval_id = setInterval(function () {
				if (jQuery("body").width() > 0) {
					mainPlay();
					clearInterval(interval_id);
				}
			}, 300)

			//player controll
			jQuery('.onoff button').click(function () {
				if (!jQuery(this).hasClass("active")) {
					if (jQuery(this).hasClass("btn-on")) {
						jQuery(this).addClass("btn-success")
						jQuery(this).parent().find(".btn-off").removeClass("btn-danger");
						jQuery(this).parent().find(".btn-warn").removeClass("btn-warning");

						/*functionality*/

						playflag = true;
						jQuery(".spy-cap").animate({opacity: 0});
						jQuery(this).css("background-position", "50% -34px");
						jQuery(".spy-frame").css("width", playdata.window_size[page][0][2] + "px");
						jQuery(".spy-frame").css("margin-left", "-" + (playdata.window_size[page][0][2] / 2) + "px");
						jQuery(".spy-frame").css("height", playdata.window_size[page][0][1] + "px");
						jQuery("#winsize").text(playdata.window_size[page][0][2] + " x " + playdata.window_size[page][0][1]);
						if (!pause)
							jQuery("html, body").animate({
								scrollTop: 0,
								scrollLeft: 0
							}, 100);
						pause = false;


					}
					if (jQuery(this).hasClass("btn-off")) {
						jQuery(this).addClass("btn-danger")
						jQuery(this).parent().find(".btn-on").removeClass("btn-success");
						jQuery(this).parent().find(".btn-warn").removeClass("btn-warning");

						/*functionality*/
						setTimeout(function () {
							jQuery(".btn-off").removeClass("btn-danger").removeClass("active")
						}, 200);
						jQuery(".spy-cap").animate({opacity: 1});
						jQuery(this).css("background-position", "50% -2px");
						playflag = false;
						time = 0;
						jQuery('.progress .bar').css("width", (jQuery('.progress .bar').parent().width() / (max_time * 10) * time) + "px");

						pause = false;
						/*functionality*/

					}
					if (jQuery(this).hasClass("btn-warn")) {
						jQuery(this).addClass("btn-warning")
						jQuery(this).parent().find(".btn-on").removeClass("btn-success");
						jQuery(this).parent().find(".btn-off").removeClass("btn-danger");


						/*functionality*/
						playflag = false;
						pause = true;
						/*functionality*/
					}
				}
			})


			//progress bar click event
			jQuery(".probress div").click(function (e) {
				var parentOffset = jQuery(this).offset();
				var relX = e.pageX - parentOffset.left;
				if (playflag)
					time = Math.round((max_time * 10) / jQuery(this).width() * relX);
			});


			//main timer to play user actions
			var speedCounter = 1;
			setInterval(function () {
				//stop when finish
				if (playflag) {
					jQuery('.progress .bar').css("width", (jQuery('.progress .bar').parent().width() / (max_time * 10) * time) + "px");
					jQuery("#seqtime").text(secondsToTime((max_time * 10 - time) / 10));
					//play scroll
					if (page_scroll[time + 1] != undefined) {
						jQuery("#spy-iframe").animate({
							scrollTop: page_scroll[time + 1][0],
							scrollLeft: page_scroll[time + 1][1]
						}, 100 * parseInt(speed));
					}
					//mouse move
					if (mouse_move[time + 1] != undefined) {
						jQuery("#spy-mouse").animate({
							left: mouse_move[time + 1][0] - 22,
							top: mouse_move[time + 1][1] - 22
						}, 100);
					}
					//mouse click
					if (mouse_click[time + 1] != undefined) {
						jQuery("#spy-iframe").animate({
							scrollTop: mouse_click[time + 1][3],
							scrollLeft: mouse_click[time + 1][4]
						}, 100 * parseInt(speed));
						jQuery("#spy-mouse").animate({
							left: mouse_click[time + 1][1] - 22,
							top: mouse_click[time + 1][2] - 22
						}, 100 * parseInt(speed));

						var mouse = (mouse_click[time + 1][0] == "1") ? "left click" : (mouse_click[time + 1][0] == "3") ? "right click" : "unknown";

						jQuery("#spy-mouse-click").text(mouse);
						jQuery("#spy-mouse-click").css("left", mouse_click[time + 1][1] + "px");
						jQuery("#spy-mouse-click").css("top", mouse_click[time + 1][2] + "px");
						setTimeout(function () {
							jQuery("#spy-mouse-click").show(100);
						}, 100 * parseInt(speed))
						setTimeout(function () {
							jQuery("#spy-mouse-click").hide(100)
						}, 2000 + (100 * parseInt(speed)));
					}
					//window size
					if (window_size[time + 1] != undefined) {
						jQuery(".spy-frame").css("width", window_size[time + 1][1] + "px");
						jQuery(".spy-frame").css("margin-left", "-" + (window_size[time + 1][1] / 2) + "px");
						jQuery(".spy-frame").css("height", window_size[time + 1][0] + "px");
						jQuery("#winsize").text(window_size[time + 1][1] + " x " + window_size[time + 1][0]);
					}

					if (max_time * 10 == time) {
						if (page < totalpages) {
							time = 0;
							playflag = false;
							setTimeout(function () {
								jQuery(".spy-cap").animate({opacity: 1});
							}, 100 * parseInt(speed));
							setTimeout(function () {
								page++;
								var height = heights[page];
//								if (height < 9999) {
//									height = 9999;
//								}
								jQuery("#spy-iframe-lvl2").css("height", height + "px");
								jQuery("#spy-iframe-lvl2").attr("src", pageurl[page]);
								var myinterval_id = setInterval(function () {
									if (!interval_init) {
										jQuery("#spy-iframe-lvl2").one("load", null, function () {
											jQuery(".spy-cap").animate({opacity: 0});
											time = 0;
											jQuery("#play_page").val(page);
											mainPlay(true);
											interval_init = false;
											playflag = true;
											clearInterval(myinterval_id);
										});
									}
									jQuery('.progress .bar').css("width", (jQuery('.progress .bar').parent().width() / (max_time * 10) * time) + "px");
									interval_init = true;
								}, 300)
							}, 1000 + 100 * parseInt(speed))
						} else {
							playflag = false;
							time = 0;
							setTimeout(function () {
								page = 0;
								jQuery("#play_page").val(page);
								var height = heights[page];
//								if (height < 9999) {
//									height = 9999;
//								}
								jQuery("#spy-iframe-lvl2").css("height", height + "px");
								jQuery("#spy-iframe-lvl2").attr("src", pageurl[page]);
								if (!interval_init)
									interval_id = setInterval(function () {
										jQuery("#spy-iframe-lvl2").one("load", function () {
											time = 0;
											mainPlay(true);
											clearInterval(interval_id);
										});
										jQuery('.progress .bar').css("width", (jQuery('.progress .bar').parent().width() / (max_time * 10) * time) + "px");
										interval_init = true;
									}, 300)

								jQuery(".spy-cap").animate({opacity: 1});
								jQuery(".btn-on").removeClass("btn-success").removeClass("active");
								jQuery(".btn-warn").removeClass("btn-warning").removeClass("active");


							}, 1000 + 100 * parseInt(speed));
						}
					} else if (speed > speedCounter) {
						speedCounter++;
					} else {
						time += 1;
						speedCounter = 1;
					}
				}
			}, interval);
		});
	</script>
</head>
<body>
<div class="navbar navbar-fixed-top ">
	<div class="navbar-inner ">
		<div class="container" style="margin-left: 10px; width: 100%;">
			<a class="brand" href="javascript: window.close()" style="padding-top: 0; padding-bottom: 0"><img
					src="<?php echo $this->getBrandLogo(); ?>" alt="logo" style="max-height: 40px;"
					class="center"/></a>
			<ul class="nav">
				<li style="margin: 10px 0 0;">
						<span><?php
							//split user id
							$usrData = explode( "~", $session->user_id );
							//geoid
							$objGeoIP = new hmtracker_GeoIP();
							$objGeoIP->search_ip( $usrData[0] );
							$country = "not found";
							if ( $objGeoIP->found() ) {
								$fclass = "flag-" . $objGeoIP->getCountryCode();
							}

							?><i class="<?php echo $fclass ?>"></i> <?php echo $usrData[1]; ?></span>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 8px 0 0;">
							<span><select id="play_page" style=" height: 23px; margin: 0; padding: 0;">
									<?php echo $page_history; ?>
								</select></span>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 8px 0 0;">
							<span><select style="width: 60px; height: 23px; margin: 0; padding: 0;" id="play_speed">
									<option value="1">X 1</option>
									<option value="2">X0.5</option>
									<option value="10">X0.1</option>
								</select></span>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 1px 0 0;">
					<div class="btn-group onoff opt_record_status" data-toggle="buttons-radio">
						<button type="button" class="btn btn-small btn-on" data-value="1">
							<img src="<?php echo $this->PLUGIN_URL ?>images/player-btn.png"/>
						</button>
						<button type="button" class="btn btn-small btn-warn" data-value="0">
							<img src="<?php echo $this->PLUGIN_URL ?>images/pause-btn.png"/>
						</button>
						<button type="button" class="btn btn-small btn-off" data-value="0">
							<img src="<?php echo $this->PLUGIN_URL ?>images/stop-btn.png"/>
						</button>
					</div>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 9px 0 0; width: 420px" class="probress">
					<div class="progress progress-striped active" style="margin: 0;">
						<div class="bar" style="width: 0px; position: relative; z-index: 20;"></div>
					</div>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 10px 0 0;">
					<span id="seqtime"></span>
				</li>
				<li class="divider-vertical"></li>
				<li style="margin: 10px 0 0;">
					<span id="winsize"></span>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="spy-frame spy-cap" style="z-index: 2"></div>
<div id="spy-iframe" class="spy-frame" style="overflow: scroll;">
	<iframe style="position: relative; z-index: 0;" id="spy-iframe-lvl2" scrolling="no" frameborder="0" noresize="noresize"></iframe>
	<div id="spy-mouse"
	     style="padding: 0px ! important; margin: 0px ! important; position: absolute ! important; width: 56px ! important; height: 56px ! important; background: url('<?php echo admin_url(); ?>images/spy-cursor.png') repeat scroll 0% 0% transparent; z-index: 99998 ! important; left: 1305px; top: -20px;"></div>
	<div id="spy-mouse-click"
	     style="padding: 0px; margin: 0px; position: absolute ! important; width: 76px; height: 22px; background: none repeat scroll 0% 0% rgb(0, 0, 0); z-index: 99999 ! important; color: rgb(255, 255, 255) ! important; border: 1px solid rgb(255, 255, 255) ! important; font: 16px sans-serif ! important; opacity: 0.6; text-align: center ! important; display: none; left: 571px; top: 420px;">
		left click
	</div>
</div>
</body>
</html>