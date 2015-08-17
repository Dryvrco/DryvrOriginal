<?php echo $header; ?>

<div class="container">
   <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
   </ul>
   <?php if (isset($success) && $success) { ?>
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
      <div id="content" class="<?php echo $class; ?> ms-account-dashboard"><?php echo $content_top; ?>
         <div class="padding-top-20"></div>
         <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading"><?php echo $ms_total_bookings ?></div>
                  <div class="tile-body"><i class="fa fa-shopping-cart"></i>
                     <h2 class="pull-right white"><?php echo $total_bookings ?></h2>
                  </div>
                  <div class="tile-footer"><a href="<?php echo $bookings ?>"><?php echo $ms_view_more ?></a></div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading"><?php echo $ms_total_income ?></div>
                  <div class="tile-body"><i class="fa fa-credit-card"></i>
                     <h2 class="pull-right white"><?php echo $total_sales ?></h2>
                  </div>
                  <div class="tile-footer"><a href="<?php echo $sales ?>"><?php echo $ms_view_more ?></a></div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading"><?php echo $ms_total_vehicles ?></div>
                  <div class="tile-body"><i class="fa fa-user"></i>
                     <h2 class="pull-right white"><?php echo $customers_total ?></h2>
                  </div>
                  <div class="tile-footer"><a href="<?php echo $customers_customer ?>"><?php echo $ms_view_more ?></a></div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading"><?php echo $ms_account_dashboard_overview; ?></div>
                  <div style="line-height:18px; padding:5px"><?php echo $ms_account_dashboard_balance; ?>: <?php echo $seller['balance']; ?><br />
                     <?php echo $ms_account_dashboard_total_sales; ?>: <?php echo $seller['total_sales']; ?><br />
                     <?php echo $ms_account_dashboard_total_earnings; ?>: <?php echo $seller['total_earnings']; ?><br />
                     <?php echo $ms_account_dashboard_sales_month; ?>: <?php echo $seller['sales_month']; ?><br />
                     <?php echo $ms_account_dashboard_earnings_month; ?>: <?php echo $seller['earnings_month']; ?></div>
                  <div class="tile-footer"><!--<a href="<?php echo $bookings ?>"><?php echo $ms_view_more ?></a>-->&nbsp;</div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading">Service Ratings</div>
                  <div class="tile-body lstset"><i class="fa fa-comment"></i>
                     <h2 class="pull-right white"><?php echo $total_reviews ?>/5</h2>
                  </div>
                  <div class="tile-footer"><a href="<?php echo $reviews_link ?>">See comments...</a></div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading">Upcoming Reservations</div>
                  <div class="tile-body lstset"><i class="fa fa-line-chart"></i>
                     <h2 class="pull-right white"><?php echo $upcoming ?></h2>
                  </div>
                  <div class="tile-footer"><a href="<?php echo $upcoming_link; ?>">View all...</a></div>
               </div>
            </div>
         </div>
         
         <!--<div class="row">
            <div class="col-lg-4 col-md-4 col-sm-6"></div>
            <div class="col-lg-4 col-md-4 col-sm-6">
               <div class="tile">
                  <div class="tile-heading"><?php echo $ms_account_dashboard_stats; ?></div>
                  <div style="line-height:18px; padding:5px"><?php echo $ms_account_dashboard_balance; ?>: <?php echo $seller['balance']; ?><br />
                     <?php echo $ms_account_dashboard_total_sales; ?>: <?php echo $seller['total_sales']; ?><br />
                     <?php echo $ms_account_dashboard_total_earnings; ?>: <?php echo $seller['total_earnings']; ?><br />
                     <?php echo $ms_account_dashboard_sales_month; ?>: <?php echo $seller['sales_month']; ?><br />
                     <?php echo $ms_account_dashboard_earnings_month; ?>: <?php echo $seller['earnings_month']; ?></div>
                  <div class="tile-footer">&nbsp;</div>
               </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6"></div>
         </div>-->
         
         <!--<div class="row">
        <div class="stats col-md-4">
          <h3><?php echo $ms_account_dashboard_overview; ?></h3>
          <p><span><?php echo $ms_date_created; ?>:</span> <span><?php echo $seller['date_created']; ?></span></p>
          <p><span><?php echo $ms_account_dashboard_seller_group; ?>:</span> <span><?php echo $seller['seller_group']; ?></span></p>
          <p> <span><?php echo $ms_account_dashboard_listing; ?>:</span> <span> <?php echo $this->currency->format(isset($seller['commission_rates'][MsCommission::RATE_LISTING]['flat']) ? $seller['commission_rates'][MsCommission::RATE_LISTING]['flat'] : 0, $this->config->get('config_currency')); ?> + <?php echo isset($seller['commission_rates'][MsCommission::RATE_LISTING]['percent']) ? $seller['commission_rates'][MsCommission::RATE_LISTING]['percent'] : '0'; ?>% </span> </p>
          <p> <span><?php echo $ms_account_dashboard_sale; ?>:</span> <span> <?php echo $this->currency->format(isset($seller['commission_rates'][MsCommission::RATE_SALE]['flat']) ? $seller['commission_rates'][MsCommission::RATE_SALE]['flat'] : 0, $this->config->get('config_currency')); ?> + <?php echo isset($seller['commission_rates'][MsCommission::RATE_SALE]['percent']) ? $seller['commission_rates'][MsCommission::RATE_SALE]['percent'] : '0'; ?>% </span> </p>
          <p> <span><?php echo $ms_account_dashboard_royalty; ?>:</span> <span> <?php echo isset($seller['commission_rates'][MsCommission::RATE_SALE]['percent']) ? 100 - $seller['commission_rates'][MsCommission::RATE_SALE]['percent'] : '100'; ?>% - <?php echo $this->currency->format(isset($seller['commission_rates'][MsCommission::RATE_SALE]['flat']) ? $seller['commission_rates'][MsCommission::RATE_SALE]['flat'] : 0, $this->config->get('config_currency')); ?> </span> </p>
        </div>
        <div class="stats col-md-4">
          <h3><?php echo $ms_account_dashboard_stats; ?></h3>
          <p><span><?php echo $ms_account_dashboard_balance; ?>:</span> <span><?php echo $seller['balance']; ?></span></p>
          <p><span><?php echo $ms_account_dashboard_total_sales; ?>:</span> <span><?php echo $seller['total_sales']; ?></span></p>
          <p><span><?php echo $ms_account_dashboard_total_earnings; ?>:</span> <span><?php echo $seller['total_earnings']; ?></span></p>
          <p><span><?php echo $ms_account_dashboard_sales_month; ?>:</span> <span><?php echo $seller['sales_month']; ?></span></p>
          <p><span><?php echo $ms_account_dashboard_earnings_month; ?>:</span> <span><?php echo $seller['earnings_month']; ?></span></p>
        </div>
      </div>-->
         <table class="list table table-bordered">
            <thead>
               <tr>
                  <td><?php echo $ms_account_orders_id; ?></td>
                  <?php //if (!$this->config->get('msconf_hide_customer_email')) { ?>
                  <td><?php echo $ms_account_orders_customer; ?></td>
                  <?php //} ?>
                  <td><?php echo $ms_status; ?></td>
                  <!--<td><?php echo $ms_account_orders_products; ?></td>-->
                  <td><?php echo $ms_date_created; ?></td>
                  <!--<td><?php echo $ms_account_orders_total; ?></td>-->
                  <td><?php echo $ms_action; ?></td>
               </tr>
            </thead>
            <tbody>
               <?php if (isset($orders) && $orders) { ?>
               <?php foreach ($orders as $order) { ?>
               <tr>
                  <td><?php echo $order['order_id']; ?></td>
                  <?php //if (!$this->config->get('msconf_hide_customer_email')) { ?>
                  <td><?php echo $order['customer']; ?></td>
                  <?php //} ?>
                  <td><?php echo $order['status']; ?></td>
                  <!--<td class="left products"><?php foreach ($order['products'] as $p) { ?>
              <p> <span class="name">
                <?php if ($p['quantity'] > 1) { echo "{$p['quantity']} x "; } ?>
                <a href="<?php echo $this->url->link('product/product', 'product_id=' . $p['product_id'], 'SSL'); ?>"><?php echo $p['name']; ?></a></span>
                <?php foreach ($p['options'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>:<?php echo $option['value']; ?></small>
                <?php } ?>
                <span class="total"><?php echo $this->currency->format($p['seller_net_amt'], $this->config->get('config_currency')); ?></span> </p>
              <?php } ?></td>-->
                  <td><?php echo $order['date_created']; ?></td>
                  <!--<td><?php echo $order['total']; ?></td>-->
                  <td><a href="<?php echo $this->url->link('seller/account-order/viewOrder', 'order_id=' . $order['order_id']); ?>" class="ms-button ms-button-view" title="<?php echo $this->language->get('ms_view_modify') ?>"></a></td>
               </tr>
               <?php } ?>
               <?php } else { ?>
               <tr>
                  <td class="center" colspan="6"><?php echo $ms_account_orders_noorders; ?></td>
               </tr>
               <?php } ?>
            </tbody>
         </table>
         <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 