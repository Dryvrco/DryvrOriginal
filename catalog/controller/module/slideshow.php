<?php

class ControllerModuleSlideshow extends Controller {

    public function index($setting) {

        $this->cart->clear();

        static $module = 0;

        $this->load->model('design/banner');
        $this->load->model('tool/image');
        $this->load->model('module/slideshow');

        $this->document->addStyle('catalog/view/javascript/jquery/owl-carousel/owl.carousel.css');
        $this->document->addScript('catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js');

        $data['action'] = $this->url->link('product/listing');

        $data['banners'] = array();

        $results = $this->model_design_banner->getBanner($setting['banner_id']);

        $data['makes'] = $this->model_module_slideshow->getMakes();

        $data['cats'] = $this->model_module_slideshow->getCats();

        $data['cities'] = $this->model_module_slideshow->getCities();

        $data['agencies'] = $this->model_module_slideshow->getAgencies();

        $data['star'] = '*';

        if ($this->request->get['error'] == '1') {    

            if (!isset($this->request->get['city_id'])) {
                $data['error_city'] = '(Provide City)';
            }

            if (!isset($this->request->get['start_date'])) {
                $data['error_start_date'] = '(Provide Start Date)';
            }

            if (!isset($this->request->get['start_time'])) { 
                $data['error_start_time'] = '(Provide Start Time)';
            }


            if (!isset($this->request->get['end_date'])) {
                $data['error_end_date'] = '(Provide End Date)';
            }

            if (!isset($this->request->get['end_time'])) {
                $data['error_end_time'] = '(Provide End Time)';
            }

            $data['error_class'] = 'has-error';
            $data['class_red'] = 'redcolor';
            $data['star'] = '';
        }

        if (isset($this->request->get['city_id'])) {
            $data['city_id'] = $this->request->get['city_id'];
        }

        if (isset($this->request->get['start_date'])) {
            $data['start_date'] = $this->request->get['start_date'];
        }

        if (isset($this->request->get['start_time'])) {
            $data['start_time'] = $this->request->get['start_time'];
        }

        if (isset($this->request->get['end_date'])) {
            $data['end_date'] = $this->request->get['end_date'];
        }

        if (isset($this->request->get['end_time'])) {
            $data['end_time'] = $this->request->get['end_time'];
        }

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $data['banners'][] = array(
                    'title' => $result['title'],
                    'link' => $result['link'],
                    'image' => $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height'])
                );
            }
        }

        $data['module'] = $module++;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/module/slideshow.tpl', $data);
        } else {
            return $this->load->view('default/template/module/slideshow.tpl', $data);
        }
    }

    public function autocomplete() {
        $this->load->model('module/slideshow');
        $results = $this->model_module_slideshow->getSubCategories($this->request->get['make_id']);
    }

    public function cityautocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('module/slideshow');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'limit' => 5,
                'sort' => 'name',
                'order' => 'ASC'
            );

            $results = $this->model_module_slideshow->getCities($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'city_id' => $result['city_id'],
                    'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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
