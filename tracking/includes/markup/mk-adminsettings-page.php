<?php
/*
 * HeatMapTracker
 * (c) 2013. HeatMapTracker 
 * http://HeatMapTracker.com
 */
if ( ! defined( 'HMT_STARTED' ) || ! isset( $this->PLUGIN_PATH ) ) {
	die( 'Can`t be called directly' );
}
if ( ! is_user_logged_in() && IS_KEY_VALID ) {
	header( 'location: ' . admin_url() . '?login' );
}
include( "mk-header.php" );
if ( is_agency() && is_admin() ) {
	include( $this->AGENCY_MARKUP_PATH . "admin/mk-sidebar.php" );
}
?>
	<div id="content">
		<div class="analytics-block">
			<h2>Admin Settings</h2>

			<div class="table-holder">


				<form action="<?php echo admin_url() ?>?adminsettings" method="POST" class="form-horizontal" id="loginform" autocomplete="off">

					<div class="control-group">
						<label class="control-label">Email</label>

						<div class="controls">
							<input type="text" id="email" name="fldEmail" class="" value="<?php echo $this->OPTIONS['email'] ?>"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">New Password</label>

						<div class="controls">
							<input type="password" id="pass1" class="" value="" name="fldPass"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Password Again</label>

						<div class="controls">
							<input type="password" id="pass2" class="" value=""/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">License</label>

						<div class="controls">
							<input type="text" disabled="" readonly="" class="license_key" value="<?php echo $this->OPTIONS['license_key'] ?>"/>
							<a href="#projectModal" data-toggle="modal" type="button" class="btn btn-primary btn-mini fldsubmitLicenseU">Deactivate</a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Blocked login IP's</label>

						<div class="controls" style="width: 200px;">

							<?php if ( $this->BANNED_LOGINS ) {
								foreach ( $this->BANNED_LOGINS as $key => $value ) {
									?>
									<div class="label btn-primary"
									     style="font-size: 14px; margin-top: 3px; padding: 4px;"><?php echo $key; ?>
										<button class="close del_ip" type="button" style="float: none;" to-del="<?php echo $key; ?>">×</button>
									</div>
								<?php
								}
							} ?>
						</div>

					</div>
					<?php if ( is_agency() ) { ?>
						<div class="control-group">
							<label class="control-label">PayPal Email</label>

							<div class="controls">
								<input type="text" id="payemail" class="" name="fldPayEmail"
								       value="<?php echo $this->OPTIONS['paypal_email'] ?>" required email/>

							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Service Name</label>

							<div class="controls">
								<input type="text" id="bname" class="" name="fldBName"
								       value="<?php echo $this->OPTIONS['brandname'] ?>"/>

							</div>
						</div>

						<div class="control-group">
							<label class="control-label"> Logo URL (254x58 px)</label>

							<div class="controls">
								<input type="text" id="blogo" class="" name="fldBLogo" value="<?php echo $this->getBrandLogo(); ?>"/>

							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Support URL</label>

							<div class="controls">
								<input type="text" id="bsupport" class="" name="fldBSupport"
								       value="<?php echo $this->OPTIONS['brandsupport'] ?>"/>

							</div>
						</div>

						<div class="control-group">
							<label class="control-label">User Help Section</label>

							<div class="controls">
								<textarea rows="5" cols="35" id="help_area" name="fldBHelp"><?php echo html_entity_decode( $this->OPTIONS['help_area'] ) ?></textarea>
							</div>
						</div>
					<?php } ?>

					<div class="form-actions">
						<button type="button" class="btn btn-primary width-auto save-button fldsubmitLicense" data-loading-text="Saving...">
							Save changes
						</button>
					</div>
					<input type="hidden" name="form" value="admin"/>
				</form>

			</div>
			<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/alerter.js" type="text/javascript"></script>
			<script type="text/javascript">
				jQuery(document).ready(function () {
					<?php if(isset($_GET['submit']) && $_GET["submit"] == 'true' ) {?>
					jQuery("#loginform").prepend(alerter("Settings Saved.", 2));
					<?php } ?>
					jQuery(".fldsubmitLicense").click(function () {

						jQuery("#loginform .alert").remove();

						if (jQuery('#email').val() == "") {
							jQuery('#email').focus();
							return false;
						}

						if (jQuery('#payemail').val() == "") {
							jQuery('#payemail').focus();
							return false;
						}

						if (jQuery('#pass1').val() != jQuery('#pass2').val()) {
							jQuery('#pass2').focus();
							return false;
						}

						if (jQuery('#help_area').val() == "") {
							jQuery('#help_area').focus();
							return false;
						}

						jQuery(this).button('loading');
						jQuery("#loginform").submit();
					});


					jQuery(".fldsubmitLicenseU").click(function () {
						jQuery("#register-form .alert").remove();

						var post = {}
						post.fldTask = 'deregister';
						post.fldLicense = jQuery('.license_key').val();

						jQuery(this).button('loading')
						jQuery.post('<?php echo admin_url() ?>/?hmtrackerregister', post, function (data) {
							if (data.indexOf('Successfully') != -1) {
								jQuery("#register-form").prepend(alerter(data, 2));
								setTimeout(function () {
									top.location.reload();
								});
							} else {
								jQuery("#register-form").prepend(alerter(data, 1));
							}
							jQuery('.fldsubmitLicenseU').button('reset');
						});

					});

					jQuery('.del_ip').click(function () {
						//post var
						var post = {};
						post.ip = jQuery(this).attr('to-del');
						post.action = 'del_ban_ip';
						var latest_el = jQuery(this);
						//sending
						jQuery.post('<?php echo admin_url() ?>/?hmtrackeractions', post, function (data) {
							if (data == "ok") {
								latest_el.parent().remove();
							}
						});
					})

				});
			</script>
		</div>
	</div>
<?php include( 'mk-footer.php' ); ?>