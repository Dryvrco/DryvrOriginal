<?php

class ControllerProductListing extends Controller {

    public function index() {

        if ($this->session->data['start_date'] == '' || $this->session->data['start_time'] == '' || $this->session->data['end_date'] == '' || $this->session->data['end_time'] == '') {
            unset($this->session->data['start_date']);
            unset($this->session->data['start_time']);
            unset($this->session->data['end_date']);
            unset($this->session->data['end_time']);
            unset($this->session->data['booking_hours']);
            unset($this->session->data['dsd']);
            unset($this->session->data['dst']);
            unset($this->session->data['ded']);
            unset($this->session->data['det']);
        }

        $this->cart->clear();

        /* if ($this->request->get['city_id'] == '' || $this->request->get['start_date'] == '' || $this->request->get['start_time'] == '' || $this->request->get['end_date'] == '' || $this->request->get['end_time'] == '') {
          $this->response->redirect($this->url->link('common/home&error=1'));
          } */

        $this->load->language('product/listing');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->load->model('module/slideshow');

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.daily';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = $this->request->get['limit'];
        } else {
            $limit = $this->config->get('config_product_limit');
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/special', $url)
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_model'] = $this->language->get('text_model');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_tax'] = $this->language->get('text_tax');
        $data['text_points'] = $this->language->get('text_points');
        $data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
        $data['text_sort'] = $this->language->get('text_sort');
        $data['text_limit'] = $this->language->get('text_limit');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');
        $data['button_list'] = $this->language->get('button_list');
        $data['button_grid'] = $this->language->get('button_grid');
        $data['button_continue'] = $this->language->get('button_continue');

        $data['compare'] = $this->url->link('product/compare');

        $data['products'] = array();

        $url = '';

        if (isset($this->request->get['make_id'])) {
            $url .= '&make_id=' . $this->request->get['make_id'];
            $data['make_id'] = $this->request->get['make_id'];
        }

        if (isset($this->request->get['model_id'])) {
            $url .= '&model_id=' . $this->request->get['model_id'];
            $data['model_id'] = $this->request->get['model_id'];
        }

        if (isset($this->request->get['cat_id'])) {
            $url .= '&cat_id=' . $this->request->get['cat_id'];
            $data['cat_id'] = $this->request->get['cat_id'];
        }

        if (isset($this->request->get['city_id'])) {
            $url .= '&city_id=' . $this->request->get['city_id'];
            $data['city_id'] = $this->request->get['city_id'];
        }

        if (isset($this->request->get['area_id'])) {
            $url .= '&area_id=' . $this->request->get['area_id'];
            $data['area_id'] = $this->request->get['area_id'];
        }

        if (isset($this->request->get['start_date'])) {
            $url .= '&start_date=' . $this->request->get['start_date'];
            $dateurl .= '&start_date=' . $this->request->get['start_date'];
        }

        if (isset($this->request->get['start_time'])) {
            $url .= '&start_time=' . $this->request->get['start_time'];
            $dateurl .= '&start_time=' . $this->request->get['start_time'];
        }

        if (isset($this->request->get['end_date'])) {
            $url .= '&end_date=' . $this->request->get['end_date'];
            $dateurl .= '&end_date=' . $this->request->get['end_date'];
        }

        if (isset($this->request->get['end_time'])) {
            $url .= '&end_time=' . $this->request->get['end_time'];
            $dateurl .= '&end_time=' . $this->request->get['end_time'];
        }

        if (isset($this->request->get['filters'])) {
            $url .= '&filters=' . $this->request->get['filters'];
            $getfilters = $this->request->get['filters'];
        }

