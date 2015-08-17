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
    <div id="content" class="<?php echo $class; ?> ms-account-order"><?php echo $content_top; ?>
      <div class="row">
        <div class="col-md-6">
          <h1><?php echo $ms_account_orders_heading; ?></h1>
        </div>
        <div class="col-md-6">
          <div class="pull-right"><a href="<?php echo $link_back; ?>" class="btn btn-primary"><span><?php echo $button_back; ?></span></a></div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="list table table-bordered table-hover" id="list-orders">
          <thead>
            <tr>
              <td class="tiny"><?php echo $ms_account_orders_id; ?></td>
              <td class="medium"><?php echo $ms_account_orders_customer; ?></td>
              <td class="medium"><?php echo $ms_status; ?></td>
              <!--<td><?php echo $ms_account_orders_products; ?></td>-->
              <td class="medium"><?php echo $ms_date_created; ?></td>
              <!--<td class="medium"><?php echo $ms_account_orders_total; ?></td>-->
              <td class="tiny"><?php echo $ms_action; ?></td>
            </tr>
            <tr class="filter">
              <td><input type="text"/></td>
              <td><input type="text"/></td>
              <td></td>
              <!--<td><input type="text"/></td>-->
              <td><input type="text"/></td>
              <!--<td><input type="text"/></td>-->
              <td></td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
	$(function() {
		$('#list-orders').dataTable( {
			"sAjaxSource": $('base').attr('href') + "index.php?route=seller/account-order/getTableData",
			"aoColumns": [
				{ "mData": "order_id" },
				{ "mData": "customer_name" },
				{ "mData": "suborder_status", "bSortable": false },
				/*{ "mData": "products", "bSortable": false, "sClass": "products" },*/
				{ "mData": "date_created" },
				/*{ "mData": "total_amount" },*/
				{ "mData": "view_order" }
			],
			"aaSorting":  [[4,'desc']]
		});
	});
</script> 
<?php echo $footer; ?>