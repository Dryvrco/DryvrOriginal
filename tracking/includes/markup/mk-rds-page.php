<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 13-11-2014
 * Time: 15:01
 */

include( "includes/functions/fn-rds-process.php" );

if(is_agency()) {
	include( $this->AGENCY_MARKUP_PATH . "admin/mk-header.php" );
	include( $this->AGENCY_MARKUP_PATH . "admin/mk-sidebar.php" );
} elseif(is_personal()) {
	include( $this->COMMON_MARKUP_PATH . "mk-header.php" );
}
?>
	<div id="content">
		<div class="analytics-block">
			<h2>IAM Settings</h2>

			<form action="<?php echo admin_url() ?>?rds" method="POST" class="form-horizontal" id="aimform" autocomplete="off">
				<div class="control-group">
					<label class="control-label">Key</label>

					<div class="controls">
						<input style="height: 30px;" type="text" class="license_key" name="iam_key" value="<?php echo isset( $this->OPTIONS['iam_key'] ) ? $this->OPTIONS['iam_key'] : ""; ?>"/>
						<?php echo showError( "iam_key", $errors ); ?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Secret</label>

					<div class="controls">
						<input style="height: 30px; width: 500px;" type="text" class="license_key" name="iam_secret" value="<?php echo isset( $this->OPTIONS['iam_secret'] ) ? $this->OPTIONS['iam_secret'] : ""; ?>"/>
						<?php echo showError( "iam_secret", $errors ); ?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Region</label>

					<div class="controls">
						<input style="height: 30px;" type="text" disabled class="license_key" name="iam_region" value="<?php echo isset( $this->OPTIONS['iam_region'] ) ? $this->OPTIONS['iam_region'] : ""; ?>"/>
						<?php echo showError( "iam_secret", $errors ); ?>
					</div>
				</div>

				<div class="form-actions">
					<input type="submit" class="btn btn-primary width-auto save-button fldsubmitLicense" value="Save changes"/>
				</div>
				<input type="hidden" name="form" value="aim"/>
			</form>
			<?php include( $this->COMMON_MARKUP_PATH . "mk-rdssettings-page.php" ); ?>
		</div>
	</div>
<?php
if(is_agency()) {
	include( $this->AGENCY_MARKUP_PATH . "admin/mk-footer.php" );
} elseif(is_personal()) {
	include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' );
}

?>