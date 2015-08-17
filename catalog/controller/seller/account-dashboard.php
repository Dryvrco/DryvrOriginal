<?php

class ControllerSellerAccountDashboard extends ControllerSellerAccount {

    public function index() {
        
        // paypal listing payment confirmation
        if (isset($this->request->post['payment_status']) && strtolower($this->request->post['payment_status']) == 'completed') {
            $this->data['success'] = $this->language->get('ms_account_sellerinfo_saved');
        }

        $this->load->model('catalog/product');
        $this->load->model('tool/image');
        $this->load->model('account/vieworder');
        $this->load->model('account/order2');
        $this->data['upcoming'] = $this->model_account_order2->getTotalUpcomingReservations();
        $this->data['upcoming_link'] = $this->url->link('seller/account-reservations');

        $seller_id = $this->agency->getSellerId();

        $seller = $this->MsLoader->MsSeller->getSeller($seller_id);
        
        $seller_group_names = $this->MsLoader->MsSellerGroup->getSellerGroupDescriptions($seller['ms.seller_group']);
        $my_first_day = date('Y-m-d H:i:s', mktime(0, 0, 0, date("n"), 1));

        $this->data['seller'] = array_merge(
                $seller, array('balance' => $this->currency->format($this->MsLoader->MsBalance->getSellerBalance($seller_id), $this->config->get('config_currency'))), array('commission_rates' => $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $seller_id))), array('total_earnings' => $this->currency->format($this->MsLoader->MsSeller->getTotalEarnings($seller_id), $this->config->get('config_currency'))), array('earnings_month' => $this->currency->format($this->MsLoader->MsSeller->getTotalEarnings($seller_id, array('period_start' => $my_first_day)), $this->config->get('config_currency'))), array('sales_month' => $this->MsLoader->MsOrderData->getTotalSales(array(
                'seller_id' => $seller_id,
                'period_start' => $my_first_day
            ))), array('seller_group' => $seller_group_names[$this->config->get('config_language_id')]['name']), array('date_created' => date($this->language->get('date_format_short'), strtotime($seller['ms.date_created'])))
                //array('total_products' => $this->MsLoader->MsProduct->getTotalProducts(array(
                //'seller_id' => $seller_id,
                //'enabled' => ))
        );

        if ($seller['ms.avatar'] && file_exists(DIR_IMAGE . $seller['ms.avatar'])) {
            $this->data['seller']['avatar'] = $this->MsLoader->MsFile->resizeImage($seller['ms.avatar'], $this->config->get('msconf_seller_avatar_dashboard_image_width'), $this->config->get('msconf_seller_avatar_dashboard_image_height'));
        } else {
            $this->data['seller']['avatar'] = $this->MsLoader->MsFile->resizeImage('ms_no_image.jpg', $this->config->get('msconf_seller_avatar_dashboard_image_width'), $this->config->get('msconf_seller_avatar_dashboard_image_height'));
        }

        $payments = $this->MsLoader->MsPayment->getPayments(
                array(
            'seller_id' => $seller_id,
                ), array(
            'order_by' => 'mpay.date_created',
            'order_way' => 'DESC',
            'offset' => 0,
            'limit' => 10
                )
        );

        $orders = $this->MsLoader->MsOrderData->getOrders(
                array(
            'seller_id' => $seller_id,
            'order_status' => $this->config->get('msconf_display_order_statuses')
                ), array(
            'order_by' => 'date_added',
            'order_way' => 'DESC',
            'offset' => 0,
            'limit' => 10
                )
        );
        

        $this->load->model('account/reviews');
        $this->data['total_reviews'] = (int) $this->model_account_reviews->getTotalReviews($seller_id);
        $this->data['reviews_link'] = $this->url->link('seller/account-reviews');

        $this->load->model('localisation/order_status');
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        $sale_total = '';
        $order_total = '';

        foreach ($orders as $order) {

            $suborder_status_id = $this->model_localisation_order_status->getSuborderStatusId($order['order_id'], $this->agency->getSellerId());
            if ($suborder_status_id)
                $order['order_status_id'] = $suborder_status_id;
            $order_status_name = '';
            foreach ($order_statuses as $order_status) {
                if ($order_status['order_status_id'] == $order['order_status_id'])
                    $order_status_name = $order_status['name'];
            }

            $products = $this->MsLoader->MsOrderData->getOrderProducts(array('order_id' => $order['order_id'], 'seller_id' => $seller_id));

            foreach ($products as $key => $p)
                $products[$key]['options'] = $this->model_account_vieworder->getOrderOptions($order['order_id'], $p['order_product_id']);

            $this->data['orders'][] = array(
                'order_id' => $order['order_id'],
                'customer' => "{$order['firstname']} {$order['lastname']}",
                'status' => $order_status_name,
                'products' => $products,
                'date_created' => date($this->language->get('date_format_short'), strtotime($order['date_added'])),
                'total' => $this->currency->format($this->MsLoader->MsOrderData->getOrderTotal($order['order_id'], array('seller_id' => $seller_id)), $this->config->get('config_currency'))
            );
            $sale_total = $sale_total + $this->MsLoader->MsOrderData->getOrderTotal($order['order_id'], array('seller_id' => $seller_id));
            $order_total++;
        }

        // Total Orders        
        $ordrs = $this->MsLoader->MsOrderData->getOrders(
                array(
            'seller_id' => $seller_id,
            'order_status' => $this->config->get('msconf_display_order_statuses')
                ), array(
            'order_by' => $sortCol,
            'order_way' => $sortDir,
            'offset' => $this->request->get['iDisplayStart'],
            'limit' => $this->request->get['iDisplayLength'],
            'filters' => $filterParams
                ), array(
            'total_amount' => 1,
            'products' => 1,
                )
        );

        $ot = isset($ordrs[0]) ? $ordrs[0]['total_rows'] : 0;

        if ($order_total > 1000000000000) {
            $this->data['total_bookings'] = round($ot / 1000000000000, 1) . 'T';
        } elseif ($order_total > 1000000000) {
            $this->data['total_bookings'] = round($ot / 1000000000, 1) . 'B';
        } elseif ($order_total > 1000000) {
            $this->data['total_bookings'] = round($ot / 1000000, 1) . 'M';
        } elseif ($order_total > 1000) {
            $this->data['total_bookings'] = round($ot / 1000, 1) . 'K';
        } elseif ($order_total == '') {
            $this->data['total_bookings'] = 0;
        } else {
            $this->data['total_bookings'] = $ot;
        }

        $this->data['bookings'] = $this->url->link('seller/account-order');

        //Total Sales
        $this->load->model('report/sale');

        if ($sale_total > 1000000000000) {
            $this->data['total_sales'] = round($sale_total / 1000000000000, 1) . 'T';
        } elseif ($sale_total > 1000000000) {
            $this->data['total_sales'] = round($sale_total / 1000000000, 1) . 'B';
        } elseif ($sale_total > 1000000) {
            $this->data['total_sales'] = round($sale_total / 1000000, 1) . 'M';
        } elseif ($sale_total > 1000) {
            $this->data['total_sales'] = round($sale_total / 1000, 1) . 'K';
        } else {
            $this->data['total_sales'] = round($sale_total);
        }

        $this->data['sales'] = $this->url->link('seller/account-order');

        // Total Vehicles
        $this->load->model('sale/customer');

        $customer_total = $this->model_sale_customer->getTotalProducts($seller_id);

        if ($customer_total > 1000000000000) {
            $this->data['customers_total'] = round($customer_total / 1000000000000, 1) . 'T';
        } elseif ($customer_total > 1000000000) {
            $this->data['customers_total'] = round($customer_total / 1000000000, 1) . 'B';
        } elseif ($customer_total > 1000000) {
            $this->data['customers_total'] = round($customer_total / 1000000, 1) . 'M';
        } elseif ($customer_total > 1000) {
            $this->data['customers_total'] = round($customer_total / 1000, 1) . 'K';
        } else {
            $this->data['customers_total'] = $customer_total;
        }

        $this->data['customers_customer'] = $this->url->link('seller/account-product');

        //Tiles End

        $this->document->setTitle($this->language->get('ms_account_dashboard_heading'));

        $this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
                'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
            )
        ));

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-dashboard');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

}

?>
