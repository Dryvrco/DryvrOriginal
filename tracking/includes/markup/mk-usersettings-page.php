<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
?>
<?php if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
} ?>
<?php
global $loggedin_user;
if ( ! is_user_logged_in( $loggedin_user ) && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
} ?>
<?php $user = current_user();
include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content-holder">
	<div class="analytics-block">
		<h2>Account Settings</h2>

		<div class="table-holder">

			<form action="#" method="POST" class="form-horizontal" id="loginform" autocomplete="off">

				<div class="control-group">
					<label class="control-label">Email</label>

					<div class="controls">
						<input type="text" id="email" class="" value="<?php echo $user->email; ?>"/>

					</div>
				</div>
				<div class="control-group">
					<label class="control-label">New Password</label>

					<div class="controls">
						<input type="password" id="pass1" class="" value=""/>

					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Password Again</label>

					<div class="controls">
						<input type="password" id="pass2" class="" value=""/>
					</div>
				</div>
				<?php if ( $user->status != USER_STATUS_FREE && $user->status != USER_STATUS_OVERRIDDEN ) { ?>
					<div class="control-group">
						<label class="control-label">Domains</label>

						<div class="controls">

							<input id="all_domains" type="text" class="input-medium" value=""/> <a type="button"
							                                                                       class="btn btn-primary btn-mini add_ip">Add</a>

							<div id="domain_cont" style="width:180px;">
								<?php if ( count( $this->USER_DOMAINS['opt_tracking_domains'] ) > 0 ) {
									foreach ( $this->USER_DOMAINS['opt_tracking_domains'] as $key => $value ) { ?>
										<div class="label btn-info"
										     style="font-size: 14px; margin-top: 3px; padding: 4px;"><?php echo $value; ?>
											<button class="close del_ip" type="button" style="float: none;"
											        to-del="<?php echo $value; ?>">×
											</button>
										</div>
									<?php }
								} ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="form-actions">
					<button type="button" class="btn btn-primary save-button fldsubmitLicense width-auto"
					        data-loading-text="Saving...">
						Save changes
					</button>
				</div>

			</form>


		</div>
	</div>
</div>
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/alerter.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function () {
		App.init(); // initlayout and core plugins


		jQuery(".fldsubmitLicense").click(function () {

			jQuery("#loginform .alert").remove();

			if (jQuery('#email').val() == "") {
				jQuery('#email').focus();
				return false;
			}

			if (jQuery('#pass1').val() == "") {
				jQuery('#pass1').focus();
				return false;
			}

			if (jQuery('#pass1').val() != jQuery('#pass2').val()) {
				jQuery('#pass2').focus();
				return false;
			}

			var post = {}
			post.fldEmail = jQuery('#email').val();
			if (jQuery('#pass2').val() != "") post.fldPass = jQuery('#pass2').val();

			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>?changeudata', post, function (data) {
				if (data.indexOf('Successfully') != -1) {
					jQuery("#loginform").prepend(alerter(data, 2));
					//setTimeout(function(){ top.location.reload(); })
				} else {
					jQuery("#loginform").prepend(alerter(data, 1));
				}
				jQuery('.fldsubmitLicense').button('reset');
			});
		});


		//ip
		jQuery('.add_ip').click(function () {

			//validate
			if (jQuery('#all_domains').val() == "" || !CheckIsValidDomain(jQuery('#all_domains').val())) {
				FieldMsg("Please use correct domain name");
				jQuery('#all_domains').focus();
				return false;
			}

			//post var
			var post = {};
			post.domain = jQuery('#all_domains').val();
			post.action = 'add_tracking_domain';

			//sending
			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>/?hmtrackeractions', post, function (data) {
				jQuery('.add_ip').button('reset');

				if (data == "exists") {
					FieldMsg("Domain already exists");
					return false;
				}

				if (data == "overflow") {
					FieldMsg("Domains limit reached");
					return false;
				}


				jQuery('#domain_cont').append('<div class="label btn-primary" style="font-size: 14px; margin-top: 3px; padding: 4px;">' + data + '<button class="close del_ip" type="button" style="float: none;" to-del="' + data + '">×</button></div><br/>')
				to_det_init();
			});
		})
		to_det_init();

	});

	function FieldMsg(msg) {

		jQuery('#all_domains').popover({title: 'Wrong Domain', content: msg, placement: 'top'}).popover("show");
		setTimeout(function () {
			jQuery('#all_domains').popover("hide").popover('destroy');
		}, 2000);


	}

	function CheckIsValidDomain(domain) {
		var re = new RegExp(/^((?:(?:(?:\w[\.\-\+]?)*)\w)+)((?:(?:(?:\w[\.\-\+]?){0,62})\w)+)\.(\w{2,6})$/);
		return domain.match(re);
	}

	function to_det_init() {
		jQuery('.del_ip').click(function () {
			//post var
			var post = {};
			post.domain = jQuery(this).attr('to-del');
			post.action = 'del_tracking_domain';
			var latest_el = jQuery(this);
			//sending
			jQuery.post('<?php echo admin_url() ?>/?hmtrackeractions', post, function (data) {
				if (data == "ok") {
					latest_el.parent().remove();
				}
			});
		})
	}
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
