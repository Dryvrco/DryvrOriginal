</div>
</div>
</div>

<div id="banner-container">
  <div class="container srch-container">
    <div class="src-bar">
      <div class="col-sm-6 <?php if (!isset($this->request->get['city_id'])) { echo $error_class; } ?>">
        <div class="frmtital">Rental City <span class="<?php echo $class_red; ?>"><?php echo $error_city.$star; ?></span></div>
        <select class="form-control" name="city_id" id="city_id">
        	<option value="">- Select Your City -</option>
            <?php mkdd($cities,$city_id); ?>
        </select>
      </div>
      <!--<div class="col-sm-6">
        <div class="frmtital">Rental Area</span></div>
        <div id="areas">
        <select class="form-control" name="area_id" id="area_id">
        	<option value="">- Select Your Area -</option>
            <?php mkdd($areas,$area_id); ?>
        </select>
        </div>
      </div>-->
      <div class="clear"></div>
      <div class="col-sm-3 <?php if (!isset($this->request->get['start_date'])) { echo $error_class; } ?>">
        <div class="frmtital">Pick Up Date <span class="<?php echo $class_red; ?>"><?php echo $error_start_date.$star; ?></span></div>
        <div class="input-group date">
          <input type="text" value="<?php echo $start_date; ?>" data-date-format="MM/DD/YYYY" class="form-control fld-txt12 input-lg" name="start_date" id="start_date">
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
      <div class="col-sm-3 <?php if(!isset($this->request->get['start_time'])) { echo $error_class; } ?>">
        <div class="frmtital">Pick Up Time <span class="<?php echo $class_red; ?>"><?php echo $error_start_time.$star; ?></span></div>
        <div class="input-group time">
          <input type="text" value="<?php echo $start_time; ?>" data-date-format="hh:00 A" class="form-control fld-txt12 input-lg" name="start_time" id="start_time">
          <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
        </div>
      </div>
      <div class="col-sm-3 <?php if(!isset($this->request->get['end_date'])) { echo $error_class; } ?>">
        <div class="frmtital">Drop Off Date <span class="<?php echo $class_red; ?>"><?php echo $error_end_date.$star; ?></span></div>
        <div class="input-group date">
          <input type="text" value="<?php echo $end_date; ?>" data-date-format="MM/DD/YYYY" class="form-control fld-txt12 input-lg" name="end_date" id="end_date">
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
      <div class="col-sm-3 <?php if(!isset($this->request->get['end_time'])) { echo $error_class; } ?>">
        <div class="frmtital">Drop Off Time <span class="<?php echo $class_red; ?>"><?php echo $error_end_time.$star; ?></span></div>
        <div class="input-group time">
          <input type="text" value="<?php echo $end_time; ?>" data-date-format="hh:00 A" class="form-control fld-txt12 input-lg" name="end_time" id="end_time">
          <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
        </div>
      </div>
      <div id="more" class="morsrch">Show Options &nbsp;<i class="fa fa-chevron-down"></i></div>
      <div class="togdiv" style="display:none">
      	<div class="col-sm-4">
          <div class="frmtital">Vehicle Category</div>
          <select name="cat_id" id="cat_id" class="form-control  fld-txt12 input-lg">
            <option value="">- Select Category -</option>
            <?php mkdd($cats); ?>
          </select>
        </div>
        <div class="col-sm-4">
          <div class="frmtital">Make</div>
          <select name="make_id" id="make_id" class="form-control fld-txt12 input-lg">
            <option value="">- Select Make -</option>
            <?php mkdd($makes); ?>
          </select>
        </div>
        <div class="col-sm-4">
          <div class="frmtital">Model</div>
          <div id="model_id_2">
            <select name="model_id" id="model_id" class="form-control fld-txt12 input-lg">
              <option value="">- Select Model -</option>
            </select>
          </div>
        </div>
        <div class="clear">&nbsp;</div>
      </div>
      <div class="clear"></div>
      <div class="col-sm-3">
        <button class="btn btn-danger btn-lg" type="submit" onclick="kk()">Find Cars</button>
      </div>
    </div>
  </div>
</div>
<div class="container">
<div class="row">
<div id="content">
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

$('#more').click(function() {
	    $('.togdiv').slideToggle('slow');
	    return false;
	});
	$('#more').click(function(e){
    e.preventDefault();
    $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
});
</script> 
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
	
	/*if (area_id!=''){
		var url = url.concat('&area_id=' + area_id);
	}*/
	
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
	pickDate: false,
});
$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
$("#city").click(function(event){
   event.preventDefault();
   document.getElementById("city_id").value= "";
   document.getElementById("city").value= "";
});
$(".closeit").click(function(event){
   $(document).find(".bootstrap-datetimepicker-widget").hide();
});
/*$(document).on("change", "#city_id", function(){
	$.ajax({
		url: 'index.php?route=localisation/city/getAreas&token=<?php echo $token; ?>&city_id=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data)  
      {$('#areas').html('<select class="form-control" name="area_id" id="area_id"><option value="">- Select Your Area -</option>' + data + '</select>');} 
	});
});*/
</script>