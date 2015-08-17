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
      <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
         <div class="row">
            <div class="col-sm-3">
               <div class="lft-srchon">
                  <h2>Refine Search</h2>
                  <div class="col-sm-12 ">
                     <div class="frmtital">Rental Location *</div>
                     <select class="form-control" name="city_id" id="city_id">
                        <option value="">- Select Your City -</option>
                        <?php mkdd($cities,$city_id); ?>
                     </select>
                  </div>
                  <div class="col-sm-12 ">
                     <div class="frmtital">Pick Up Date *</div>
                     <div class="input-group date">
                        <input type="text" value="<?php echo $start_date ?>" data-date-format="MM/DD/YYYY" class="form-control fld-txt12 input-lg" name="start_date" id="start_date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Pick Up Time *</div>
                     <div class="input-group time">
                        <input type="text" value="<?php echo $start_time ?>" data-date-format="hh:00 A" class="form-control fld-txt12 input-lg" name="start_time" id="start_time">
                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Drop Off Date *</div>
                     <div class="input-group date">
                        <input type="text" value="<?php echo $end_date ?>" data-date-format="MM/DD/YYYY" class="form-control fld-txt12 input-lg" name="end_date" id="end_date">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Drop Off Time *</div>
                     <div class="input-group time">
                        <input type="text" value="<?php echo $end_time ?>" data-date-format="hh:00 A" class="form-control fld-txt12 input-lg" name="end_time" id="end_time">
                        <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Vehicle Category</div>
                     <select name="cat_id" id="cat_id" class="form-control  fld-txt12 input-lg">
                        <option value="">- Select Category -</option>
                        <?php mkdd($cats,$cat_id); ?>
                     </select>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Make</div>
                     <select name="make_id" id="make_id" class="form-control fld-txt12 input-lg">
                        <option value="">- Select Make -</option>
                        <?php mkdd($makes,$make_id); ?>
                     </select>
                  </div>
                  <div class="col-sm-12">
                     <div class="frmtital">Model</div>
                     <div id="model_id_2">
                        <select name="model_id" id="model_id" class="form-control fld-txt12 input-lg">
                           <option value="">- Select Model -</option>
                        </select>
                     </div>
                  </div>
                  <div class="col-sm-12">
                     <button class="btn btn-danger btn-lg" type="submit" onclick="kk()">Refine Search</button>
                  </div>
               </div>
            </div>
            
            <!--End-col-md-3-->
            
            <div class="col-sm-9">
               <div class="listdp-bar">
                  <div class="dropdown pull-right">
                     <button id="dLabel" class="btn btn-primary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Sort By Rent&nbsp;&nbsp; <span class="caret"></span> </button>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?php echo $url.'&sort=p.daily&order=ASC' ?>">Low to High</a></li>
                        <li><a href="<?php echo $url.'&sort=p.daily&order=DESC' ?>">High to Low</a></li>
                     </ul>
                  </div>
               </div>
               <?php if ($products){ ?>
               <?php foreach ($products as $product){ ?>
               <section class="listing">
                  <div class="row">
                     <div class="col-md-3 col-sm-3 col-xs-12">
                        <div class="vehicle"> <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>"></a> <span><img src="<?php echo $product['avatar']; ?>"></span> </div>
                     </div>
                     <div class="col-sm-9">
                        <h1 class="itm-nam"> <a href="<?php echo $product['href']; ?>" class="pull-left"><?php echo $product['name']; ?></a> 
                           <!--<span class="pull-right label label-primary">Rent = <?php echo $product['rate']; ?></span>-->
                           
                           <ul class="nav nav-pills pull-right" role="tablist">
                              <li role="presentation" class="active mrg-btm0"><strong>Total Price</strong>&nbsp;<span class="badge rntbtn"><?php echo $product['rate']; ?></span><br>
                                 <small class="incld-tx">Includes all taxes and fees</small></li>
                           </ul>
                        </h1>
                        <div class="location"><i class="fa fa-map-marker"></i><?php echo $product['location']; ?> </div>
                        <p><?php echo $product['description']; ?></p>
                        <!--<img src="catalog/view/theme/cars/image/icon-number-of-adults.png" > <img src="catalog/view/theme/cars/image/icon-manual-transmission.png" > <img src="catalog/view/theme/cars/image/onrequest.gif" > <img src="catalog/view/theme/cars/image/icon-air-conditioning.png" > <img src="catalog/view/theme/cars/image/meet_the_owner_ico.png" > <img src="catalog/view/theme/cars/image/fuel_full_to_full_ico.gif" > --></div>
                     <div class="col-sm-9 pricing">
                        <div class="row">
                           <div class="col-sm-3"> <span>Daily</span>
                              <p><?php echo $product['daily']; ?></p>
                           </div>
                           <div class="col-sm-3"><span>Weekly</span>
                              <p><?php echo $product['weekly']; ?></p>
                           </div>
                           <!--<div class="col-sm-3"><p><?php echo $product['weekend']; ?></p>
                                 <span>Weekend</span></div>-->
                           <div class="col-sm-3"><span>Monthly</span>
                              <p><?php echo $product['monthly']; ?></p>
                           </div>
                        </div>
                        <div>
                           <button class="requestBtn" onclick="cart.add(<?php echo $product['product_id'] ?>)">Request Now</button>
                           <a class="requestBtn" href="<?php echo $product['href']; ?>">View Details</a> </div>
                     </div>
                  </div>
               </section>
               <?php } ?>
               <?php } else { ?>
               <div class="cstmset bg-info"> No car Available </div>
               <?php } ?>
               <div class="row mrg-btm10">
                  <div class="col-sm-6 pgn-ul"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 rslt-rit"><?php echo $results; ?></div>
               </div>
            </div>
         </div>
      </div>
      <?php echo $content_bottom; ?></div>
   <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 
