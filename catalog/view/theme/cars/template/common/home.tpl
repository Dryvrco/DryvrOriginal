<?php echo $header; ?>

<div class="container">
   <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
      <?php } ?>
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
</div>
<div class="ftured-container">
   <div class="container">
      <h1>Featured Agencies</h1>
      <div class="row" id="carousel">
         <?php foreach($agencies as $agency) { ?>
         <div class="row">
            <div class="img-box"> <a href="<?php echo $agency['href']; ?>"><img src="<?php echo $agency['avatar']; ?>" class="img-responsive"> </a>
            </div>
            <div class="ownr-tital" style="color:#000 !important; font-weight:bold !important;"><?php echo $agency['nickname'];  ?></div>
         </div>
         <?php } ?>
      </div>
   </div>
   
   <div class="container">
      <h1>Featured cars <!--<span class="v-all"><a href="#"><i class="fa fa-search"></i>&nbsp;View All</a> </span>--></h1>
      <div class="row">
         <?php foreach($agencycars as $agencycar) { ?>
         <div class="col-md-3 setmrg">
            <div class="img-box"> <a href="<?php echo $agencycar['href']; ?>"><img src="<?php echo $agencycar['image']; ?>" class="img-responsive"></a> 
               <!--  <div class="toptag">$22 <span>per day</span></div>  --> 
            </div>
            <div class="ownr-tital"><?php echo $agencycar['name']; ?></div>
         </div>
         <?php } ?>
      </div>
   </div>
</div>
<div class="callus-container">
   <div class="container">
      <h1>For Additional Assistance: <a href="mailto:concierge@dryvr.co">Email Us</a> at concierge@dryvr.co</h1>
      
      <!--<div class="col-sm-6 txt-rit1 txt-cntr"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;Monday-Friday 8.30am to 6.30pm</div>

    <div class="col-sm-6 txt-cntr"><i class="fa fa-clock-o"></i>&nbsp;&nbsp;Monday-Friday 8.30am to 6.30pm</div>

--> </div>
</div>
<div class="put-container">
   <div class="container">
      <h1>How it works</h1>
      <div class="col-sm-6">
         <h3>Join the Dryvr Community</h3>
         <p class="large">Register today and never wait for a rental car again.  We handle the transaction securely and seamlessly. Enter your information and start renting awesome cars.</p>
         <div class="put-btn">
            <a class="btn btn-ylow" href="index.php?route=account/register">Register for free</a>
         </div>
      </div>
      <div class="col-sm-6">
         <h3>For Agencies</h3>
         <p class="large">Want to rent more cars? </p>
         <!--<p class="small"><span class="reference">*</span> Currently available at SFO and LAX</p>
-->
         <div class="put-btn">
            <a class="btn btn-ylow" href="http://partner.dryvr.co/partner-lp/" target="_blank"> Learn More</a>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript"><!--
$('#carousel').owlCarousel({
	items: 4,
	autoPlay: 3000,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	pagination: true
});
--></script>
<?php echo $footer; ?>