        $data['sorturl'] = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
            $data['sorturl'] .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
            $data['sorturl'] .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
            $data['sorturl'] .= '&limit=' . $this->request->get['limit'];
        }

        $searchurl = $url;

        if (isset($this->request->get['start_date']) && isset($this->request->get['start_time'])) {
            $unix_start = strtotime(convertdate($this->request->get['start_date']) . ' ' . $this->request->get['start_time']);
            $data['start_date'] = $this->request->get['start_date'];
            $data['start_time'] = $this->request->get['start_time'];
            $this->session->data['dsd'] = $this->request->get['start_date'];
            $this->session->data['dst'] = $this->request->get['start_time'];
            $this->session->data['start_date'] = convertdate($this->request->get['start_date']);
            $this->session->data['start_time'] = convert12to24($this->request->get['start_time']);
        } else if (isset($this->session->data['start_date']) && isset($this->session->data['start_time'])){
            $data['start_date'] = $this->session->data['dsd'];
            $data['start_time'] = $this->session->data['dst'];
        }

        if (isset($this->request->get['end_date']) && isset($this->request->get['end_time'])) {
            $unix_end = strtotime(convertdate($this->request->get['end_date']) . ' ' . $this->request->get['end_time']);
            $data['end_date'] = $this->request->get['end_date'];
            $data['end_time'] = $this->request->get['end_time'];
            $this->session->data['ded'] = $this->request->get['end_date'];
            $this->session->data['det'] = $this->request->get['end_time'];
            $this->session->data['end_date'] = convertdate($this->request->get['end_date']);
            $this->session->data['end_time'] = convert12to24($this->request->get['end_time']);
        } else if (isset($this->session->data['end_date']) && isset($this->session->data['end_time'])){
            $data['end_date'] = $this->session->data['ded'];
            $data['end_time'] = $this->session->data['det'];
        }

        $this->session->data['booking_hours'] = calculatehours($this->session->data['start_date'], $this->session->data['start_time'], $this->session->data['end_date'], $this->session->data['end_time']);

        $data['url'] = $this->url->link('product/listing' . $url);

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.daily';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        $this->load->model('module/slideshow');

        $data['makes'] = $this->model_module_slideshow->getMakes();

        $data['cats'] = $this->model_module_slideshow->getCats();

        $data['cities'] = $this->model_module_slideshow->getCities();

        $this->load->model('localisation/city');

        $data['areas'] = $this->model_localisation_city->getAreasList($this->request->get['city_id']);

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'make_id' => $this->request->get['make_id'],
            'model_id' => $this->request->get['model_id'],
            'cat_id' => $this->request->get['cat_id'],
            'city_id' => $this->request->get['city_id'],
            'area_id' => $this->request->get['area_id'],
            'filters' => $getfilters,
            'unix_start' => $unix_start,
            'unix_end' => $unix_end,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        );

        $data['get_filters'] = explode(',', $getfilters);

        $product_total = $this->model_catalog_product->showTotalVehlicles($filter_data);

        $results = $this->model_catalog_product->showVehlicles($filter_data);

        foreach ($results as $result) {

            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 500, 500);
            } else {
                $image = $this->model_tool_image->resize('no_image.jpg', 500, 500);
            }

            if ($result['avatar']) {
                $avatar = $this->model_tool_image->resize($result['avatar'], 50, 50);
            } else {
                $avatar = $this->model_tool_image->resize('no_image.jpg', 50, 50);
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb' => $image,
                'location' => $result['name'],
                'avatar' => $avatar,
                'name' => $this->model_catalog_product->getMakeModelYear($result['product_id']),
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '...',
                'daily' => $this->currency->format($result['daily']),
                'weekly' => $this->currency->format($result['weekly']),
                'monthly' => $this->currency->format($result['monthly']),
                'rate' => $this->currency->format(calculaterate($this->session->data['booking_hours'], $result['daily'], $result['weekly'], $result['monthly'])),
                'href' => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $dateurl)
            );
        }

        /* $url = '';

          if (isset($this->request->get['limit'])) {
          $url .= '&limit=' . $this->request->get['limit'];
          }

          $url = '';

          if (isset($this->request->get['sort'])) {
          $url .= '&sort=' . $this->request->get['sort'];
          }

          if (isset($this->request->get['order'])) {
          $url .= '&order=' . $this->request->get['order'];
          } */

        $url = '';

        if (isset($this->request->get['make_id'])) {
            $url .= '&make_id=' . $this->request->get['make_id'];
        }

        if (isset($this->request->get['model_id'])) {
            $url .= '&model_id=' . $this->request->get['model_id'];
        }

        if (isset($this->request->get['cat_id'])) {
            $url .= '&cat_id=' . $this->request->get['cat_id'];
        }

        if (isset($this->request->get['city_id'])) {
            $url .= '&city_id=' . $this->request->get['city_id'];
        }

        if (isset($this->request->get['area_id'])) {
            $url .= '&area_id=' . $this->request->get['area_id'];
        }

        if (isset($this->request->get['start_date'])) {
            $url .= '&start_date=' . $this->request->get['start_date'];
        }

        if (isset($this->request->get['start_time'])) {
            $url .= '&start_time=' . $this->request->get['start_time'];
        }

        if (isset($this->request->get['end_date'])) {
            $url .= '&end_date=' . $this->request->get['end_date'];
        }

        if (isset($this->request->get['end_time'])) {
            $url .= '&end_time=' . $this->request->get['end_time'];
        }

        if (isset($this->request->get['filters'])) {
            $url .= '&filters=' . $this->request->get['filters'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['limits'] = array();

        $limits = array_unique(array($this->config->get('config_product_limit'), 25, 50, 75, 100));

        sort($limits);

        foreach ($limits as $value) {
            $data['limits'][] = array(
                'text' => $value,
                'value' => $value,
                'href' => $this->url->link('product/listing' . $url . '&limit=' . $value)
            );
        }

        $this->load->model('catalog/filter');

        // Filters
        $filtergroups = $this->model_catalog_filter->getAllFilterGroups();

        $filters = $this->model_catalog_filter->getFilters();

        $data['filters'] = array();

        $data['allfilters'] = array();

        foreach ($filtergroups as $groups) {
            $data['allfilters'][] = array(
                'group_name' => $groups['name'],
                'filters' => $this->model_catalog_filter->getFilters($groups['filter_group_id'])
            );
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/listing', $searchurl . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        $data['sort'] = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        $data['continue'] = $this->url->link('common/home');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/listing.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/product/listing.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/product/listing.tpl', $data));
        }
    }

}
