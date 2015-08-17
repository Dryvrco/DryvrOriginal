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
    <div id="content" class="<?php echo $class ?>">
      <div class="container-fluid" style="padding-top:23px">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><?php echo $text_form; ?></h3>
          </div>
          <div class="panel-body">
            <table class="table table-bordered">
            	<tr><td width="25%"><?php echo $entry_author ?></td><td width="75%"><?php echo $author; ?></td></tr>
                <tr><td width="25%"><?php echo $entry_text ?></td><td width="75%"><?php echo $text; ?></td></tr>
                <tr><td width="25%"><?php echo $entry_rating ?></td><td width="75%"><?php for ($i=1;$i<=$stars;$i++){ ?><i class="glyphicon glyphicon-star"></i><?php } ?><?php for ($j=1;$j<=$nostars;$j++){ ?><i class="glyphicon glyphicon-star-empty"></i><?php } ?></td></tr>
                <tr><td width="25%"><?php echo $entry_status ?></td><td width="75%"><?php if($status=='1') { echo 'Enabled'; } else if ($status=='0') { echo 'Disabled'; } ?></td></tr>
                <tr><td width="25%"><?php echo $entry_date_added ?></td><td width="75%"><?php echo $date_added; ?></td></tr>
            </table>
            <a class="requestBtn" href="<?php echo $back ?>">Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 