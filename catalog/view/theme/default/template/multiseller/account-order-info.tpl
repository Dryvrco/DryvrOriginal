<?php echo $header; ?>

<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if (isset($error_warning) && $error_warning) { ?>
  <div class="alert alert-danger warning main"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if (isset($success) && ($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if (isset($statustext) && ($statustext)) { ?>
  <div class="alert alert-<?php echo $statusclass; ?>"><?php echo $statustext; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="ms-product <?php echo $class; ?> ms-account-profile"><?php echo $content_top; ?>
      <div class="row">
        <div class="col-md-6">
          <h2><?php echo $ms_account_order_information; ?></h2>
        </div>
        <div class="col-md-6">
          <div class="pull-right"><a href="<?php echo $link_back; ?>" class="btn btn-primary"><span><?php echo $button_back; ?></span></a></div>
        </div>
      </div>
      
      <!-- order information -->
      <table class="table table-responsive table-bordered table-hover">
        <thead>
          <tr>
            <td colspan="2"><?php echo $text_order_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width: 50%;"><?php if ($invoice_no) { ?>
              <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
              <?php } ?>
              <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
              <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
            <td style="width: 50%;"><?php if ($payment_method) { ?>
              <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
              <?php } ?>
              <?php if ($shipping_method) { ?>
              <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
              <?php } ?></td>
          </tr>
        </tbody>
      </table>
      
      <!-- addresses -->
      <table class="table table-responsive table-bordered">
        <thead>
          <tr>
            <td class="left"><?php echo $text_payment_address; ?></td>
            <?php if ($shipping_address) { ?>
            <td class="left"><?php echo $text_shipping_address; ?></td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="left"><?php echo $payment_address; ?></td>
            <?php if ($shipping_address) { ?>
            <td class="left"><?php echo $shipping_address; ?></td>
            <?php } ?>
          </tr>
        </tbody>
      </table>
      
      <!-- products -->
      <table class="list table table-responsive table-bordered">
        <thead>
          <tr>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="left"><?php echo $column_model; ?></td>
            <!--<td class="right"><?php echo $column_quantity; ?></td>
            <td class="right"><?php echo $column_price; ?></td>-->
            <td class="right"><?php echo $column_total; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="left"><?php echo $product['name']; ?>
            <td class="left"><?php echo $product['model']; ?></td>
            <!--<td class="right"><?php echo $product['quantity']; ?></td>
            <td class="right"><?php echo $product['price']; ?></td>-->
            <td class="right"><?php echo $product['total']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot style="text-align: center;">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td></td>
            <td><b><?php echo $total['title']; ?>:</b></td>
            <td><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </tfoot>
      </table>
      
      <!-- sub-order history --> 
      
      <!-- change -->
      <table class="list table table-responsive table-bordered">
        <tr>
          <td><form method="POST" action="<?= $redirect ?>">
              <?php echo $ms_account_orders_change_status ?>:
              <select name="order_status_edit">
                <?php foreach ($order_statuses as $order_statuses) { ?>
                <?php if ($order_statuses['order_status_id'] == $order_status_id) { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>" selected="selected"><?php echo $order_statuses['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_statuses['order_status_id']; ?>"><?php echo $order_statuses['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
              <button><?php echo $ms_button_submit; ?></button>
            </form></td>
        </tr>
      </table>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo footer; ?> 