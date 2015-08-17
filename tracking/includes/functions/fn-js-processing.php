<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker
 * http://HeatMapTracker.com
 */
if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}
//detect user info
$broosArr = HMTrackerFN::browser_detection( "full" );
$broos    = HMTrackerFN::browser_detection( "os" ) . " " . HMTrackerFN::browser_detection( "os_number" );

if ( HMTrackerFN::browser_detection( "browser" ) != "ie" ) {
	$broarr = HMTrackerFN::browser_detection( HMTrackerFN::browser_detection( "browser" ) . "_version" );
	$broos .= "; " . $broarr[0] . " " . $broarr[1];
} else {
	$broos .= "; ie " . HMTrackerFN::browser_detection( "number" );
}

//get user real IP
$uip = HMTrackerFN::getRealIp();

$reguser = "guest";

//send valid file type
header( "Content-type: application/javascript" );

//secure check $_GET variables
$_GET['hmtrackerjs'] = str_replace( "~", "%20", $_GET["hmtrackerjs"] );
$_GET                = array_map( array( 'HMTrackerFN', 'hmtracker_secure' ), $_GET );

if ( is_agency() ) {
//fetch user
	$user = get_user_by( 'user_key', HMTrackerFN::hmtracker_secure( $_GET['uid'] ) );

//check user
	if ( empty( $user ) ) {
		die( '//invalid user uid' );
	}
	$status_code = detect_user_status( $user );
	if ( ! validate_user_status( $status_code ) ) {
		die( '//' . user_status_name( $status_code ) );
	}
//fetch project settings and check if we can track domain
	$general_opts       = array( $this->PROJECTS_NAME . $user->user_key, $this->USER_DOMAINS_NAME . $user->user_key );
	$opts               = get_options( $general_opts );
	$this->PROJECTS     = $opts[ $this->PROJECTS_NAME . $user->user_key ];
	$this->USER_DOMAINS = $opts[ $this->USER_DOMAINS_NAME . $user->user_key ];
	$domains            = &$this->USER_DOMAINS['opt_tracking_domains'];

	$domain = parse_url( str_replace( "~", ".", $_GET['purl'] ) );
	$domain = $domain['host'];
	if ( ! in_array( $domain, $domains ) && $status_code != 6 && $status_code != 8 ) {
		//insert a free slot
		if ( ! empty( $this->USER_DOMAINS['opt_tracking_autofill'] ) &&
		     $this->USER_DOMAINS['opt_max_tracking_domains'] > count( $domains )
		) {
			$domains[] = $domain;
			update_option( $this->USER_DOMAINS_NAME . $user->user_key, $this->USER_DOMAINS );
		} else {
			//think how to report overflow max count issue
			die( '//slot overflow' );
		}
	}
}

