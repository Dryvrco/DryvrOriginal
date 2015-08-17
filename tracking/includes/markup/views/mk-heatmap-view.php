<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2014/06/30
 * Time: 10:37 AM
 */
$u = parse_url( $url );
$h = parse_url( $home_url );
$h = "{$u['scheme']}://{$h['host']}{$h['path']}";
?>
<div class="navbar navbar-fixed-top" style="z-index: 20">
	<div class="navbar-inner">
		<div id="heatmap_menu" class="container" style="margin-left: 10px">
			<a class="brand" href="javascript: window.close()" style="padding-top: 0; padding-bottom: 0">
				<img src="<?php echo $brandLogo; ?>" alt="logo" style="max-height: 40px;" class="center"/>
			</a>
			<ul class="nav">
				<?php if ( $count > 0 ): ?>
					<li class="divider-vertical"></li>
					<li style="margin: 10px 0 0; width: 150px; text-align: right;" id="ie_message"><span><?php echo $count; ?></span> points analyzed</li>
					<li class="divider-vertical"></li>
					<li style="margin: 10px 0 0;">From: <?php echo $from ?> To: <?php echo $to ?></li>
					<?php if ( $map == 'scroll' ) { ?>
						<li class="divider-vertical"></li>
						<li style="margin: 10px 0 0;">Scroll grid step: &nbsp;&nbsp;</li>
						<li style="margin: 10px 0 0;">
							<input id="grd_step" style=" height: 23px; margin: 0; padding: 0;" class="span1 opt_record_interval" min="50" max="500" step="10" value="<?php echo $grid_step; ?>" type="number">
						</li>
					<?php } else { ?>
						<li class="divider-vertical"></li>
						<li style="margin: 10px 0 0;">Aggregation Factor: &nbsp;&nbsp;</li>
						<li style="margin: 10px 0 0;">
							<input id="variance" style=" height: 23px; margin: 0; padding: 0;" class="span1 opt_record_interval" min="1" max="20" step="1" value="<?php echo $variance; ?>" type="number">
						</li>
						<li class="divider-vertical"></li>
						<li style="margin: 10px 0 0;">Min point count: &nbsp;&nbsp;</li>
						<li style="margin: 10px 0 0;">
							<input id="min_point_count" style=" height: 23px; margin: 0; padding: 0;" class="span1 opt_record_interval" min="1" max="10" step="1" value="<?php echo $min_point_count; ?>" type="number">
						</li>
					<?php } ?>
					<li class="divider-vertical"></li>
				<?php
				elseif ( ! $count > 0 ): ?>
					<li class="divider-vertical"></li>
					<li style="margin: 10px 0 0;"><strong style="color:#f00">No tracking data for the selected time period</strong></li>
					<li class="divider-vertical"></li>
					<li style="margin: 10px 0 0;">For the period From: <?php echo $from; ?>
						To: <?php echo $to; ?></li>
				<?php endif; ?>
			</ul>
			<?php if ( $count > 0 ) { ?>
				<div id="loader" style="margin-top: 10px;"><span id="loader-text">Loading webpage:</span>&nbsp;&nbsp;<img src="<?php echo $home_url; ?>/images/loader.gif"/></div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="heat-holder spy-frame" style="width: <?php echo $width == 0 ? '100%' : "{$width}px"; ?>; margin-top: 41px; position: relative; z-index: 10; overflow-y: scroll">
	<?php if ( $count > 0 ): ?>
		<div id="heatmapArea" class="spy-frame" style="position: absolute !important; z-index:9999 !important; top: 0px; left: 0; width: <?php echo $width == 0 ? '100%' : "{$width}px"; ?>; height: <?php echo $height; ?>px;"></div>
		<iframe id="spy-iframe" class="spy-frame"
		        src="<?php echo preg_replace( "%~%", ".", $url ); ?>"
		        name="spy-frame" frameborder="0" noresize="noresize" scrolling="no"
		        style="width:100%<?php /*echo $width == 0 ? '100%' : "{$width}px"; */ ?>; height:<?php echo $height; ?>px;"></iframe>
	<?php endif; ?>
</div>