<script type="text/javascript">
function kk(){
	var make_id = $('#make_id').val();
	var model_id = $('#model_id').val();
	var cat_id = $('#cat_id').val();
	var city_id = $('#city_id').val();
	
	var start_date = $('#start_date').val();
	var start_time = $('#start_time').val();
	var end_date = $('#end_date').val();
	var end_time = $('#end_time').val();
  
	var url = '';
	
	if (make_id!=''){
		var url = url.concat('&make_id=' + make_id);
	}
  
	if (model_id!=''){
		var url = url.concat('&model_id=' + model_id);
	}
  
	if (cat_id!=''){
		var url = url.concat('&cat_id=' + cat_id);
	}
	
	if (city_id!=''){
		var url = url.concat('&city_id=' + city_id);
	}
	
	if (start_date!=''){
		var url = url.concat('&start_date=' + start_date);
	}
	
	if (start_time!=''){
		var url = url.concat('&start_time=' + start_time);
	}
  
	if (end_date!=''){
		var url = url.concat('&end_date=' + end_date);
	}
  
	if (end_time!=''){
		var url = url.concat('&end_time=' + end_time);
	}
	
	if (end_time!=''){
		var url = url.concat('&end_time=' + end_time);
	}
	
	if (start_date != '' && start_time != '' && end_date != '' && end_time != ''){
	var sdarr = start_date.split("/");
	var new_start_date = sdarr[2] + '-' + sdarr[0] + '-' + sdarr[1];
	var edarr = end_date.split("/");
	var new_end_date = edarr[2] + '-' + edarr[0] + '-' + edarr[1];
	var new_start_time = ConvertTimeformat("24", start_time);
	var new_end_time = ConvertTimeformat("24", end_time);
	
	if (new_start_date + ' ' + new_start_time >= new_end_date + ' ' + new_end_time){
		alert('End Time should be greater than Start Time.');
		return false;
	}
	}
	
	if (city_id == '' || start_date == '' || start_time == '' || end_date == '' || end_time == ''){
		window.location.href="index.php?route=common/home" + url + "&error=1"
	} else {
		window.location.href="index.php?route=product/listing" + url;
	}
	}
</script> 
<script language="javascript">
function ConvertTimeformat(format, str) {
    var time = str;
    var hours = Number(time.match(/^(\d+)/)[1]);
    var minutes = Number(time.match(/:(\d+)/)[1]);
    var AMPM = time.match(/\s(.*)$/)[1];
    if (AMPM == "PM" && hours < 12) hours = hours + 12;
    if (AMPM == "AM" && hours == 12) hours = hours - 12;
    var sHours = hours.toString();
    var sMinutes = minutes.toString();
    if (hours < 10) sHours = "0" + sHours;
    if (minutes < 10) sMinutes = "0" + sMinutes;
    return(sHours + ":" + sMinutes);
}
</script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,
    autoclose: true
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script> 
<script type="text/javascript">
$('select[name=\'make_id\']').on('change', function() { 
if ($("#make_id").val()!=""){
	$.ajax({
		url: 'index.php?route=module/slideshow/autocomplete&token=<?php echo $token ?>&make_id=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data)  
      {$('#model_id_2').html('<select name="model_id" id="model_id" class="form-control fld-txt12 input-lg"><option value="">- Select Model -</option>' + data + '</select>');}
	});
} else {
	$('#model_id_2').html('<select name="model_id" id="model_id" class="form-control fld-txt12 input-lg"><option value="">- Select Model -</option></select>');
}
});

</script> 
<script type="text/javascript"><!--
  
  var make_id = <?php echo $make_id ?>;
  var model_id = <?php echo $model_id ?>;
  
  if (make_id){
	  $.ajax({
		url: 'index.php?route=catalog/category/customautocomplete&filter_cat=' + make_id + '&filter_model=' + model_id,
		type: 'POST',
        dataType: 'html',
        success : function(data) 
      {$('#model_id_2').html('<select name="model_id" id="model_id" class="form-control fld-txt12 input-lg"><option value="">- Select Model -</option>' + data + '</select>');}
	});
  }
</script> 
<script type="text/javascript">
$('.arro-dv').click(function() {
	    $('.listsrch-bar').slideToggle('slow');
	    return false;
	});
	$('.arro-dv').click(function(e){
    e.preventDefault();
    $(this).find('i').toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
});
</script>