<?php

class ControllerSellerAccountFeatures extends ControllerSellerAccount {

    private $error = array();

    public function index() {
        
        $this->load->language('catalog/features');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => "Agency Dashboard",
                'href' => $this->url->link('seller/account-dashboard'),
            ),
            array(
                'text' => "Agency Options",
                'href' => $this->url->link('seller/account-features'),
            )
        ));

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->load->model('agency/agency');
            $this->model_agency_agency->addFilters($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('seller/account-features'));
        }

        $this->data['text_featured'] = $this->language->get('text_featured');
        $this->data['cancel'] = $this->url->link('seller/account-dashboard');
        
        // Filters
        $this->load->model('catalog/filter');

        if (isset($this->request->post['product_filter'])) {
            $filters = $this->request->post['product_filter'];
        } else {
            $filters = $this->model_catalog_filter->getAgencyFiltersList($this->agency->getSellerId());
        }

        $data['product_filters'] = array();

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $this->data['product_filters'][] = array(
                    'filter_id' => $filter_info['filter_id'],
                    'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                );
            }
        }

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-features');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

    public function autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/filter');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name']/*,
                'start' => 0,
                'limit' => 5*/
            );

            $filters = $this->model_catalog_filter->getAgencyFilters($filter_data);

            foreach ($filters as $filter) {
                $json[] = array(
                    'filter_id' => $filter['filter_id'],
                    'name' => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}

?>
