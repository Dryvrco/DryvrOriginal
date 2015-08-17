<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-zone" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-zone" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" required="required" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
            <div class="col-sm-10">
              <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" required="required" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
            <div class="col-sm-10">
              <select name="country_id" id="input-country" class="form-control">
              <option value="">--Select Country--</option> 
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-country">Zone</label>
            <div class="col-sm-10" id="zones">
            	<?php if ($zone_select){ ?>
              <select name="zone_id" id="zone_id" class="form-control">
               <?php mkdd($zones, $zone_select); ?>
              </select>
              <?php } else { ?>
              <select name="zone_id" id="zone_id" class="form-control">
              	<option>-- Select Zone --</option>
              </select>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-country">City</label>
            <div class="col-sm-10" id="cities">
            <?php if ($city_select){ ?>
              <select name="city_id" id="city_id" class="form-control" required="required">
               <?php mkdd($cities, $city_select); ?>
              </select>
              <?php } else { ?>
              <select name="zone_id" id="zone_id" class="form-control" required="required">
              	<option>-- Select City --</option>
              </select>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control" required="required">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--

$('select[name=\'country_id\']').on('change', function() {  
	$.ajax({
		url: 'index.php?route=localisation/city/getZones&token=<?php echo $token; ?>&country_id=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data)  
      {
		  $('#zones').html('<select class="form-control" name="zone_id" id="zone_id"><option value="">-- Select Zone --</option>' + data + '</select>');
		  $('#cities').html('<select class="form-control" name="city_id" id="city_id" required="required"><option value="">-- Select City --</option></select>');
		  } 
	});
});

$(document).on("change", "#zone_id", function(){
	$.ajax({
		url: 'index.php?route=localisation/city/getCities&token=<?php echo $token; ?>&zone_id=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data)  
      {$('#cities').html('<select class="form-control" name="city_id" id="city_id" required="required"><option value="">-- Select City --</option>' + data + '</select>');} 
	});
});

//--></script>
