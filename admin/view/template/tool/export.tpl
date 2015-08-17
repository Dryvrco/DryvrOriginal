<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="panel panel-default">
    <div class="panel-heading">
			<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
    <div class="panel-body">
      <form class="form-horizontal" action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="restore">
		<div class="form-group">
			<label class="col-sm-4 control-label"><?php echo $entry_category; ?></label>
			<div class="col-sm-8">
				 <select class="form-control" name="category">
				 <option value="">All Category</option>
				 <?php foreach($categories as $cat){?>
				 <option value="<?php echo $cat['category_id']?>"><?php echo $cat['name']?></option>
				 <?php } ?>
				 </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label"><span data-toggle="tooltip" title="<?php echo $help_number; ?>"><?php echo $entry_number; ?></span></label>
			<div class="col-sm-8">
				<input class="form-control" type="text" name="number" value="<?php echo  $number?>" size="3"/>
				<input class="form-control" type="text" name="end" value="<?php echo  $end?>" size="3"/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label"><?php echo $entry_exportxls; ?></label>
			<div class="col-sm-8">
				<input type="submit" class="btn btn-primary" value="<?php echo $button_export; ?>">
			</div>
		</div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>