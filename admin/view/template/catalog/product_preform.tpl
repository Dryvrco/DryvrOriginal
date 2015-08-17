<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <div class="form-group ">
            <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_make; ?></label>
            <div class="col-sm-10">
              <select name="make_id" class="form-control" id="myselect">
                <option value="">- Select Make -</option>
                <?php foreach ($allcats as $cats){ ?>
                <?php if ($cats['category_id']==$make_id)
                  	{
                  		$selected = 'selected';
                 	} else {
                  		$selected = '';
                  	} ?>
                <option value="<?php echo $cats['category_id'] ?>" <?php echo $selected; ?>><?php echo $cats['name'] ?></option>
                <?php } ?>
              </select>
              
            </div>
          </div>
          <div class="form-group">
            <label for="input-category" class="col-sm-2 control-label"><?php echo $entry_model; ?> </label>
            <div id="models" class="col-sm-10">
              <select name="model_id" tabindex="2" class="form-control fld">
                <option selected="selected" value="0">- Select Model -</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label  class="col-sm-2 control-label">&nbsp;</label>
            <div id="models" class="col-sm-10">
              <button type="submit" class="btn btn-primary">Submit</button> 
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="sample">&nbsp;</div>
  <script type="text/javascript"><!--
  
 // var make_id = <?php echo $make_id ?>;
 // var model_id = <?php echo $model_id ?>;
  
 // if (make_id){
//	  $.ajax({
//		url: 'index.php?route=catalog/category/customautocomplete&token=<?php echo $token ?>&filter_cat=' + make_id + '&filter_model=' + model_id,
//		type: 'POST',
 //       dataType: 'html',
  //      success : function(data) 
  //    {$('#models').html('<select class="form-control fld" tabindex="2" name="model_id"><option value="0">- Select Model -</option>' + data + '</select>');}
//	});
 // }
  
  $('select[name=\'make_id\']').on('change', function() {
   $.ajax({
		url: 'index.php?route=catalog/category/customautocomplete&token=<?php echo $token ?>&filter_cat=' + this.value,
		type: 'POST',
        dataType: 'html',
        success : function(data) 
      {$('#models').html('<select class="form-control fld" tabindex="2" name="model_id"><option value="0">- Select Model -</option>' + data + '</select>');}
	});
}); 

//--></script> 
</div>
<?php echo $footer; ?> 