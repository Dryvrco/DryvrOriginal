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
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="padding-top-20"></div>
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="tile">
            <div class="tile-heading"><?php echo $text_my_account; ?></div>
            <div style="line-height:20px; height:150px; padding: 10px">
              <ul class="list-unstyled">
                <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
                <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
                <li style="display:none"><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
                <li style="display:none"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
          <div class="tile">
            <div class="tile-heading"><?php echo $text_my_orders; ?></div>
            <div style="line-height:20px; height:150px; padding: 10px">
              <ul class="list-unstyled">
                <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                <li style="display:none"><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                <?php if ($reward) { ?>
                <li style="display:none"><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
                <?php } ?>
                <li style="display:none"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
                <li style="display:none"><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
                <li style="display:none"><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6" style="display:none">
          <div class="tile">
            <div class="tile-heading"><?php echo $text_my_newsletter; ?></div>
            <div style="line-height:20px; height:150px; padding: 10px">
              <ul class="list-unstyled">
                <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php if ($orders) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-right"><?php echo $column_order_id; ?></td>
              <td class="text-left"><?php echo $column_status; ?></td>
              <td class="text-left"><?php echo $column_reservation; ?></td>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_agency; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
              <td></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order) { ?>
            <tr>
              <td class="text-right">#<?php echo $order['order_id']; ?></td>
              <td class="text-left"><?php echo $order['status']; ?></td>
              <td class="text-left"><?php echo $order['date_added']; ?></td>
              <td class="text-left"><?php echo $order['products']; ?></td>
              <td class="text-left"><?php echo $order['agency']; ?></td>
              <td class="text-right"><?php echo $order['total']; ?></td>
              <td class="text-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <!--<h2><?php echo $text_my_account; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
        <li style="display:none"><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      </ul>
      <h2><?php echo $text_my_orders; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
        <li style="display:none"><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
        <?php if ($reward) { ?>
        <li style="display:none"><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
        <?php } ?>
        <li style="display:none"><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
        <li style="display:none"><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
        <li style="display:none"><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      </ul>
      <h2><?php echo $text_my_newsletter; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
      </ul>--> 
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>