$option = $this->PROJECTS[ $_GET['hmtrackerjs'] ]['settings'];
if ( ! $option ) {
	$option = $this->PROJECTS[ rawurlencode( $_GET['hmtrackerjs'] ) ]['settings'];
}
//check page we want to record
// print_r($_GET);
// echo "<hr />";
// echo rawurlencode($_GET['hmtrackerjs']);
// echo "<hr />";
// var_dump($option);
// var_dump($this->PROJECTS);
if ( ! $option['opt_record_status'] ) {
	die( '//recording disabled' );
}
if ( in_array( $uip, $option['opt_black_ips'] ) ) {
	die( '//IP is blocked' );
}
if ( ( $option["opt_record_all"] == "false" && ! ( in_array( $_SERVER['HTTP_REFERER'], $option['opt_record_special'] ) ) ) ) {
	die( '//Referrer mismatch' );
}
?>
/*
<script>*/
	hmtracker = "initialised";
	window.onerror = function () {
		return true;
	}

	var JSONP = function (global) {
		function JSONP(uri, callback) {
			function JSONPResponse() {
				try {
					delete global[src]
				} catch (e) {
					// kinda forgot < IE9 existed
					// thanks @jdalton for the catch
					global[src] = null
				}
				documentElement.removeChild(script);
				callback.apply(this, arguments);
			}

			var
				src = prefix + id++,
				script = document.createElement("script")
				;
			global[src] = JSONPResponse;
			documentElement.insertBefore(
				script,
				documentElement.lastChild
			).src = uri + "=" + src;
		}

		var
			id = 0,
			prefix = "__JSONP__",
			document = global.document,
			documentElement = document.documentElement
			;
		return JSONP;
	}(this);

	JSONstringify = function (obj) {
		var t = typeof (obj);
		if (t != "object" || obj === null) {
			if (t == "string") obj = '"' + obj + '"';
			return String(obj);
		}
		else {
			var n, v, json = [], arr = (obj && obj.constructor == Array);
			for (n in obj) {
				v = obj[n];
				t = typeof(v);
				if (t == "string") v = '"' + v + '"';
				else if (t == "object" && v !== null) v = JSONstringify(v);
				json.push((arr ? "" : '"' + n + '":') + String(v));
			}
			return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
		}
	};

	JSONparse = function (str) {
		if (str === "") str = '""';
		eval("var p=" + str + ";");
		return p;
	};

	//	function getUrlVars() {
	//		var vars = {};
	//		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
	//			vars[key] = value;
	//		});
	//		return vars;
	//	}
	//
	//	function getByteSize(s) {
	//		return encodeURIComponent('<q></q>' + s).length;
	//	}

	function setHMTrackerData(e, t, n) {
		localStorage.setItem(e, hmtracker_serialize(t));
	}

	function getHMTrackerData(e) {
		return localStorage[e];
	}

	var hmtracker_cookie_name = "hmtracker";
	var END_OF_INPUT = -1;
	var base64Chars = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/');
	var reverseBase64Chars = new Array();
	for (var i = 0; i < base64Chars.length; i++) {
		reverseBase64Chars[base64Chars[i]] = i;
	}
	var base64Str;
	var base64Count;
	var isActive = true;
	var latest_update;
	var hmt_bodyHeight;
	var hmt_htmlHeight;
	var hmt_pageheight = getPageHeight();
	var hmt_windowSize = [0.8, window.innerHeight, window.innerWidth, hmt_pageheight];


	function setBase64Str(str) {
		base64Str = str;
		base64Count = 0;
	}

	function readBase64() {
		if (!base64Str) return END_OF_INPUT;
		if (base64Count >= base64Str.length) return END_OF_INPUT;
		var c = base64Str.charCodeAt(base64Count) & 0xff;
		base64Count++;
		return c;
	}

	function encodeBase64(str) {
		setBase64Str(str);
		var result = '';
		var inBuffer = new Array(3);
		var lineCount = 0;
		var done = false;
		while (!done && (inBuffer[0] = readBase64()) != END_OF_INPUT) {
			inBuffer[1] = readBase64();
			inBuffer[2] = readBase64();
			result += (base64Chars[inBuffer[0] >> 2]);
			if (inBuffer[1] != END_OF_INPUT) {
				result += (base64Chars [(( inBuffer[0] << 4 ) & 0x30) | (inBuffer[1] >> 4)]);
				if (inBuffer[2] != END_OF_INPUT) {
					result += (base64Chars [((inBuffer[1] << 2) & 0x3c) | (inBuffer[2] >> 6)]);
					result += (base64Chars [inBuffer[2] & 0x3F]);
				} else {
					result += (base64Chars [((inBuffer[1] << 2) & 0x3c)]);
					result += ('=');
					done = true;
				}
			} else {
				result += (base64Chars [(( inBuffer[0] << 4 ) & 0x30)]);
				result += ('=');
				result += ('=');
				done = true;
			}
			lineCount += 4;
			if (lineCount >= 76) {
				result += ('\n');
				lineCount = 0;
			}
		}
		return result;
	}

	function hmtracker_serialize(arr) {
		var _srz = JSON.stringify(arr);
		return _srz;
	}
	function hmtracker_unserialize(e) {
		var unserialized = JSON.parse(e);
		return unserialized;
	}

	function isiOS() {
		return (
		(navigator.platform.indexOf("iPhone") != -1) ||
		(navigator.platform.indexOf("iPod") != -1) ||
		(navigator.platform.indexOf("iPad") != -1)
		);
	}

	function getBuff(sess, location, name) {
		var src_buff = getHMTrackerData(hmtracker_cookie_name + "_buff");
		if (src_buff != null) {
			buff = hmtracker_unserialize(src_buff);
		}
		else buff = {};

		if (buff[sess] == undefined)
			buff[sess] = {};

		if (buff[sess][location] == undefined)
			buff[sess][location] = {};

		if (buff[sess][location][name] == undefined)
			buff[sess][location][name] = [];

		return buff;
	}

	function hmt_tracking_init() {

		window.onfocus = function () {
			isActive = true;
		}
		window.onblur = function () {
			isActive = false;
		}
		if (top !== self) return false;
		var time = 0;
		var myVar = "<?php echo $uip."~".$broos."~".$reguser; ?>";
		var jsonp_url = "<?php echo home_url(); ?>?hmtrackerdata=<?php echo $_GET['hmtrackerjs'] ?>";
		<?php if( is_agency() ) { ?>
		jsonp_url += "&uid=<?php echo $_GET['uid'] ?>";
		<?php } ?>
		jsonp_url += "&user=" + myVar + "&data=";
//		console.log("SESSION DATA:", session_data);
		var session_data = getHMTrackerData(hmtracker_cookie_name + "_session");
		if (session_data == undefined) {
			var session = create_new_session();
		} else {
			var session = update_current_session(hmtracker_unserialize(session_data));
		}
		var hmtracker_lastmousex = 0, hmtracker_lastmousey = 0, hmtracker_lastscrollv = 0, hmtracker_lastscrollh = 0, lastwinh = 0, lastwinw = 0;
		var hmtracker_prevmousex = 0, hmtracker_prevmousey = 0, prevscrollv = 0, prevscrollh = 0, prevwinh = 0, prevwinw = 0;
		var mouse_move, mouse_click, page_scroll;
		var send_interval =
		<?php print $option['opt_record_interval']; ?>*
		1000;
		var interval = 100;
		var sending = false;
		var location = document.location.href;

		function sendData() {
//			console.log("Check Send data");
			now = NOW();
			sending = true;

			var send_buff = getHMTrackerData(hmtracker_cookie_name + "_buff") || "";
			var session_buff = getHMTrackerData(hmtracker_cookie_name + "_session");
			var session_data = hmtracker_unserialize(session_buff);

			var send_obj = hmtracker_unserialize(send_buff);
			for (var key in send_obj) {
				for (var kkey in send_obj[key]) {
					if (typeof send_obj[key][kkey]["referrer"]) {
						send_obj[key][kkey]["referrer"] = "";
					}
					send_obj[key][kkey]["referrer"] = session_data[4];
					if (send_obj[key][kkey]["window_size"] == undefined)
						send_obj[key][kkey]["window_size"] = [
							hmt_windowSize
						];
				}
			}
			if (!(send_buff.length < 5)) {
//				console.log("Sending....");
//				console.log('SEND OBJECT:', send_obj);
//				console.log('BUFF:', send_buff);
//			var body = document.body,
//				html = document.documentElement;
//
//			var height = Math.max( body.scrollHeight, body.offsetHeight,
//				html.clientHeight, html.scrollHeight, html.offsetHeight );
//			    console.log(height);
				JSONP(jsonp_url + encodeBase64(hmtracker_serialize(send_obj)) + "&callback", function (a, b, c) {
				});
				buff = {};
				setHMTrackerData(hmtracker_cookie_name + "_buff", buff);
				sending = false;
				latest_update = NOW();
			} else {
				var session_data = getHMTrackerData(hmtracker_cookie_name + "_session");
				if (session_data == undefined) {
					session = create_new_session();
				} else {
					var now = NOW();
//					console.log("LATEST_UPDATE: ", latest_update);
//					console.log("NOW: ", now);
//					console.log("SESSION[1]: ", session[1]);
//					console.log("OPTION: ", <?php //print $option['opt_record_interval']; ?>//);
//					console.log("CALCULATION: ", (now - session[1] - <?php //print $option['opt_record_interval']; ?>//));
					session = hmtracker_unserialize(session_data);
					if ((now - session[1] - <?php print $option['opt_record_interval']; ?>) >= <?php print $option['opt_record_kill_session']; ?>) {
						latest_update = now;
						session = create_new_session();
					} else {
						if (typeof latest_update === "undefined") {
							latest_update = now;
						}
						session = update_session(session, latest_update);
					}
				}
				sending = false;
			}

		}

		setInterval(function () {
			sendData();
		}, send_interval)
		prevwinw = prevwinh = 0;
		lastwinh = window.innerHeight;
		lastwinw = window.innerWidth;
		setInterval(function () {
			if (isActive) {
//	    		console.log("Store Data in Buffer");
				if ((prevwinw != lastwinw || prevwinh != lastwinh || hmt_pageheight != getPageHeight()) && !sending) {

					hmt_pageheight = getPageHeight();
					var buff = getBuff(session[0], location, "window_size");
					buff[session[0]][location]["window_size"].push([parseFloat(time.toFixed(1)), lastwinh, lastwinw, hmt_pageheight]);
//			        console.log(3, buff);
					setHMTrackerData(hmtracker_cookie_name + "_buff", buff);

					prevwinw = lastwinw;
					prevwinh = lastwinh;
					session[1] = NOW();
				}

				var mmove_iterate = 0;
				if ((hmtracker_prevmousex != hmtracker_lastmousex || hmtracker_prevmousey != hmtracker_lastmousey) && !sending && <?php print ($option['opt_record_mousemove'])?'true':'false'; ?>) {

					var buff = getBuff(session[0], location, "mouse_move");
					if (mmove_iterate == 0) {
						mmove_iterate = 0;
						buff[session[0]][location]["mouse_move"].push([parseFloat(time.toFixed(1)), hmtracker_lastmousex, hmtracker_lastmousey, window.innerWidth, hmt_pageheight]);
//				        console.log(1, buff);
						setHMTrackerData(hmtracker_cookie_name + "_buff", buff);
					} else mmove_iterate--;

					hmtracker_prevmousex = hmtracker_lastmousex;
					hmtracker_prevmousey = hmtracker_lastmousey;
					session[1] = NOW();
				}

				if ((prevscrollv != hmtracker_lastscrollv || prevscrollh != hmtracker_lastscrollh) && !sending && <?php print ($option['opt_record_pagescroll'])?'true':'false'; ?>) {

					var buff = getBuff(session[0], location, "page_scroll");
					buff[session[0]][location]["page_scroll"].push([parseFloat(time.toFixed(1)), hmtracker_lastscrollv, hmtracker_lastscrollh, hmt_pageheight]);
//			        console.log(2, buff);
					setHMTrackerData(hmtracker_cookie_name + "_buff", buff);

					prevscrollv = hmtracker_lastscrollv;
					prevscrollh = hmtracker_lastscrollh;
					session[1] = NOW();
				}

				time += (interval / 1000);
				session[2] = time;
				setHMTrackerData(hmtracker_cookie_name + "_session", session);
			}
		}, interval)

		window.onmousemove = function (e) {
			hmtracker_lastmousex = e.pageX;
			hmtracker_lastmousey = e.pageY;
		}

		window.onscroll = function (e) {
			hmtracker_lastscrollv = window.pageYOffset;
			hmtracker_lastscrollh = window.pageXOffset;
		}

		window.onresize = function () {
			lastwinh = window.innerHeight;
			lastwinw = window.innerWidth;
		}

		if (!isiOS())
			window.onmousedown = function (event) {
				if (!sending && isActive && event.pageX <= document.body.clientWidth && event.pageY <= hmt_pageheight) {
					var cur_sess_data = getHMTrackerData(hmtracker_cookie_name + "_session");
					var cur_sess = hmtracker_unserialize(cur_sess_data);

					var buff = getBuff(cur_sess[0], location, "mouse_click");

					buff[cur_sess[0]][location]["mouse_click"].push([parseFloat(time.toFixed(1)), event.which, hmtracker_lastmousex, hmtracker_lastmousey, hmtracker_lastscrollv, hmtracker_lastscrollh, window.innerWidth, hmt_pageheight]);

					setHMTrackerData(hmtracker_cookie_name + "_buff", buff);
				}
			}
		if (isiOS())
			window.ontouchstart = function (event) {
				if (!sending && isActive && event.pageX <= document.body.clientWidth && event.pageY <= hmt_pageheight) {
					var cur_sess_data = getHMTrackerData(hmtracker_cookie_name + "_session");
					var cur_sess = hmtracker_unserialize(cur_sess_data);

					var buff = getBuff(cur_sess[0], location, "mouse_click");

					buff[cur_sess[0]][location]["mouse_click"].push([parseFloat(time.toFixed(1)), event.which, e.touches[0].pageX, e.touches[0].pageY, hmtracker_lastscrollv, hmtracker_lastscrollh, window.innerWidth, hmt_pageheight]);

					setHMTrackerData(hmtracker_cookie_name + "_buff", buff);
				}
			}

		function create_new_session() {
			time = 0;
			var session_id = Math.floor((Math.random() * 1000000000) + 1);
			session = [session_id, null, 0, null, document.referrer];
//			console.log("hmt_tracking_init() - New Session - " + session_id);
//			console.log(session);
			session = update_session(session);
			return session;
		}

		function update_current_session(session) {
//			console.log("Updatge Current Session", session);
			var now = NOW();
			if ((now - session[1] - <?php print $option['opt_record_interval']; ?>) > <?php print $option['opt_record_kill_session']; ?>) {
//				console.log("hmt_tracking_init() - Session Expired");
				time = 0;
				session = create_new_session();
			} else {
				if (session[3] != document.location.href) {
//					console.log("hmt_tracking_init() - Existing Session - New Page");
					time = 0;
				} else {
//					console.log("hmt_tracking_init() - Existing Session - Same Page");
					time = session[2];
				}
				session = update_session(session);
//				console.log(session);
			}
			return session;
		}

		function update_session(session, start_time) {

//			console.log("Update session", session);
//			console.log("START_TIME: ", start_time);

			if (typeof start_time === "undefined") {
				session[1] = NOW();
			}

			session[3] = document.location.href;
//			console.log("UPDATED SESSION: ", session);
			setHMTrackerData(hmtracker_cookie_name + "_session", session, 365);
			return session;
		}

		function NOW() {
			return Math.floor((new Date()).getTime() / 1000);
		}

	}

	function getPageHeight() {
		hmt_bodyHeight = document.body;
		hmt_htmlHeight = document.documentElement;

		return Math.max(hmt_bodyHeight.scrollHeight, hmt_bodyHeight.offsetHeight,
			hmt_htmlHeight.clientHeight, hmt_htmlHeight.scrollHeight, hmt_htmlHeight.offsetHeight);

	}

	//	hmtrackerreadyList = []

	var funcDomReady = '';
	function onDomReady(func) {
		var oldonload = funcDomReady;
		if (typeof funcDomReady != 'function')
			funcDomReady = func;
		else {
			funcDomReady = function () {
				oldonload();
				func();
			}
		}
	}

	onDomReady(hmt_tracking_init());
	function init() {
		if (arguments.callee.done) return;
		arguments.callee.done = true;
		if (funcDomReady)funcDomReady();
	}

	if (document.addEventListener)
		document.addEventListener("DOMContentLoaded", init, false);

	if (/WebKit/i.test(navigator.userAgent)) {
		var _timer = setInterval(function () {
			if (/loaded|complete/.test(document.readyState)) {
				clearInterval(_timer);
				init();
			}
		}, 10);
	}

	window.onload = init;