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
      <div class="page-header">
        <div class="container-fluid">
          <!--<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> </div>-->
          <h1><?php echo $heading_title; ?></h1>
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
            <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
          </div>
          <div class="panel-body">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_author ?></td>
                      <td class="text-left"><?php echo $entry_text ?></td>
                      <td class="text-left"><?php echo $entry_rating ?></td>
                      <td class="text-left"><?php echo $entry_status ?></td>
                      <td class="text-left"><?php echo $entry_date_added ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($reviews) { ?>
                    <?php foreach ($reviews as $review) { ?>
                    <tr>
                      <td class="text-left"><?php echo $review['author']; ?></td>
                      <td class="text-left"><?php echo $review['text']; ?></td>
                      <td class="text-left"><?php for ($i=1;$i<=$review['stars'];$i++){ ?><i class="glyphicon glyphicon-star"></i><?php } ?><?php for ($j=1;$j<=$review['nostars'];$j++){ ?><i class="glyphicon glyphicon-star-empty"></i><?php } ?></td>
                      <td class="text-left"><?php if($review['status']=="1"){ echo "Enabled"; } else if ($review['status']=="0"){ echo "Disabled"; } ?></td>
                      <td class="text-left"><?php echo $review['date_added']; ?></td>
                      <td class="text-right"><a href="<?php echo $review['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></a></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 