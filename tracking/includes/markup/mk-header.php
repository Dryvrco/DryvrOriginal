<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0 "/>
	<title><?php echo $this->OPTIONS['brandname'] ?></title>

	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->getFavIcon(); ?>" />
	<link href="<?php echo admin_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<link media="all" rel="stylesheet" type="text/css" href="<?php echo admin_url() ?>css/all.css"/>
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'/>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo admin_url() ?>css/flags.css"/>
	<link href="<?php echo admin_url() ?>assets/plugins/jqvmap/jqvmap/jqvmap.css" media="screen" rel="stylesheet" type="text/css"/>
	<link href="<?php echo admin_url() ?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo admin_url() ?>assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo admin_url() ?>css/datepicker.css"/>
	<link rel="stylesheet" type="text/css" media="all" href="<?php echo admin_url() ?>css/style.css"/>

	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery-1.8.3.min.js" type="text/javascript"></script>
	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<!--[if lt IE 9]>
	<script src="<?php echo $this -> PLUGIN_URL ?>assets/plugins/excanvas.js"></script>
	<script src="<?php echo $this -> PLUGIN_URL ?>assets/plugins/respond.js"></script>
	<![endif]-->
	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/breakpoints/breakpoints.js" type="text/javascript"></script>
	<!-- IMPORTANT! jquery.slimscroll.min.js depends on jquery-ui-1.10.1.custom.min.js -->

	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery.blockui.js" type="text/javascript"></script>
	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/jquery-cookie.js" type="text/javascript"></script>
	<script src="<?php echo $this->PLUGIN_URL ?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
</head>
<body>
<div id="wrapper">
	<div id="header">
		<strong class="logo"><a href="<?php echo admin_url() ?>"><img src="<?php echo $this->getBrandLogo(); ?>" alt="logo"/></a></strong>
		<ul id="nav">
			<?php
			if ( isset( $_SESSION['return_to_admin'] ) && $_SESSION['return_to_admin'] ) {
				?>
				<li><a href="<?php echo admin_url() ?>?return_admin">Return to Admin</a></li>
			<?php } ?>
			<li><a href="<?php echo admin_url() ?>">Projects</a></li>
			<li><a href="<?php echo $this->OPTIONS['brandsupport'] ?>" target="_blank">Support</a></li>
			<li><a href="<?php echo admin_url() ?>?logout">Log Out</a></li>
		</ul>
	</div>
	<div id="main">
		<div class="container">
			<div class="headbar">

				<?php checkDependencies() ?>

				<em class="date"><?php echo @date( "F d, Y" ) ?></em>

				<?php if ( is_agency() && is_user() ) { ?>
					<div class="pull-right">&nbsp;</div>
					<div class="pull-right"><?php show_status( true ); ?> </div>
				<?php } ?>
			</div>
			<?php if ( is_personal() || ( is_agency() && is_user() ) ) { ?>
				<div id="sidebar">
					<ul class="sidenav">
						<?php if ( empty( $_GET ) || isset( $_GET['rds'] ) || isset( $_GET['upayments'] ) || isset( $_GET['usersettings'] ) || isset( $_GET['helpvideos'] ) || isset( $_GET['changepackage'] ) || isset( $_GET['adminsettings'] ) || isset( $_GET['about'] ) ) { ?>
							<li>
								<a <?php echo empty( $_GET ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>"><i class="icon-th-list"></i>Projects</a>
							</li>
							<?php if ( is_agency() ) { ?>
								<li>
									<a <?php echo isset( $_GET['upayments'] ) || isset( $_GET['changepackage'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?upayments"><i class="icon-money"></i> Payments</a>
								</li>
								<li>
									<a <?php echo isset( $_GET['usersettings'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?usersettings"><i class=" icon-wrench"></i> Account Settings</a>
								</li>
							<?php } else { ?>
								<li>
									<a  <?php echo isset( $_GET['adminsettings'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?adminsettings"><i class=" icon-wrench"></i> Admin Settings</a>
								</li>
							<?php } ?>
						<?php } else { ?>
							<li>
								<a <?php echo isset( $_GET['project'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?project&name=<?php echo $_GET['name'] ?>"><i class="icon-th-list"></i> Dashboard</a>
							</li>
							<li>
								<a <?php echo isset( $_GET['analytics'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?analytics&name=<?php echo $_GET['name'] ?>"><i class="icon-bar-chart"></i> User Sessions</a>
							</li>
							<li>
								<a <?php echo isset( $_GET['hmaps'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?hmaps&name=<?php echo $_GET['name'] ?>"><i class="icon-dashboard"></i> Heat Maps</a>
							</li>
							<li>
								<a <?php echo isset( $_GET['ppages'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?ppages&name=<?php echo $_GET['name'] ?>"><i class="icon-star"></i> Popular Pages</a>
							</li>
							<li>
								<a <?php echo isset( $_GET['mdata'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?mdata&name=<?php echo $_GET['name'] ?>"><i class="icon-hdd"></i> Manage Data</a>
							</li>
							<li>
								<a <?php echo isset( $_GET['settings'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?settings&name=<?php echo $_GET['name'] ?>"><i class="icon-cogs"></i> Settings</a>
							</li>
						<?php }
						if ( is_personal() && ! isset( $_GET['name'] ) && isset( $this->OPTIONS['iam_key'] ) && $this->OPTIONS['iam_key'] != "" ) { ?>
							<li>
								<a<?php echo( isset( $_GET['rds'] ) ? ' class="active"' : '' ); ?> href="<?php echo admin_url() ?>?rds">
									<i class="icon-fire"></i> AWS RDS Settings
								</a>
							</li>
						<?php } ?>

						<li>
							<a <?php echo isset( $_GET['helpvideos'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?helpvideos"><i class="icon-facetime-video"></i> Help Videos</a>
						</li>
						<li>
							<a href="<?php echo $this->OPTIONS['brandsupport'] ?>"><i class=" icon-comments-alt" target="_blank"></i> Support</a>
						</li>
						<?php if ( ( is_personal() && ! isset( $_GET['name'] ) ) || ( is_agency() && is_admin() ) ) { ?>
							<li>
								<a <?php echo isset( $_GET['about'] ) ? 'class="active"' : ''; ?> href="<?php echo admin_url() ?>?about"><i class=" icon-info-sign"></i> About This Software</a>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
