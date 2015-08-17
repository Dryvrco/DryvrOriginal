<!--<div class="topfix"></div>-->
<footer>
  <div class="container">
   <!-- <div class="row"> -->
      
    <div class="row">  
       <h5>Cities</h5> 
       <ul class="list-unstyled">
       <?php foreach($car_cities as $city) {  ?> 
       <div class="col-sm-3">
       <li><a href="<?php echo $city['href']; ?>"><?php echo $city['name']; ?></a></li>
       </div>
       <?php }?>
       </ul>
         </div>  
          
    <hr />
     
     <div class="row">  
       <h5>Rent anything from an A3 to a Z4..</h5> 
       <ul class="list-unstyled">
       <?php foreach($car_makes as $make) {  ?> 
       <div class="col-sm-3">
       <li><a href="<?php echo $make['href']; ?>"><?php echo $make['name']; ?></a></li>  
       </div>
       <?php }?>
       </ul>
         </div>  
          
    <hr />
     
     
     <div class="row"> 
      
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <!--<h5><?php echo $text_information; ?></h5>-->
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      
      <div class="col-sm-3">
       <!-- <h5><?php echo $text_service; ?></h5>-->
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      
      <div class="col-sm-3">
        <!--<h5><?php echo $text_extra; ?></h5>-->
        <ul class="list-unstyled">
          <!--<li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>-->
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <!--<h5><?php echo $text_account; ?></h5>-->
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!--<footer>
  <div class="container">
    <div class="row">
            <div class="col-sm-3">
        
        <ul class="list-unstyled">
                    <li><a href="">Rent a Car</a></li>
                    <li><a href="">List Your Car</a></li>
                    <li><a href="">Get the iPhone App</a></li>
                    <li><a href="">Get the Android App</a></li>
                  </ul>
      </div>
            <div class="col-sm-3">
        
        <ul class="list-unstyled">
          <li><a href="">Free Airport Parking</a></li>
          <li><a href="">How RelayRides Works</a></li>
          <li><a href="">Trust & Safety</a></li>
          <li><a href="">Sign Up</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
      
        <ul class="list-unstyled">
          <li><a href="">General FAQs</a></li>
          <li><a href="">Owner Help</a></li>
          <li><a href="">Renter Help</a></li>
          <li><a href="">Policies</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
       
        <ul class="list-unstyled">
          <li><a href="">Blog</a></li>
          <li><a href="">Facebook</a></li>
          <li><a href="">Twitter</a></li>
          <li><a href="">Google+</a></li>
        </ul>
      </div>
    </div>
    </div>
</footer>-->

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//--> 

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>