<?php

class ControllerAgencyAgencies extends Controller {

    public function index() {

        $this->load->language('agency/agencies');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('agency/agency');
        $this->load->model('catalog/product');

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

        $filter_data = array(
            'start' => ($page - 1) * 6,
            'limit' => 6
        );

        $sellers = $this->model_agency_agency->getAllSellerDetails($filter_data);

        $totalsellers = $this->model_agency_agency->getAllSellers();

        $this->load->model('tool/image');

        foreach ($sellers as $seller_detail) {

            if ($seller_detail['avatar']) {
                $avatar = $this->model_tool_image->resize($seller_detail['avatar'], 800, 364);
            } else {
                $avatar = $this->model_tool_image->resize('no_image.jpg', 800, 364);
            }
            
            if ($seller_detail['country'] || $seller_detail['zone']) {
                $location = $seller_detail['zone'] . ', ' . $seller_detail['country'];
            } else {
                $location = 'Not Specified';
            }
            
            $seller_description = '';
            
            if ($seller_detail['description']){
                $seller_description = utf8_substr(strip_tags(html_entity_decode($seller_detail['description'], ENT_QUOTES, 'UTF-8')), 0, 125) . '...';
            }

            $data['seller_details'][] = array(
                'nickname' => $seller_detail['nickname'],
                'description' => $seller_description,
                'avatar' => $avatar,
                'location' => $location,
                'total_seller_cars' => $this->model_agency_agency->getTotalAgencyCars($seller_detail['seller_id']),
                'agencyfilters' => $this->model_catalog_product->getAgencyFiltersNames($seller_detail['seller_id']),
                'rating' => ((int) $this->model_agency_agency->getAgencyReview($seller_detail['seller_id'])),
                'href' => $this->url->link('agency/agency_detail&agency_id=' . $seller_detail['seller_id'])
            );
        }

        $pagination = new Pagination();
        $pagination->total = $totalsellers;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('agency/agencies' . '&page={page}' . $url);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($totalsellers) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($totalsellers - $limit)) ? $totalsellers : ((($page - 1) * $limit) + $limit), $totalsellers, ceil($totalsellers / $limit));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/agency/agencies.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/agency/agencies.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/agency/agencies.tpl', $data));
        }
    }

}
