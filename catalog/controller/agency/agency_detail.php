<?php

class ControllerAgencyAgencyDetail extends Controller {

    public function index() {

        $this->load->language('agency/agency');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('agency/agency');
        $this->load->model('catalog/product');

        $agency_id = $this->request->get['agency_id'];

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.daily';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = 6;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => 'Our Agencies',
            'href' => $this->url->link('agency/agencies')
        );

        $data['cars_available'] = $this->language->get('cars_available');
        $data['sort_by'] = $this->language->get('sort_by');
        $data['low_to_high'] = $this->language->get('low_to_high');
        $data['high_to_low'] = $this->language->get('high_to_low');

        $data['url'] = $this->url->link('agency/agency_detail&agency_id=' . $agency_id);

        $data['seller_details'] = array();

        $seller_details = $this->model_agency_agency->getSellerDetails($agency_id);

        $this->load->model('tool/image');

        foreach ($seller_details as $seller_detail) {

            $data['breadcrumbs'][] = array(
                'text' => $seller_detail['nickname'],
                'href' => $this->url->link('agency/agency_detail&agency_id=' . $agency_id)
            );

            if ($seller_detail['avatar']) {
                $avatar = $this->model_tool_image->resize($seller_detail['avatar'], 800, 364);
            } else {
                $avatar = $this->model_tool_image->resize('no_image.jpg', 800, 364);
            }

            if ($seller_detail['country_name'] || $seller_detail['zone_name']) {
                $location = $seller_detail['zone_name'] . ', ' . $seller_detail['country_name'];
            } else {
                $location = 'This Agent has not selected any region';
            }

            $data['agencyfilters'] = $this->model_catalog_product->getAgencyFiltersNames($agency_id);

            $rating = $this->model_agency_agency->getAgencyReview($agency_id);
            
            $data['nearbyareas'] = $this->model_agency_agency->getNearbyAreas($agency_id);

            $data['seller_details'][] = array(
                'nickname' => $seller_detail['nickname'],
                'description' => html_entity_decode($seller_detail['description'], ENT_QUOTES, 'UTF-8'),
                'avatar' => $avatar,
                'location' => $location,
                'rating' => ceil($rating['rating'])
            );
        }

        $agency_car_total = $this->model_agency_agency->getTotalAgencyCars($agency_id);
        
        $data['agency_car_total'] = $agency_car_total;

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * 6,
            'limit' => 6
        );

        $data['agency_cars'] = array();

        $agency_cars = $this->model_agency_agency->getAgencyCars($agency_id, $filter_data);

        foreach ($agency_cars as $agency_car) {

            if ($agency_car['image']) {
                $image = $this->model_tool_image->resize($agency_car['image'], 640, 480);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 640, 480);
            }

            $data['agency_cars'][] = array(
                'name' => $this->model_catalog_product->getMakeModelYear($agency_car['product_id']),
                'image' => $image,
                'description' => utf8_substr(strip_tags(html_entity_decode($agency_car['description'], ENT_QUOTES, 'UTF-8')), 0, 150) . '...',
                'daily' => $this->currency->format($agency_car['daily']),
                'weekly' => $this->currency->format($agency_car['weekly']),
                'monthly' => $this->currency->format($agency_car['monthly']),
                'href' => $this->url->link('product/product&product_id=' . $agency_car['product_id'])
            );
        }

        $pagination = new Pagination();
        $pagination->total = $agency_car_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('agency/agency_detail&agency_id=' . $agency_id . '&page={page}' . $url);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($agency_car_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($agency_car_total - $limit)) ? $agency_car_total : ((($page - 1) * $limit) + $limit), $agency_car_total, ceil($agency_car_total / $limit));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agency/agency_detail.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/agency/agency_detail.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/agency/agency_detail.tpl', $data));
        }
    }

}
