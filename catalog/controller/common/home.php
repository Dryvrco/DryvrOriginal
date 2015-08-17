<?php

class ControllerCommonHome extends Controller {

    public function index() {
        $this->document->setTitle($this->config->get('config_meta_title'));
        $this->document->setDescription($this->config->get('config_meta_description'));
        $this->document->setKeywords($this->config->get('config_meta_keyword'));

        if (isset($this->request->get['route'])) {
            $this->document->addLink(HTTP_SERVER, 'canonical');
        }

        $this->cart->clear();

        $this->load->model('common/home');
        $this->load->model('tool/image');
        $this->load->model('catalog/product');

        $agencies = $this->model_common_home->getAgencies();
        $data['agencies'] = array();

        foreach ($agencies as $agency) {

            if ($agency['avatar']) {
                $avatar = $this->model_tool_image->resize($agency['avatar'], 600, 313);
            } else {
                $avatar = $this->model_tool_image->resize('placeholder.png', 600, 313);
            }

            $data['agencies'][] = array(
                'nickname' => $agency['nickname'],
                'avatar' => $avatar,
                'href' => $this->url->link('agency/agency_detail&agency_id=' . $agency['seller_id'])
            );
        }

        $agencycars = $this->model_common_home->getAgenciesCars();
        $data['agencycars'] = array();

        foreach ($agencycars as $agencycar) {

            if ($agencycar['image']) {
                $image = $this->model_tool_image->resize($agencycar['image'], 600, 313);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 600, 313);
            }

            $data['agencycars'][] = array(
                'name' => $this->model_catalog_product->getMakeModelCategories($agencycar['product_id']) . ' ' . $agencycar['model'],
                'image' => $image,
                'href' => $this->url->link('product/product', 'product_id=' . $agencycar['product_id'])
            );
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
        }
    }

}
