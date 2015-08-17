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


			<script src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
	
			<?php 
if ($hideadl==0) {
$_SESSION['advurl']="http" . (($_SERVER['SERVER_PORT']==443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

echo $customcss;


			}	?>
<?php if ($hideadl==1) { ?>

<?php $zone_id=0; if ((isset($_SESSION['fieldrequired'])) && (count($_SESSION['fieldrequired'])>=1)) { ?>





<link href="catalog/view/javascript/jquery/fancybox/bootstrap-combined.min.css" rel="stylesheet">

<script src="catalog/view/javascript/jquery/fancybox/bootstrafp.min.js"></script>
<div id="thanks"><a id="subscribepopup" href="#form-content"  style="display:none"></a></div>
	<!-- model content -->	
	
	<div id="form-contentb" class="modal fade in" data-keyboard="false" data-backdrop="static" style="bottom: auto !important; display: none; ">
	        <div class="modal-header">
	              
	              <b><?php echo $popupheading; ?></b>
	              
	        </div>
		<div>
		<form id="address_field" class="contact">
			
			<fieldset>
			<div id="thanks2"></div>
		         <div class="modal-body">
		        	 <ul class="nav nav-list">
					 <?php foreach ($_SESSION['fieldrequired'] as $field) { ?>
					  <?php if ($field=='firstname') { ?>
				<li class="nav-header">*<?php echo $entry_firstname; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
                
                 <?php if ($field=='lastname') { ?>
				<li class="nav-header">*<?php echo $entry_lastname; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
				
					  <?php if ($field=='fax') { ?>
				<li class="nav-header"><?php echo $entry_fax; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
				
						  <?php if ($field=='telephone') { ?>
				<li class="nav-header">*<?php echo $entry_telephone; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
				
						  <?php if ($field=='company') { ?>
				<li class="nav-header"><?php echo $entry_company; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
				
				
				
				
				
						  <?php if ($field=='postcode') { ?>
				<li class="nav-header"><?php echo $entry_postcode; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				
				<?php if ($error_postcode) { ?>
            <span id="postcode-required" class="error"><?php echo $error_postcode; ?></span>
            <?php } ?>
			<?php } ?>
				
						  <?php if ($field=='city') { ?>
				<li class="nav-header">*<?php echo $entry_city; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
					 
					  <?php if ($field=='address_1') { ?>
				<li class="nav-header">*<?php echo $entry_address_1; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
				
				 <?php if ($field=='address_2') { ?>
				 
				<li class="nav-header"><?php echo $entry_address_2; ?></li>
				<li><input class="input-xlarge" value="" type="text" name="<?php echo $field; ?>" id="<?php echo $field; ?>"></li>
				<?php } ?>
					 
					 <?php if ($field=='country_id') { ?>
					 <?php $usecountry='1'; ?>
					 <li class="nav-header">*<?php echo $entry_country; ?></li>
					 <li>
					 <select name="country_id" id="country_id" >
              <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?>
             
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
             
            </select>
            <?php if ($error_country) { ?>
            <span id ="country-required"  class="error"><?php echo $error_country; ?></span>
            <?php } ?>					
					 </li>
					  <?php } ?>  <?php if ($field=='zone_id') { ?>
					 
					 
					 <li class="nav-header">*<?php echo $entry_zone; ?></li>
					 <li>
					<select name="zone_id" id="zone_id">
            </select>
            <?php if ($error_zone) { ?>
            <span id ="zone-required" class="error"><?php echo $error_zone; ?></span>
            <?php } ?>
					 </li>
					 <?php } ?> 
					
				
				
				<?php } ?>
				 

				</ul> 
		        </div>
			</fieldset>
			</form>
		</div>
	     <div class="modal-footer">
	         <button class="btn btn-success" id="submit">submit</button>
	        
  		</div>
	</div>

    <script>
 $(function() {
//twitter bootstrap script
	$("button#submit").click(function(){
	        $.ajax({
    		type: "POST",
		url: "index.php?route=module/advancedlogin/address",
		data: $("#address_field").serialize(),
	
        	success: function(msg){
			  $('#thanks2').before('<div class="alert ' + msg.type + '">' + msg.message + '</div>');
 	                
 		       	$("."+msg.type).delay(5000).slideUp(400, function(){if($(this).hasClass('alert-success')){ $("#form-contentb").hide();	}});
 		        },
		error: function(){
			alert("failure");
			}
      		});
	});
});
</script>
<script type="text/javascript">jQuery(document).ready(function() {

    setTimeout( function() {$("#subscribepopup").trigger('click'); $( "#form-contentb" ).show(); },0);
	
   }
   );  
   </script>
   	<?php if ($usecountry=='1') { ?>
   <script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/account/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},		
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			
			} else {
				$('#postcode-required').hide();
			
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone']) {
			$('#country-required').show();
			
			$('#zone-required').show();
			
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
					
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
						$('#zone-required').hide();
						
	    			}
					
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
					
					
						
				}
			} else {
			
				$('#zone-required').show();
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
		
			$('select[name=\'zone_id\']').html(html);
			
		
			$('#country-required').hide();
			$('#zone-required').hide();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
  <?php } ?> 
   <?php } ?>
      <?php } ?>
			
			
</body></html>