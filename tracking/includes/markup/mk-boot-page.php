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
if ( is_agency() ) {
	$user                = current_user();
	$active_plan         = get_active_plan( $user );
	$max_domains_reached = count( $this->PROJECTS ) >= $active_plan['pack_domains'] + $active_plan['extradomains_count'];
	$cur_status_id       = detect_user_status( $user );
	$ui_enabled          = validate_user_status( $cur_status_id );
} elseif ( is_personal() ) {
	$ui_enabled = true;
}
include( $this->COMMON_MARKUP_PATH . 'mk-header.php' );
?>
<div id="content-holder">
	<div class="analytics-block">
		<h2><span>VIEW AND MANAGE PROJECTS</span> Projects</h2>

		<?php if ( is_array( $this->PROJECTS ) && count( $this->PROJECTS ) > 0 ) {
			if ( is_personal() || ( ( $ui_enabled && ! $max_domains_reached ) || $user->status == USER_STATUS_FREE || $user->status == USER_STATUS_OVERRIDDEN ) ) {
				?>
				<a href="#projectModal" role="button" class="btn btn-primary btn-mini width-auto" data-toggle="modal">
					+ Create
				</a>
				<br/><br/>
			<?php } else { ?>
				<div class="control-group">
					<div class="label label-warning">
						Your domain limit has been reached. Please click
						<a href="<?php echo $this->PLUGIN_URL ?>?changepackage">Change Package</a> to upgrade
					</div>
				</div>

			<?php } ?>
		<?php } ?>

		<div id="content">

			<?php
			if ( $this->PROJECTS === false ) {
				$this->PROJECTS = array();
			}
			if ( count( $this->PROJECTS ) < 1 ) {
				?>
				<div class="form-wizard">
					<div class="navbar steps">
						<div style="padding-top: 10px">
							<ul class="row-fluid">
								<li class="span5 <?php if ( count( $this->PROJECTS ) == 0 ) {
									echo 'active';
								} ?> ">
									<img src="<?php echo $this->PLUGIN_URL ?>/images/p-setup.png" <?php if ( count( $this->PROJECTS ) == 1 ) {
										echo 'style="opacity: 0.5"';
									} ?> />
								</li>
								<li class="span5 <?php if ( count( $this->PROJECTS ) == 1 ) {
									echo 'active';
								} ?> ">
									<img src="<?php echo $this->PLUGIN_URL ?>/images/c-setup.png" <?php if ( count( $this->PROJECTS ) == 0 ) {
										echo 'style="opacity: 0.5"';
									} ?> />
								</li>
							</ul>
						</div>
					</div>
					<div class="tab-content">
						<div class="tab-pane <?php if ( count( $this->PROJECTS ) == 0 ) {
							echo 'active';
						} ?>"
						     id="tab1">
							<form class="form-horizontal no-padding no-margin" method="post" action="<?php echo admin_url() ?>?hmtrackeractions">
								<input type="hidden" name="action" value="create"/>

								<div class="control-group">
									<label class="control-label"></label>

									<div class="controls">
										<h4 class="center">Enter project details</h4>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Project Name</label>

									<div class="controls">
										<input style="width: 490px" type="text" name="projectname2" required/>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Project Description</label>

									<div class="controls">
										<textarea style="width: 490px" class="input-xlarge" rows="3" name="projectdescription2" required></textarea>
									</div>
								</div>
								<div class="form-actions clearfix">
									<button type="button" class="btn btn-primary <?php echo( $ui_enabled ? "createproject2 " : "" ); ?>width-auto"<?php echo( $ui_enabled ? "" : " disabled" ); ?>>
										Continue
									</button>
								</div>
							</form>
						</div>
						<div class="tab-pane <?php if ( count( $this->PROJECTS ) == 1 ) {
							echo 'active';
						} ?>"
						     id="tab2">
							<h4></h4>
							<h4>Please complete step 1</h4>
						</div>
					</div>
				</div>
			<?php } else { ?>
				<?php foreach ( $this->PROJECTS as $project => $value ) { ?>
					<div class="project-block">
						<?php
						$table1 = T_PREFIX . $this->OPTIONS['dbtable_name_clicks'];
						$table2 = T_PREFIX . $this->OPTIONS['dbtable_name'];

						if ( is_agency() ) {
							$table1 .= "_{$user->user_key}";
							$table2 .= "_{$user->user_key}";
						}

						$pages_q     = "SELECT COUNT( distinct `page_url`) FROM `" . $table1 . "` WHERE `project` = '" . $project . "'";
						$pages_count = $wpdb->queryUniqueValue( $pages_q );

						$session_q     = "SELECT COUNT( `session_id`) FROM `" . $table2 . "` WHERE `project` = '" . $project . "'";
						$session_count = $wpdb->queryUniqueValue( $session_q );

						$usr_uniq_q     = "SELECT COUNT( distinct `user_id` ) FROM `" . $table2 . "` WHERE `project` = '" . $project . "'";
						$usr_uniq_res   = $wpdb->queryUniqueValue( $usr_uniq_q );

						?>
						<div class="sec-head">
							<strong
								class="title"><span>PROJECT:</span>  <?php echo rawurldecode( $project ) ?> <?php if ( $value['description'] != "" ): ?> (<?php echo $value['description']; ?>) <?php endif; ?>
							</strong>
							<ul class="sub-links">
								<?php if ( $ui_enabled ) { ?>
									<li>
										<a href="<?php echo admin_url() ?>?project&name=<?php echo $project ?>" data-toggle="modal">PROJECT DASHBOARD</a>
									</li>
									<li>
										<a href="<?php echo admin_url() ?>?settings&name=<?php echo $project ?>" data-toggle="modal">SETTINGS</a>
									</li>
									<li>
										<a href="#delproject" role="button" class="delproject" data-toggle="modal" data-value="<?php echo $project ?>">DELETE PROJECT</a>
									</li>
								<?php } ?>
								<?php if ( ! $ui_enabled ) { ?>
									<li><a href="#">PROJECT DASHBOARD</a></li>
									<li><a href="#">SETTINGS</a></li>
									<li><a href="#">DELETE PROJECT</a></li>
								<?php } ?>
							</ul>
						</div>
						<div class="info-block">
							<div class="info-box green">
								<span>TOTAL VISITORS</span>
								<strong><?php echo $usr_uniq_res; ?></strong>
							</div>
							<div class="info-box orange">
								<span>TRACKING PAGES</span>
								<strong><?php echo ( empty( $pages_count ) ) ? 0 : $pages_count; ?></strong>
							</div>
							<div class="info-box blue">
								<span>TOTAL SESSIONS</span>
								<strong><?php echo $session_count; ?></strong>
							</div>
						</div>
						<div class="code-block">
							<div class="holder">
								<textarea class="code" rows="4"><?php include( 'views/mk-hmtrackerjs.php' ); ?></textarea>
								<a class="btn-code" href="#">SELECT ALL</a>
							</div>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
</div>
<?php include( $this->COMMON_MARKUP_PATH . 'mk-footer.php' ); ?>

<!-- MODALS-->
<div id="projectModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="projectModal"
     aria-hidden="true">
	<div class="modal-header">
		<h3 id="myModalLabel3">New Project</h3>
	</div>
	<div class="modal-body">
		<p>&nbsp;</p>

		<form class="form-horizontal no-padding no-margin" method="post" action="<?php echo admin_url() ?>?restoreit">
			<div class="control-group">
				<label class="control-label">Project Name</label>

				<div class="controls">
					<input style="width: 290px" type="text" name="projectname" required/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Project Description</label>

				<div class="controls">
					<textarea style="width: 290px" class="input-xlarge" rows="3" name="projectdescription" required></textarea>
				</div>
			</div>
		</form>

	</div>
	<div class="modal-footer">
		<a class="btn btn-primary width-auto" data-dismiss="modal" aria-hidden="true">Close</a>
		<?php if ( $ui_enabled ): ?><a class="btn btn-primary width-auto createproject">Create</a><?php endif; ?>
		<?php if ( ! $ui_enabled ): ?><a class="btn btn-primary width-auto">Create</a><?php endif; ?>
	</div>
</div>
<div id="delproject" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
     aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel1">Delete <span id="delprojtitle"></span></h3>
	</div>
	<div class="modal-body">
		<p>Please confirm deleting this project and all its data</p>
	</div>
	<div class="modal-footer">
		<a class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Cancel</a>
		<a class="btn btn-primary" id="delprojectaction" data-value="">Delete</a>
	</div>
</div>
<div id="delprojectdata" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3"
     aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel3">Delete All Data for <span id="delprojdatatitle"></span></h3>
	</div>
	<div class="modal-body">
		<p>Please confirm deleting project data</p>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		<button id="delprojectdataaction" class="btn btn-danger" data-value="">Delete</button>
	</div>
</div>
<div id="help_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
     aria-hidden="true" style="width: 828px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel1">Help Video</h3>
	</div>
	<div class="modal-body" style=" max-height:650px; overflow: hidden">
		<iframe width="800" height="600" src="//www.youtube.com/embed/hThHYK9qc5c" frameborder="0"
		        allowfullscreen></iframe>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<!-- MODALS END-->
<!-- END FOOTER -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery.peity.min.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/app.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/index.js" type="text/javascript"></script>
<script src="<?php echo $this->PLUGIN_URL ?>assets/scripts/form-wizard.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
	jQuery(document).ready(function () {
		App.init(); // initlayout and core plugins
		Index.init();
		Index.initPeityElements(); // init pierty elements
		FormWizard.init();

		jQuery(".code-block textarea").focus(function () {
			jQuery(this).select();
		});

		jQuery('.btn-code').click(function () {
			jQuery(this).parent().find('.code').focus().select();
			return false;
		})

		jQuery('.createproject').click(function () {
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


		jQuery('textarea[name="projectdescription2"]').keyup(function () {
			var $th = jQuery(this);
			$th.val($th.val().replace(/[^a-zA-Z0-9 ,.\n]/g, function (str) {
				$th.popover({
					title: 'You typed " ' + str + ' "',
					content: "Please use only letters and numbers"
				}).popover("show");
				setTimeout(function () {
					jQuery('textarea[name="projectdescription2"]').popover("hide").popover('destroy');
				}, 2000);
				return '';
			}));
		});
		jQuery('textarea[name="projectdescription"]').keyup(function () {
			var $th = jQuery(this);
			$th.val($th.val().replace(/[^a-zA-Z0-9 ,.\n]/g, function (str) {
				$th.popover({
					title: 'You typed " ' + str + ' "',
					content: "Please use only letters and numbers",
					placement: 'top'
				}).popover("show");
				setTimeout(function () {
					jQuery('textarea[name="projectdescription"]').popover("hide").popover('destroy');
				}, 2000);
				return '';
			}));
		});

		jQuery('input[name="projectname2"]').keyup(function () {
			var $th = jQuery(this);
			$th.val($th.val().replace(/[^a-zA-Z0-9 ,.\n&]/g, function (str) {
				$th.popover({
					title: 'You typed " ' + str + ' "',
					content: "Please use only letters and numbers"
				}).popover("show");
				setTimeout(function () {
					jQuery('input[name="projectname2"]').popover("hide").popover('destroy');
				}, 2000);
				return '';
			}));
		});

		jQuery('input[name="projectname"]').keyup(function () {
			var $th = jQuery(this);
			$th.val($th.val().replace(/[^a-zA-Z0-9 ,.\n&]/g, function (str) {
				$th.popover({
					title: 'You typed " ' + str + ' "',
					content: "Please use only letters and numbers",
					placement: 'bottom'
				}).popover("show");
				setTimeout(function () {
					jQuery('input[name="projectname"]').popover("hide").popover('destroy');
				}, 2000);
				return '';
			}));
		});


		jQuery('.createproject2').click(function () {
			if (jQuery('input[name="projectname2"]').val() == "") {
				jQuery('input[name="projectname2"]').focus();
				return false
			}
			if (jQuery('textarea[name="projectdescription2"]').val() == "") {
				jQuery('textarea[name="projectdescription2"]').focus();
				return false
			}

			var post = {}
			post.action = 'create';
			post.name = encodeURIComponent(jQuery('input[name="projectname2"]').val());
			post.description = jQuery('textarea[name="projectdescription2"]').val();

			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>?hmtrackeractions', post, function (data) {
				jQuery('#createproject').button('reset')
				if (data == 'ok') {
					location.reload();
				}
			});
		})
		jQuery('.delproject').click(function () {
			jQuery('#delprojtitle').text(decodeURIComponent(jQuery(this).attr('data-value')));
			jQuery('#delprojectaction').attr('data-value', jQuery(this).attr('data-value'));
		})
		jQuery('.delprojectdata').click(function () {
			jQuery('#delprojdatatitle').text(decodeURIComponent(jQuery(this).attr('data-value')));
			jQuery('#delprojectdataaction').attr('data-value', jQuery(this).attr('data-value'));
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


		jQuery('#delprojectdataaction').click(function () {
			var post = {}
			post.action = 'deletedata';
			post.name = jQuery(this).attr('data-value');

			jQuery(this).button('loading')
			jQuery.post('<?php echo admin_url() ?>?hmtrackeractions', post, function (data) {
				jQuery('#createproject').button('reset')
				if (data == 'ok') {
					location.reload();
				}
			});
		})
		jQuery('.help-ico').popover({'placement': 'right'});

	});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
