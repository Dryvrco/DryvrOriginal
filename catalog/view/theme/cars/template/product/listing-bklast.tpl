<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="container">
        <div class="row">
          <div class="col-md-3 col-sm-3 col-xs-12">
            <div class="widget margin-top-10 padding-top-10">
              <div class="widget_title">Search Filters</div>
              <div class="widget_body">
                <ul class="left_nav">
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> transmission</a>
                    <div class="sub_left_nav" style="display:block">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Booking Type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Car type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> transmission</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Booking Type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Car type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> transmission</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Booking Type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Car type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> transmission</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                  <li><a href="javascript:void(0)"><i class="fa fa-arrow-circle-right"></i> Booking Type</a>
                    <div class="sub_left_nav">
                      <ul>
                        <li>
                          <input type="radio" id="all" name="all">
                          <label for="all"> All</label>
                        </li>
                        <li>
                          <input type="radio" id="manual">
                          <label for="manual"> Manual</label>
                        </li>
                        <li>
                          <input type="radio" id="automatic">
                          <label for="automatic"> Automatic</label>
                        </li>
                      </ul>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
            <div class="widget">
              <div class="widget_title">Available Vehicle</div>
              <div class="box"> <img src="images/my-car4.jpg">
                <h1>Lorem ipsum doller sit</h1>
                <p>Lorem ipsum doller site emer unit lorm your doller sit each doller mert sit</p>
                <a href="#"><i class="fa fa-search"></i> Search more!</a> </div>
              <div class="box"> <img src="images/my-car4.jpg">
                <h1>Lorem ipsum doller sit</h1>
                <p>Lorem ipsum doller site emer unit lorm your doller sit each doller mert sit</p>
                <a href="#"><i class="fa fa-search"></i> Search more!</a> </div>
            </div>
          </div>
          <div class="col-md-9 col-sm-9 col-xs-12">
            <section class="padding-top-20"> 
              <div class="src-bar">
      <div class="col-sm-6 ">
        <div class="frmtital">Where do you want to hire?</div>
        <input name="city" id="city" value="<?php echo $city_name ?>" type="text" class="form-control input-lg" placeholder="Enter City" />
        <input name="city_id" value="<?php echo $city_id ?>" id="city_id" type="hidden" />
      </div>
      <div class="clear"></div>
      <div class="col-sm-3 ">
        <div class="frmtital">Pick Up Date</div>
        <div class="input-group date">
          <input type="text" value="<?php echo $start_date ?>" data-date-format="YYYY-MM-DD" class="form-control fld-txt12 input-lg" name="start_date" id="start_date">
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="frmtital">Pick Up Time</div>
        <div class="input-group time">
          <input type="text" value="<?php echo $start_time ?>" data-date-format="hh:mm A" class="form-control fld-txt12 input-lg" name="start_time" id="start_time">
          <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
        </div>
      </div>
      <div class="col-sm-3 ">
        <div class="frmtital">Drop Off Date</div>
        <div class="input-group date">
          <input type="text" value="<?php echo $end_date ?>" data-date-format="YYYY-MM-DD" class="form-control fld-txt12 input-lg" name="end_date" id="end_date">
          <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="frmtital">Drop Off Time</div>
        <div class="input-group time">
          <input type="text" value="<?php echo $end_time ?>" data-date-format="hh:mm A" class="form-control fld-txt12 input-lg" name="end_time" id="end_time">
          <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
        </div>
      </div>
        <div class="col-sm-4">
          <div class="frmtital">Make</div>
          <select name="make_id" id="make_id" class="form-control fld-txt12 input-lg">
            <option value="">- Select Make -</option>
            <?php mkdd($makes,$make_id); ?>
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
        <div class="col-sm-4">
          <div class="frmtital">Rental Car Company</div>
          <select name="seller_id" id="seller_id" class="form-control  fld-txt12 input-lg">
            <option value="">- Select Agency -</option>
            <?php mkdd($agencies,$seller_id); ?>
          </select>
        </div>
        <div class="clear">&nbsp;</div>
      <div class="clear"></div>
      <div class="col-sm-3">
        <button class="btn btn-danger btn-lg" type="submit" onclick="kk()">Find Cars</button>
      </div>
    </div>
            </section>
            <?php foreach ($products as $product){ ?>
            <section class="listing">
              <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <div class="vehicle"> <img src="<?php echo $product['thumb']; ?>"> <span><img src="<?php echo $product['avatar']; ?>"></span> </div>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-12">
                  <h1><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h1>
                  <ul>
                    <li><i class="fa fa-angle-double-right"></i> Economy 2/5 Door - Manual</li>
                    <li><i class="fa fa-angle-double-right"></i> leeds, headingley, 1.71 miles from your chosen location</li>
                    <li> <img src="catalog/view/theme/cars/image/icon-number-of-adults.png" > <img src="catalog/view/theme/cars/image/icon-manual-transmission.png" > <img src="catalog/view/theme/cars/image/onrequest.gif" > <img src="catalog/view/theme/cars/image/icon-air-conditioning.png" > <img src="catalog/view/theme/cars/image/meet_the_owner_ico.png" > <img src="catalog/view/theme/cars/image/fuel_full_to_full_ico.gif" > </li>
                  </ul>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <table width="100%" border="0" cellpadding="0" cellspacing="0" class="pricing">
                    <tr>
                      <td width="25%"><p><?php echo $product['daily']; ?></p>
                        <span>Daily</span></td>
                      <td width="25%"><p><?php echo $product['weekly']; ?></p>
                        <span>Weekly</span></td>
                      <td width="25%"><p><?php echo $product['weekend']; ?></p>
                        <span>Weekend</span></td>
                      <td width="25%"><p><?php echo $product['monthly']; ?></p>
                        <span>Monthly</span></td>
                    </tr>
                  </table>
                  <div class="margin-top-30 text-right"><button class="requestBtn margin-top-15" onclick="cart.add(<?php echo $product['product_id'] ?>)">Request Now</button></div>
                </div>
              </div>
            </section>
            <?php } ?>
            <div class="text-right"><?php echo $pagination; ?></div>
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
	var seller_id = $('#seller_id').val();
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
  
	if (seller_id!=''){
		var url = url.concat('&seller_id=' + seller_id);
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
	
	if (city_id == '' || start_date == '' || start_time == '' || end_date == '' || end_time == ''){
		alert('Please fill required fields.')
	} else {
		window.location.href="index.php?route=product/listing" + url;
	}
	}
</script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script>
<script type="text/javascript"><!--
$('input[name=\'city\']').autocomplete({
	'source': function(request, response) {
		
		$.ajax({
			url: 'index.php?route=module/slideshow/cityautocomplete&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['city_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'city\']').val(item['label']);
		$('input[name=\'city_id\']').val(item['value']);
	}
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
		url: 'index.php?route=catalog/category/customautocomplete&token=<?php echo $token ?>&filter_cat=' + make_id + '&filter_model=' + model_id,
		type: 'POST',
        dataType: 'html',
        success : function(data) 
      {$('#model_id_2').html('<select name="model_id" id="model_id" class="form-control fld-txt12 input-lg"><option value="">- Select Model -</option>' + data + '</select>');}
	});
  }
</script>
<script type="text/javascript"><!--
$("#city").click(function(event){
   event.preventDefault();
   document.getElementById("city_id").value= "";
   document.getElementById("city").value= "";
});
</script>