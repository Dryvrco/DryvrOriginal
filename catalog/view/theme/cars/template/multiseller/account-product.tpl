<?php echo $header; ?>
<div class="container ms-account-product">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if (isset($success) && ($success)) { ?>
		<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?></div>
  <?php } ?>

  <?php if (isset($error_warning) && $error_warning) { ?>
  	<div class="alert alert-danger warning main"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="ms-account-transaction <?php echo $class; ?>"><?php echo $content_top; ?>
    
    <div class="row toggle4" style="margin-top:21px; display:none" >
    	<table class="table table-bordered">
        	<thead>
            	<tr><td>Booking ID</td><td>Customer</td><td>Vehicle</td><td>Start Time</td></tr>
            </thead>
            <tbody>
            	<?php foreach ($orders as $order){ ?>
                	<tr>
                    	<td><?php echo $order['order_id'] ?></td>
                        <td><?php echo $order['customer'] ?></td>
                        <td><?php echo $order['product'] ?></td>
                        <td><?php echo $order['start_time'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="row">
        <div class="col-md-6">
          <h1><?php echo $ms_account_products_heading; ?></h1>
        </div>
        <div class="col-md-6"  >
          <div class="pull-right"><a href="<?php echo $link_back; ?>" class="btn btn-default"><span><?php echo $button_back; ?></span></a>
          
          <!--<button id="toggle4" class="btn btn-primary">Upcoming Reservations</button>-->
          </div>
        </div>
      </div>
    
	<table class="list table table-bordered table-hover" id="list-products">
	<thead>
	<tr>
		<td><strong><?php echo $ms_account_products_image; ?></strong></td>
		<td><strong><?php echo $ms_account_products_product; ?></strong></td>
		<td><strong><?php echo $ms_account_product_daily_price; ?></strong></td>
        <td><strong><?php echo $ms_account_product_weekly_price; ?></strong></td>
        <td><strong><?php echo $ms_account_product_weekend_price; ?></strong></td>
		<td><strong><?php echo $ms_account_products_sales; ?></strong></td>
		<td><strong><?php echo $ms_account_products_earnings; ?></strong></td>
		<td><strong><?php echo $ms_account_products_status; ?></strong></td>
		<td><strong><?php echo $ms_account_products_date; ?></strong></td>
		<td class="large"><strong><?php echo $ms_account_products_action; ?></strong></td>
	</tr>
	</thead>
	<tbody></tbody>
	</table>

	  <?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
    <div class="clear">&nbsp;</div>
</div>

<script>
	$(function() {
		$('#list-products').dataTable( {
			"sAjaxSource": $('base').attr('href') + "index.php?route=seller/account-product/getTableData",
			"aoColumns": [
				{ "mData": "image", "bSortable": false},
				{ "mData": "product_name", "bSortable": false},
				{ "mData": "daily" },
				{ "mData": "weekly" },
				{ "mData": "weekend" },
				{ "mData": "number_sold" },
				{ "mData": "product_earnings" },
				{ "mData": "product_status" },
				{ "mData": "date_created" },
				{ "mData": "actions", "bSortable": false, "sClass": "text-right" }
			]
		});
	
		$(document).on('click', '.ms-button-delete', function() {
			if (!confirm('<?php echo $ms_account_products_confirmdelete; ?>')) return false;
		});
	});
	
		$('#toggle4').click(function() {

	    $('.toggle4').slideToggle('slow');

	    return false;

	});
	
</script>
<?php echo $footer; ?>