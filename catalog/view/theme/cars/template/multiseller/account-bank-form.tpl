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
          <div class="pull-right">
            <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
            <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
            <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
          </div>
          <div class="panel-body">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_bank_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_account_title; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="account_title" value="<?php echo $account_title; ?>" placeholder="<?php echo $entry_account_title; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_account_number; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="account_number" value="<?php echo $account_number; ?>" placeholder="<?php echo $entry_account_number; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_routing_number; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="routing_number" value="<?php echo $routing_number; ?>" placeholder="<?php echo $entry_routing_number; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_swift_code; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="swift_code" value="<?php echo $swift_code; ?>" placeholder="<?php echo $entry_swift_code; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 