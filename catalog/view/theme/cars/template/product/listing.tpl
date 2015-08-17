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
            <div class="col-sm-3 mrg-btm10">
       <div class="shobtn-onmb">Refine Search<i class="fa fa-chevron-circle-down"></i></div>
               <div class="lft-srchon hidedv-onmb">
                  <h2>Refine Search</h2>
                  <div class="col-sm-12 ">
                     <div class="frmtital">Rental Location *</div>
                     <select class="form-control" name="city_id" id="city_id">
                        <option value="">- Select Your City -</option>
                        <?php mkdd($cities,$city_id); ?>
                     </select>
                  </div>
                  <div class="col-sm-12 ">
                     <div class="frmtital">Rental Area</div>
                     <select class="form-control" name="area_id" id="area_id">
                        <option value="">- Select Your Area -</option>
                        <?php mkdd($areas,$area_id); ?>
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
                  <div class="col-sm-12">&nbsp;</div>
                  <div class="col-sm-12">
                     <?php foreach ($allfilters as $filter){ ?>
                     <div class="frmtital" style="margin-bottom:-10px"><?php echo $filter['group_name'] ?></div>
                     <?php foreach ($filter['filters'] as $filternames){ ?>
                     <div class="checkbox">
                        <label>
                           <input type="checkbox" name="filters[]" value="<?php echo $filternames['filter_id'] ?>" <?php if (in_array($filternames['filter_id'], $get_filters)) echo 'checked="checked"'; ?> />
                           <?php echo $filternames['name'] ?> </label>
                     </div>
                     <?php } ?>
                     <?php } ?>
                  </div>
                  <div class="col-sm-12">
                     <button class="btn btn-danger btn-lg" type="submit" onclick="kk()">Refine Search</button>
                  </div>
               </div>
            </div>
            
            <!--End-col-md-3-->
            
            <div class="col-sm-9">
               <div class="listdp-bar">
                  <div class="col-md-1 ">
                     <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
                  </div>
                  <div class="col-md-2">
                     <select id="input-limit" class="form-control" onchange="location = this.value;">
                        <?php foreach ($limits as $limits) { ?>
                        <?php if ($limits['value'] == $limit) { ?>
                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                     </select>
                  </div>
                  <div class="dropdown pull-right onmbl">
                     &nbsp;&nbsp;<button id="dLabel" class="btn btn-primary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Sort By Rent&nbsp;&nbsp; <span class="caret"></span> </button>
                     <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?php echo $url.'&sort=p.daily&order=ASC' ?>">Low to High</a></li>
                        <li><a href="<?php echo $url.'&sort=p.daily&order=DESC' ?>">High to Low</a></li>
                     </ul>
                  </div>
                  <div class="pull-right onmb-set">
                     <div class="btn-group">
                        <button type="button" id="list-view-1" class="btn btn-default" data-toggle="tooltip" title="List View"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view-1" class="btn btn-default" data-toggle="tooltip" title="Grid View"><i class="fa fa-th"></i></button>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php if ($products){ ?>
               <?php foreach ($products as $product){ ?>
               <div class="product-layout product-list">
                  <section class="listing height">
                     <div class="row">
                        <div class="col-sm-3 tog3to12">
                           <div class="vehicle"> <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>"></a> <span class="dscrp"><img src="<?php echo $product['avatar']; ?>"></span> </div>
                        </div>
                        <div class="col-sm-9 tog9to12">
                           <div class="col-sm-6 tog6">
                              <h1 class="itm-nam tog font"> <a href="<?php echo $product['href']; ?>" class="pl"><?php echo $product['name']; ?></a></h1>
                           </div>
                           <div class="col-sm-6 tog6">
                              <ul class="nav nav-pills pull-right pr" role="tablist">
                                 <li role="presentation" class="active mrg-btm0 tog width"><strong>Total Rent</strong>&nbsp;<span class="badge rntbtn"><?php echo $product['rate']; ?></span><br>
                                    <small class="incld-tx dscrp">Includes all taxes and fees</small></li>
                              </ul>
                           </div>
                           <div class="col-sm-12">
                              <div class="location dscrp"><i class="fa fa-map-marker"></i><?php echo $product['location']; ?> </div>
                           </div>
                           <div class="col-sm-12 dscrp">
                              <p><?php echo $product['description']; ?></p>
                           </div>
                           <div class="col-sm-12 pricing dscrp">
                              <div class="row">
                                 <div class="col-sm-3"> <span>Daily</span>
                                    <p><?php echo $product['daily']; ?></p>
                                 </div>
                                 <div class="col-sm-3"><span>Weekly</span>
                                    <p><?php echo $product['weekly']; ?></p>
                                 </div>
                                 <div class="col-sm-3"><span>Monthly</span>
                                    <p><?php echo $product['monthly']; ?></p>
                                 </div>
                              </div>
                           </div>
                           <div class="col-sm-12 tog">
                           	<?php if($product['rate']!='$0.00'){ ?>
                              <button class="togbtn" onclick="cart.add(<?php echo $product['product_id'] ?>)">Request Now</button>
                              <?php } ?>
                              <button class="togbtn" onclick="window.location.href='<?php echo $product['href']; ?>'">View Details</button>
                           </div>
                        </div>
                     </div>
                  </section>
               </div>
               <?php } ?>
               <?php } else { ?>
               <div class="cstmset bg-info col-sm-12"> No car Available </div>
               <?php } ?>
               <div class="clear">&nbsp;</div>
               <div class="row mrg-btm10">
                  <div class="col-sm-6 pgn-ul pull-left"><?php echo $pagination; ?></div>
                  <div class="col-sm-6 rslt-rit pull-right"><?php echo $results; ?></div>
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
	var area_id = $('#area_id').val();
	
	var start_date = $('#start_date').val();
	var start_time = $('#start_time').val();
	var end_date = $('#end_date').val();
	var end_time = $('#end_time').val();
	
	

	var checkboxes = document.getElementsByName('filters[]');
	var vals = "";
	for (var i=0, n=checkboxes.length;i<n;i++) {
  		if (checkboxes[i].checked) 
  		{
  			vals += ","+checkboxes[i].value;
  		}
	}

if (vals) vals = vals.substring(1);
  
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
	
	if (area_id!=''){
		var url = url.concat('&area_id=' + area_id);
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
	
	if (vals!=''){
		var url = url.concat('&filters=' + vals);
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
	
	
		window.location.href="index.php?route=product/listing" + url + '<?php echo $sorturl; ?>';
	
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
$(".closeit").click(function(event){
   $(document).find(".bootstrap-datetimepicker-widget").hide();
});

$('.arro-dv').click(function() {
	    $('.listsrch-bar').slideToggle('slow');
	    return false;
	});
	$('.arro-dv').click(function(e){
    e.preventDefault();
    $(this).find('i').toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
});

$(document).on("change", "#city_id", function(){
	$.ajax({
		url: 'index.php?route=localisation/city/getAreas&token=<?php echo $token; ?>&city_id=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data)  
      {$('#areas').html('<select class="form-control" name="area_id" id="area_id"><option value="">- Select Your Area -</option>' + data + '</select>');} 
	});
});
</script>

<script type="text/javascript">
$('.shobtn-onmb').click(function() {
	    $('.hidedv-onmb').slideToggle('slow');
	    return false;
	});
	$('.shobtn-onmb').click(function(e){
    e.preventDefault();
    $(this).find('i').toggleClass('fa-chevron-circle-down fa-chevron-circle-up');
});
</script>