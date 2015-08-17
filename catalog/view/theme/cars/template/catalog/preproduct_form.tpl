<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    
  <div id="content" class="<?php echo $class ?> padding-top-20">
  
  <div class="container-fluid">
        
        <div class="panel panel-default">
      
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>  Add Vehicle<!--<?php echo $text_form; ?>--></h3>
      </div>
      
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          
          <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_make; ?></label>
               
                <div class="col-sm-10">
                  <select name="make_id" class="form-control">
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
                  <?php if ($error_make_id) { ?>
                  <div class="text-danger"><?php echo $error_make_id; ?></div>
                  <?php } ?>
                
                </div>
              
              </div>
              
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_model; ?></label>
                
                <div class="col-sm-10" id="models">
                  <select class="form-control fld" tabindex="2" name="model_id">
                    <option value="0" selected="selected">- Select Model -</option>
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
  
   
<script type="text/javascript"><!--
  
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
    
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>