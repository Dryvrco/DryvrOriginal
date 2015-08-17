<?php

class ControllerSellerAccountProduct extends ControllerSellerAccount {

    private $error = array();

    public function preadd() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_make'] = $this->language->get('entry_make');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('seller/account-product')
        );

        $data['action'] = $this->url->link('seller/account-product/add');

        $data['token'] = $this->session->data['token'];


        $this->load->model('catalog/category');

        $data['allcats'] = $this->model_catalog_category->getAllCategories(array('filter_model' => '1', 'filter_type' => 'mm'));

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/catalog/preproduct_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/catalog/preproduct_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/catalog/product_form.tpl', $data));
        }
    }

    public function add() {

        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if ($this->request->post['quantity']) {

            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
                $this->model_catalog_product->addProduct($this->request->post);

                $this->session->data['success'] = $this->language->get('text_success');

                $url = '';

                if (isset($this->request->get['filter_name'])) {
                    $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['filter_model'])) {
                    $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
                }

                if (isset($this->request->get['filter_price'])) {
                    $url .= '&filter_price=' . $this->request->get['filter_price'];
                }

                if (isset($this->request->get['filter_quantity'])) {
                    $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
                }

                if (isset($this->request->get['filter_status'])) {
                    $url .= '&filter_status=' . $this->request->get['filter_status'];
                }

                if (isset($this->request->get['sort'])) {
                    $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                    $url .= '&order=' . $this->request->get['order'];
                }

                if (isset($this->request->get['page'])) {
                    $url .= '&page=' . $this->request->get['page'];
                }

                $this->response->redirect($this->url->link('seller/account-product', 'token=' . $this->session->data['token'] . $url, 'SSL'));
            }
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['filter_name'])) {
                $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_model'])) {
                $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
            }

            if (isset($this->request->get['filter_price'])) {
                $url .= '&filter_price=' . $this->request->get['filter_price'];
            }

            if (isset($this->request->get['filter_quantity'])) {
                $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
            }

            if (isset($this->request->get['filter_status'])) {
                $url .= '&filter_status=' . $this->request->get['filter_status'];
            }

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('seller/account-product', $url));
        }

        if (($this->model_catalog_product->checkVehicle($this->request->get['product_id'], $this->agency->getSellerId())) == 0) {
            $this->response->redirect($this->url->link('seller/account-product', $url));
        } else {
            $this->getForm();
        }
    }

    protected function getForm() {

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['product_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_plus'] = $this->language->get('text_plus');
        $data['text_minus'] = $this->language->get('text_minus');
        $data['text_default'] = $this->language->get('text_default');
        $data['text_option'] = $this->language->get('text_option');
        $data['text_option_value'] = $this->language->get('text_option_value');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_percent'] = $this->language->get('text_percent');
        $data['text_amount'] = $this->language->get('text_amount');

        $data['tab_unavail'] = $this->language->get('tab_unavail');
        $data['entry_start_date'] = $this->language->get('entry_start_date');
        $data['entry_end_date'] = $this->language->get('entry_end_date');
        $data['entry_unavail_start'] = $this->language->get('entry_unavail_start');
        $data['entry_unavail_end'] = $this->language->get('entry_unavail_end');
        $data['button_unavail_add'] = $this->language->get('button_unavail_add');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_model'] = $this->language->get('entry_model');
        $data['entry_year'] = $this->language->get('entry_year');
        $data['entry_make'] = $this->language->get('entry_make');
        $data['entry_sku'] = $this->language->get('entry_sku');
        $data['entry_upc'] = $this->language->get('entry_upc');
        $data['entry_ean'] = $this->language->get('entry_ean');
        $data['entry_jan'] = $this->language->get('entry_jan');
        $data['entry_isbn'] = $this->language->get('entry_isbn');
        $data['entry_mpn'] = $this->language->get('entry_mpn');
        $data['entry_tags'] = $this->language->get('entry_tags');
        $data['entry_location'] = $this->language->get('entry_location');
        $data['entry_minimum'] = $this->language->get('entry_minimum');
        $data['entry_shipping'] = $this->language->get('entry_shipping');
        $data['entry_unavail_start'] = $this->language->get('entry_unavail_start');
        $data['entry_unavail_end'] = $this->language->get('entry_unavail_end');
        $data['entry_quantity'] = $this->language->get('entry_quantity');
        $data['entry_stock_status'] = $this->language->get('entry_stock_status');
        $data['entry_price'] = $this->language->get('entry_price');
        $data['entry_daily'] = $this->language->get('entry_daily');
        $data['entry_weekly'] = $this->language->get('entry_weekly');
        $data['entry_weekend'] = $this->language->get('entry_weekend');
        $data['entry_monthly'] = $this->language->get('entry_monthly');
        $data['entry_insurance'] = $this->language->get('entry_insurance');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_sub_category'] = $this->language->get('entry_sub_category');
        $data['entry_agency'] = $this->language->get('entry_agency');
        $data['entry_date'] = $this->language->get('entry_date');
        $data['entry_start_time'] = $this->language->get('entry_start_time');
        $data['entry_end_time'] = $this->language->get('entry_end_time');

        $data['entry_mileage'] = $this->language->get('entry_mileage');
        $data['entry_over_miles'] = $this->language->get('entry_over_miles');
        $data['entry_delivery'] = $this->language->get('entry_delivery');
        $data['entry_airport'] = $this->language->get('entry_airport');
        $data['entry_after_hours'] = $this->language->get('entry_after_hours');
        $data['entry_security'] = $this->language->get('entry_security');

        $data['entry_tax_class'] = $this->language->get('entry_tax_class');
        $data['entry_points'] = $this->language->get('entry_points');
        $data['entry_option_points'] = $this->language->get('entry_option_points');
        $data['entry_subtract'] = $this->language->get('entry_subtract');
        $data['entry_weight_class'] = $this->language->get('entry_weight_class');
        $data['entry_weight'] = $this->language->get('entry_weight');
        $data['entry_dimension'] = $this->language->get('entry_dimension');
        $data['entry_length_class'] = $this->language->get('entry_length_class');
        $data['entry_length'] = $this->language->get('entry_length');
        $data['entry_width'] = $this->language->get('entry_width');
        $data['entry_height'] = $this->language->get('entry_height');
        $data['entry_image'] = $this->language->get('entry_image');
        $data['entry_store'] = $this->language->get('entry_store');
        $data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
        $data['entry_download'] = $this->language->get('entry_download');
        $data['entry_category'] = $this->language->get('entry_category');
        $data['entry_filter'] = $this->language->get('entry_filter');
        $data['entry_related'] = $this->language->get('entry_related');
        $data['entry_attribute'] = $this->language->get('entry_attribute');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_option'] = $this->language->get('entry_option');
        $data['entry_option_value'] = $this->language->get('entry_option_value');
        $data['entry_required'] = $this->language->get('entry_required');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_start'] = $this->language->get('entry_date_start');
        $data['entry_date_end'] = $this->language->get('entry_date_end');
        $data['entry_priority'] = $this->language->get('entry_priority');
        $data['entry_tag'] = $this->language->get('entry_tag');
        $data['entry_customer_group'] = $this->language->get('entry_customer_group');
        $data['entry_reward'] = $this->language->get('entry_reward');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_recurring'] = $this->language->get('entry_recurring');

        $data['help_keyword'] = $this->language->get('help_keyword');
        $data['help_sku'] = $this->language->get('help_sku');
        $data['help_upc'] = $this->language->get('help_upc');
        $data['help_ean'] = $this->language->get('help_ean');
        $data['help_jan'] = $this->language->get('help_jan');
        $data['help_isbn'] = $this->language->get('help_isbn');
        $data['help_mpn'] = $this->language->get('help_mpn');
        $data['help_minimum'] = $this->language->get('help_minimum');
        $data['help_manufacturer'] = $this->language->get('help_manufacturer');
        $data['help_stock_status'] = $this->language->get('help_stock_status');
        $data['help_points'] = $this->language->get('help_points');
        $data['help_category'] = $this->language->get('help_category');
        $data['help_filter'] = $this->language->get('help_filter');
        $data['help_download'] = $this->language->get('help_download');
        $data['help_related'] = $this->language->get('help_related');
        $data['help_tag'] = $this->language->get('help_tag');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_attribute_add'] = $this->language->get('button_attribute_add');
        $data['button_option_add'] = $this->language->get('button_option_add');
        $data['button_option_value_add'] = $this->language->get('button_option_value_add');
        $data['button_discount_add'] = $this->language->get('button_discount_add');
        $data['button_special_add'] = $this->language->get('button_special_add');
        $data['button_image_add'] = $this->language->get('button_image_add');
        $data['button_remove'] = $this->language->get('button_remove');
        $data['button_recurring_add'] = $this->language->get('button_recurring_add');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_data'] = $this->language->get('tab_data');
        $data['tab_attribute'] = $this->language->get('tab_attribute');
        $data['tab_option'] = $this->language->get('tab_option');
        $data['tab_recurring'] = $this->language->get('tab_recurring');
        $data['tab_discount'] = $this->language->get('tab_discount');
        $data['tab_special'] = $this->language->get('tab_special');
        $data['tab_image'] = $this->language->get('tab_image');
        $data['tab_links'] = $this->language->get('tab_links');
        $data['tab_reward'] = $this->language->get('tab_reward');
        $data['tab_design'] = $this->language->get('tab_design');
        $data['tab_openbay'] = $this->language->get('tab_openbay');
        $data['tab_booking'] = $this->language->get('tab_booking');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['model'])) {
            $data['error_model'] = $this->error['model'];
        } else {
            $data['error_model'] = '';
        }

        if (isset($this->error['make_id'])) {
            $data['error_make_id'] = $this->error['make_id'];
        } else {
            $data['error_make_id'] = '';
        }

        if (isset($this->error['date_available'])) {
            $data['error_date_available'] = $this->error['date_available'];
        } else {
            $data['error_date_available'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('multiseller/product', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['product_id'])) {
            $data['action'] = $this->url->link('seller/account-product/add', 'token=' . $this->session->data['token'] . $url);
        } else {
            $data['action'] = $this->url->link('seller/account-product/edit&product_id=' . $this->request->get['product_id'] . $url);
        }

        $data['delete'] = $this->url->link('seller/account-product/delete&product_id=' . $this->request->get['product_id'] . $url);

        $data['cancel'] = $this->url->link('seller/account-product');

        if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        }

        if ($this->request->post['make_id']) {
            $cat = $this->request->post['make_id'];
        }
        if ($this->request->post['model_id']) {
            $cat = $this->request->post['model_id'];
        }
        
        if ($this->request->get['product_id']) {
            $data['del'] = '1';
        }

        $this->load->model('catalog/category');

        $category_info = $this->model_catalog_category->getCategory($cat);

        $data['token'] = $this->session->data['token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $category_description = $this->model_catalog_category->getProductDescriptions($cat);

        if (isset($this->request->post['product_description'])) {
            $data['product_description'] = $this->request->post['product_description'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_description'] = $this->model_catalog_product->getProductDescriptions($this->request->get['product_id']);
        } elseif (!empty($category_info)) {
            $data['product_description'] = $category_description;
        } else {
            $data['product_description'] = array();
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($product_info)) {
            $data['image'] = $product_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['model'])) {
            $data['model'] = $this->request->post['model'];
        } elseif (!empty($product_info)) {
            $data['model'] = $product_info['model'];
        } elseif (!empty($category_info)) {
            $data['model'] = $category_info['model'];
        } else {
            $data['model'] = '';
        }

        if (isset($this->request->post['make_id'])) {
            $data['make_id'] = $this->request->post['make_id'];
        } elseif (!empty($product_info)) {
            $data['make_id'] = $product_info['make_id'];
        } else {
            $data['make_id'] = '0';
        }

        if (isset($this->request->post['model_id'])) {
            $data['model_id'] = $this->request->post['model_id'];
        } elseif (!empty($product_info)) {
            $data['model_id'] = $product_info['model_id'];
        } else {
            $data['model_id'] = '0';
        }

        if (isset($this->request->post['cat_id'])) {
            $data['cat_id'] = $this->request->post['cat_id'];
        } elseif (!empty($product_info)) {
            $data['cat_id'] = $product_info['cat_id'];
        } elseif (!empty($category_info)) {
            $data['cat_id'] = $category_info['cat_id'];
        } else {
            $data['cat_id'] = '0';
        }

        if (isset($this->request->post['subcat_id'])) {
            $data['subcat_id'] = $this->request->post['subcat_id'];
        } elseif (!empty($product_info)) {
            $data['subcat_id'] = $product_info['subcat_id'];
        } elseif (!empty($category_info)) {
            $data['subcat_id'] = $category_info['subcat_id'];
        } else {
            $data['subcat_id'] = '0';
        }

        if (isset($this->request->post['daily'])) {
            $data['daily'] = $this->request->post['daily'];
        } elseif (!empty($product_info)) {
            $data['daily'] = $product_info['daily'];
        } elseif (!empty($category_info)) {
            $data['daily'] = $category_info['daily'];
        } else {
            $data['daily'] = '';
        }

        if (isset($this->request->post['weekly'])) {
            $data['weekly'] = $this->request->post['weekly'];
        } elseif (!empty($product_info)) {
            $data['weekly'] = $product_info['weekly'];
        } elseif (!empty($category_info)) {
            $data['weekly'] = $category_info['weekly'];
        } else {
            $data['weekly'] = '';
        }

        if (isset($this->request->post['weekend'])) {
            $data['weekend'] = $this->request->post['weekend'];
        } elseif (!empty($product_info)) {
            $data['weekend'] = $product_info['weekend'];
        } elseif (!empty($category_info)) {
            $data['weekend'] = $category_info['weekend'];
        } else {
            $data['weekend'] = '';
        }

        if (isset($this->request->post['monthly'])) {
            $data['monthly'] = $this->request->post['monthly'];
        } elseif (!empty($product_info)) {
            $data['monthly'] = $product_info['monthly'];
        } elseif (!empty($category_info)) {
            $data['monthly'] = $category_info['monthly'];
        } else {
            $data['monthly'] = '';
        }

        if (isset($this->request->post['insurance'])) {
            $data['insurance'] = $this->request->post['insurance'];
        } elseif (!empty($product_info)) {
            $data['insurance'] = $product_info['insurance'];
        } else {
            $data['insurance'] = '';
        }
        
        if (isset($this->request->post['min_age'])) {
            $data['min_age'] = $this->request->post['min_age'];
        } elseif (!empty($product_info)) {
            $data['min_age'] = $product_info['min_age'];
        } else {
            $data['min_age'] = '';
        }

        if (isset($this->request->post['tags'])) {
            $data['tags'] = $this->request->post['tags'];
        } elseif (!empty($product_info)) {
            $data['tags'] = $product_info['tags'];
        } elseif (!empty($category_info)) {
            $data['tags'] = $category_info['tags'];
        } else {
            $data['tags'] = '';
        }

        if (isset($this->request->post['mileage'])) {
            $data['mileage'] = $this->request->post['mileage'];
        } elseif (!empty($product_info)) {
            $data['mileage'] = $product_info['mileage'];
        } elseif (!empty($category_info)) {
            $data['mileage'] = $category_info['mileage'];
        } else {
            $data['mileage'] = '';
        }

        if (isset($this->request->post['over_miles'])) {
            $data['over_miles'] = $this->request->post['over_miles'];
        } elseif (!empty($product_info)) {
            $data['over_miles'] = $product_info['over_miles'];
        } else {
            $data['over_miles'] = '';
        }

        if (isset($this->request->post['delivery'])) {
            $data['delivery'] = $this->request->post['delivery'];
        } elseif (!empty($product_info)) {
            $data['delivery'] = $product_info['delivery'];
        } elseif (!empty($category_info)) {
            $data['delivery'] = $category_info['delivery'];
        } else {
            $data['delivery'] = '';
        }

        if (isset($this->request->post['airport'])) {
            $data['airport'] = $this->request->post['airport'];
        } elseif (!empty($product_info)) {
            $data['airport'] = $product_info['airport'];
        } elseif (!empty($category_info)) {
            $data['airport'] = $category_info['airport'];
        } else {
            $data['airport'] = '';
        }

        if (isset($this->request->post['after_hours'])) {
            $data['after_hours'] = $this->request->post['after_hours'];
        } elseif (!empty($product_info)) {
            $data['after_hours'] = $product_info['after_hours'];
        } else {
            $data['after_hours'] = '';
        }

        $this->load->model('localisation/city');

        $data['zones'] = $this->model_localisation_city->getZonesList($product_info['country_id']);

        $data['cities'] = $this->model_localisation_city->getCitiesList($product_info['zone_id']);
        
        $data['areas'] = $this->model_localisation_city->getAreasList($product_info['city_id']);

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if (isset($this->request->post['country_id'])) {
            $data['country_id'] = $this->request->post['country_id'];
        } elseif (!empty($product_info)) {
            $data['country_id'] = $product_info['country_id'];
        } else {
            $data['country_id'] = '';
        }

        if (isset($this->request->post['zone_id'])) {
            $data['zone_id'] = $this->request->post['zone_id'];
        } elseif (!empty($product_info)) {
            $data['zone_id'] = $product_info['zone_id'];
        } else {
            $data['zone_id'] = '';
        }

        if (isset($this->request->post['city_id'])) {
            $data['city_id'] = $this->request->post['city_id'];
        } elseif (!empty($product_info)) {
            $data['city_id'] = $product_info['city_id'];
        } else {
            $data['city_id'] = '';
        }

        if (isset($this->request->post['area_id'])) {
            $data['area_id'] = $this->request->post['area_id'];
        } elseif (!empty($product_info)) {
            $data['area_id'] = $product_info['area_id'];
        } else {
            $data['area_id'] = '';
        }

        if (isset($this->request->post['security'])) {
            $data['security'] = $this->request->post['security'];
        } elseif (!empty($product_info)) {
            $data['security'] = $product_info['security'];
        } elseif (!empty($category_info)) {
            $data['security'] = $category_info['security'];
        } else {
            $data['security'] = '';
        }

        if (isset($this->request->post['sku'])) {
            $data['sku'] = $this->request->post['sku'];
        } elseif (!empty($product_info)) {
            $data['sku'] = $product_info['sku'];
        } else {
            $data['sku'] = '';
        }

        if (isset($this->request->post['upc'])) {
            $data['upc'] = $this->request->post['upc'];
        } elseif (!empty($product_info)) {
            $data['upc'] = $product_info['upc'];
        } else {
            $data['upc'] = '';
        }

        if (isset($this->request->post['ean'])) {
            $data['ean'] = $this->request->post['ean'];
        } elseif (!empty($product_info)) {
            $data['ean'] = $product_info['ean'];
        } else {
            $data['ean'] = '';
        }

        if (isset($this->request->post['jan'])) {
            $data['jan'] = $this->request->post['jan'];
        } elseif (!empty($product_info)) {
            $data['jan'] = $product_info['jan'];
        } else {
            $data['jan'] = '';
        }

        if (isset($this->request->post['isbn'])) {
            $data['isbn'] = $this->request->post['isbn'];
        } elseif (!empty($product_info)) {
            $data['isbn'] = $product_info['isbn'];
        } else {
            $data['isbn'] = '';
        }

        if (isset($this->request->post['mpn'])) {
            $data['mpn'] = $this->request->post['mpn'];
        } elseif (!empty($product_info)) {
            $data['mpn'] = $product_info['mpn'];
        } else {
            $data['mpn'] = '';
        }

        if (isset($this->request->post['location'])) {
            $data['location'] = $this->request->post['location'];
        } elseif (!empty($product_info)) {
            $data['location'] = $product_info['location'];
        } else {
            $data['location'] = '';
        }

        if (isset($this->request->post['keyword'])) {
            $data['keyword'] = $this->request->post['keyword'];
        } elseif (!empty($product_info)) {
            $data['keyword'] = $product_info['keyword'];
        } else {
            $data['keyword'] = '';
        }

        if (isset($this->request->post['shipping'])) {
            $data['shipping'] = $this->request->post['shipping'];
        } elseif (!empty($product_info)) {
            $data['shipping'] = $product_info['shipping'];
        } else {
            $data['shipping'] = 1;
        }

        if (isset($this->request->post['price'])) {
            $data['price'] = $this->request->post['price'];
        } elseif (!empty($product_info)) {
            $data['price'] = $product_info['price'];
        } else {
            $data['price'] = '';
        }

        if (isset($this->request->post['product_unavail'])) {
            $data['product_unavails'] = $this->request->post['product_unavail'];
        } elseif (!empty($product_info)) {
            $data['product_unavails'] = $this->model_catalog_product->getProductUnavailable($this->request->get['product_id']);
        } else {
            $data['product_unavails'] = '';
        }

        $this->load->model('catalog/recurring');

        $data['recurrings'] = $this->model_catalog_recurring->getRecurrings();

        if (isset($this->request->post['product_recurrings'])) {
            $data['product_recurrings'] = $this->request->post['product_recurrings'];
        } elseif (!empty($product_info)) {
            $data['product_recurrings'] = $this->model_catalog_product->getRecurrings($product_info['product_id']);
        } else {
            $data['product_recurrings'] = array();
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['tax_class_id'])) {
            $data['tax_class_id'] = $this->request->post['tax_class_id'];
        } elseif (!empty($product_info)) {
            $data['tax_class_id'] = $product_info['tax_class_id'];
        } else {
            $data['tax_class_id'] = 0;
        }

        if (isset($this->request->post['quantity'])) {
            $data['quantity'] = $this->request->post['quantity'];
        } elseif (!empty($product_info)) {
            $data['quantity'] = $product_info['quantity'];
        } else {
            $data['quantity'] = 1;
        }

        if (isset($this->request->post['minimum'])) {
            $data['minimum'] = $this->request->post['minimum'];
        } elseif (!empty($product_info)) {
            $data['minimum'] = $product_info['minimum'];
        } else {
            $data['minimum'] = 1;
        }

        if (isset($this->request->post['subtract'])) {
            $data['subtract'] = $this->request->post['subtract'];
        } elseif (!empty($product_info)) {
            $data['subtract'] = $product_info['subtract'];
        } else {
            $data['subtract'] = 1;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($product_info)) {
            $data['sort_order'] = $product_info['sort_order'];
        } else {
            $data['sort_order'] = 1;
        }

        $this->load->model('localisation/stock_status');

        $data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

        if (isset($this->request->post['stock_status_id'])) {
            $data['stock_status_id'] = $this->request->post['stock_status_id'];
        } elseif (!empty($product_info)) {
            $data['stock_status_id'] = $product_info['stock_status_id'];
        } else {
            $data['stock_status_id'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($product_info)) {
            $data['status'] = $product_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['weight'])) {
            $data['weight'] = $this->request->post['weight'];
        } elseif (!empty($product_info)) {
            $data['weight'] = $product_info['weight'];
        } else {
            $data['weight'] = '';
        }

        $this->load->model('localisation/weight_class');

        $data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

        if (isset($this->request->post['weight_class_id'])) {
            $data['weight_class_id'] = $this->request->post['weight_class_id'];
        } elseif (!empty($product_info)) {
            $data['weight_class_id'] = $product_info['weight_class_id'];
        } else {
            $data['weight_class_id'] = $this->config->get('config_weight_class_id');
        }

        if (isset($this->request->post['length'])) {
            $data['length'] = $this->request->post['length'];
        } elseif (!empty($product_info)) {
            $data['length'] = $product_info['length'];
        } else {
            $data['length'] = '';
        }

        if (isset($this->request->post['width'])) {
            $data['width'] = $this->request->post['width'];
        } elseif (!empty($product_info)) {
            $data['width'] = $product_info['width'];
        } else {
            $data['width'] = '';
        }

        if (isset($this->request->post['agency_id'])) {
            $data['agency_id'] = $this->request->post['agency_id'];
        } elseif (!empty($product_info)) {
            $data['agency_id'] = $product_info['seller_id'];
        } else {
            $data['agency_id'] = '';
        }

        if (isset($this->request->post['height'])) {
            $data['height'] = $this->request->post['height'];
        } elseif (!empty($product_info)) {
            $data['height'] = $product_info['height'];
        } else {
            $data['height'] = '';
        }

        $this->load->model('localisation/length_class');

        $data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

        if (isset($this->request->post['length_class_id'])) {
            $data['length_class_id'] = $this->request->post['length_class_id'];
        } elseif (!empty($product_info)) {
            $data['length_class_id'] = $product_info['length_class_id'];
        } else {
            $data['length_class_id'] = $this->config->get('config_length_class_id');
        }

        $this->load->model('catalog/manufacturer');

        if (isset($this->request->post['manufacturer_id'])) {
            $data['manufacturer_id'] = $this->request->post['manufacturer_id'];
        } elseif (!empty($product_info)) {
            $data['manufacturer_id'] = $product_info['manufacturer_id'];
        } else {
            $data['manufacturer_id'] = 0;
        }

        if (isset($this->request->post['manufacturer'])) {
            $data['manufacturer'] = $this->request->post['manufacturer'];
        } elseif (!empty($product_info)) {
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

            if ($manufacturer_info) {
                $data['manufacturer'] = $manufacturer_info['name'];
            } else {
                $data['manufacturer'] = '';
            }
        } else {
            $data['manufacturer'] = '';
        }

        $this->load->model('catalog/category');

        $data['allcats'] = $this->model_catalog_category->getAllCategories(array('filter_model' => '1', 'filter_type' => 'mm'));

        $data['secondcats'] = $this->model_catalog_category->getCats();


        // Categories
        $this->load->model('catalog/category');

        if (isset($this->request->post['product_category'])) {
            $categories = $this->request->post['product_category'];
        } elseif (isset($this->request->get['product_id'])) {
            $categories = $this->model_catalog_product->getProductCategories($this->request->get['product_id']);
        } else {
            $categories = array();
        }

        $data['product_categories'] = array();

        foreach ($categories as $category_id) {
            $category_info = $this->model_catalog_category->getCategory($category_id);

            if ($category_info) {
                $data['product_categories'][] = array(
                    'category_id' => $category_info['category_id'],
                    'name' => ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name']
                );
            }
        }

        // Filters
        $this->load->model('catalog/filter');

        if (isset($this->request->post['product_filter'])) {
            $filters = $this->request->post['product_filter'];
        } elseif (isset($this->request->get['product_id'])) {
            $filters = $this->model_catalog_product->getProductFilters($this->request->get['product_id']);
        } else {
            $filters = array();
        }

        $data['product_filters'] = array();

        foreach ($filters as $filter_id) {
            $filter_info = $this->model_catalog_filter->getFilter($filter_id);

            if ($filter_info) {
                $data['product_filters'][] = array(
                    'filter_id' => $filter_info['filter_id'],
                    'name' => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                );
            }
        }

        // Attributes
        $this->load->model('catalog/attribute');

        if (isset($this->request->post['product_attribute'])) {
            $product_attributes = $this->request->post['product_attribute'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);
        } else {
            $product_attributes = array();
        }

        $data['product_attributes'] = array();

        foreach ($product_attributes as $product_attribute) {
            $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

            if ($attribute_info) {
                $data['product_attributes'][] = array(
                    'attribute_id' => $product_attribute['attribute_id'],
                    'name' => $attribute_info['name'],
                    'product_attribute_description' => $product_attribute['product_attribute_description']
                );
            }
        }

        // Options
        $this->load->model('catalog/option');

        if (isset($this->request->post['product_option'])) {
            $product_options = $this->request->post['product_option'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);
        } else {
            $product_options = array();
        }

        $data['product_options'] = array();

        foreach ($product_options as $product_option) {
            $product_option_value_data = array();

            if (isset($product_option['product_option_value'])) {
                foreach ($product_option['product_option_value'] as $product_option_value) {
                    $product_option_value_data[] = array(
                        'product_option_value_id' => $product_option_value['product_option_value_id'],
                        'option_value_id' => $product_option_value['option_value_id'],
                        'quantity' => $product_option_value['quantity'],
                        'subtract' => $product_option_value['subtract'],
                        'price' => $product_option_value['price'],
                        'price_prefix' => $product_option_value['price_prefix'],
                        'points' => $product_option_value['points'],
                        'points_prefix' => $product_option_value['points_prefix'],
                        'weight' => $product_option_value['weight'],
                        'weight_prefix' => $product_option_value['weight_prefix']
                    );
                }
            }

            $data['product_options'][] = array(
                'product_option_id' => $product_option['product_option_id'],
                'product_option_value' => $product_option_value_data,
                'option_id' => $product_option['option_id'],
                'name' => $product_option['name'],
                'type' => $product_option['type'],
                'value' => isset($product_option['value']) ? $product_option['value'] : '',
                'required' => $product_option['required']
            );
        }

        $data['option_values'] = array();

        foreach ($data['product_options'] as $product_option) {
            if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
                if (!isset($data['option_values'][$product_option['option_id']])) {
                    $data['option_values'][$product_option['option_id']] = $this->model_catalog_option->getOptionValues($product_option['option_id']);
                }
            }
        }

        $this->load->model('sale/customer_group');

        $data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

        if (isset($this->request->post['product_discount'])) {
            $product_discounts = $this->request->post['product_discount'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);
        } else {
            $product_discounts = array();
        }

        $data['product_discounts'] = array();

        foreach ($product_discounts as $product_discount) {
            $data['product_discounts'][] = array(
                'customer_group_id' => $product_discount['customer_group_id'],
                'quantity' => $product_discount['quantity'],
                'priority' => $product_discount['priority'],
                'price' => $product_discount['price'],
                'date_start' => ($product_discount['date_start'] != '0000-00-00') ? $product_discount['date_start'] : '',
                'date_end' => ($product_discount['date_end'] != '0000-00-00') ? $product_discount['date_end'] : ''
            );
        }

        if (isset($this->request->post['product_special'])) {
            $product_specials = $this->request->post['product_special'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_specials = $this->model_catalog_product->getProductSpecials($this->request->get['product_id']);
        } else {
            $product_specials = array();
        }

        $data['product_specials'] = array();

        foreach ($product_specials as $product_special) {
            $data['product_specials'][] = array(
                'customer_group_id' => $product_special['customer_group_id'],
                'priority' => $product_special['priority'],
                'price' => $product_special['price'],
                'date_start' => ($product_special['date_start'] != '0000-00-00') ? $product_special['date_start'] : '',
                'date_end' => ($product_special['date_end'] != '0000-00-00') ? $product_special['date_end'] : ''
            );
        }

        // Images
        if (isset($this->request->post['product_image'])) {
            $product_images = $this->request->post['product_image'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
        } else {
            $product_images = array();
        }

        $data['product_images'] = array();

        foreach ($product_images as $product_image) {
            if (is_file(DIR_IMAGE . $product_image['image'])) {
                $image = $product_image['image'];
                $thumb = $product_image['image'];
            } else {
                $image = '';
                $thumb = 'no_image.png';
            }

            $data['product_images'][] = array(
                'image' => $image,
                'thumb' => $this->model_tool_image->resize($thumb, 100, 100),
                'sort_order' => $product_image['sort_order']
            );
        }

        // Downloads
        $this->load->model('catalog/download');

        if (isset($this->request->post['product_download'])) {
            $product_downloads = $this->request->post['product_download'];
        } elseif (isset($this->request->get['product_id'])) {
            $product_downloads = $this->model_catalog_product->getProductDownloads($this->request->get['product_id']);
        } else {
            $product_downloads = array();
        }

        $data['product_downloads'] = array();

        foreach ($product_downloads as $download_id) {
            $download_info = $this->model_catalog_download->getDownload($download_id);

            if ($download_info) {
                $data['product_downloads'][] = array(
                    'download_id' => $download_info['download_id'],
                    'name' => $download_info['name']
                );
            }
        }

        if (isset($this->request->post['product_related'])) {
            $products = $this->request->post['product_related'];
        } elseif (isset($this->request->get['product_id'])) {
            $products = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);
        } else {
            $products = array();
        }

        $data['product_relateds'] = array();

        foreach ($products as $product_id) {
            $related_info = $this->model_catalog_product->getProduct($product_id);

            if ($related_info) {
                $data['product_relateds'][] = array(
                    'product_id' => $related_info['product_id'],
                    'name' => $related_info['name']
                );
            }
        }

        if (isset($this->request->post['points'])) {
            $data['points'] = $this->request->post['points'];
        } elseif (!empty($product_info)) {
            $data['points'] = $product_info['points'];
        } else {
            $data['points'] = '';
        }

        if (isset($this->request->post['product_reward'])) {
            $data['product_reward'] = $this->request->post['product_reward'];
        } elseif (isset($this->request->get['product_id'])) {
            $data['product_reward'] = $this->model_catalog_product->getProductRewards($this->request->get['product_id']);
        } else {
            $data['product_reward'] = array();
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/catalog/product_form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/catalog/product_form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/catalog/product_form.tpl', $data));
        }
    }

    public function getTableData() {
        $colMap = array(
            'product_status' => '`mp.product_status`',
            'date_created' => '`p.date_created`',
            'number_sold' => 'mp.number_sold',
            'product_price' => 'p.price',
            'daily' => 'p.daily',
            'weekly' => 'p.weekly',
            'weekend' => 'p.weekend',
        );

        $sorts = array('daily', 'weekly', 'weekend', 'date_created', 'product_status', 'product_earnings', 'number_sold');

        list($sortCol, $sortDir) = $this->MsLoader->MsHelper->getSortParams($sorts, $colMap);

        $seller_id = $this->agency->getSellerId();
        $products = $this->MsLoader->MsProduct->getProducts(
                array(
            'seller_id' => $seller_id,
            'language_id' => $this->config->get('config_language_id'),
            'product_status' => array(MsProduct::STATUS_ACTIVE, MsProduct::STATUS_INACTIVE, MsProduct::STATUS_DISABLED, MsProduct::STATUS_UNPAID)
                ), array(
            'order_by' => $sortCol,
            'order_way' => $sortDir,
            'offset' => $this->request->get['iDisplayStart'],
            'limit' => $this->request->get['iDisplayLength']
                ), array(
            'product_earnings' => 1
                )
        );

        $total = isset($products[0]) ? $products[0]['total_rows'] : 0;

        $columns = array();
        foreach ($products as $product) {
            $sale_data = $this->MsLoader->MsProduct->getSaleData($product['product_id']);

            // special price
            $specials = $this->MsLoader->MsProduct->getProductSpecials($product['product_id']);
            $special = false;
            foreach ($specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || $product_special['date_start'] < date('Y-m-d')) && ($product_special['date_end'] == '0000-00-00' || $product_special['date_end'] > date('Y-m-d'))) {
                    $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));
                    break;
                }
            }

            // price
            $product['p.price'] = $this->currency->format($product['p.price'], $this->config->get('config_currency'));
            if ($special) {
                $price = "<span style='text-decoration: line-through;'>{$product['p.price']}></span><br/>";
                $price .= "<span class='special-price' style='color: #b00;'>$special</span>";
            } else {
                $price = $product['p.price'];
            }


            $daily = $this->currency->format($product['p.daily'], $this->config->get('config_currency'));
            $weekly = $this->currency->format($product['p.weekly'], $this->config->get('config_currency'));
            $weekend = $this->currency->format($product['p.weekend'], $this->config->get('config_currency'));

            // Product Image
            if ($product['p.image'] && file_exists(DIR_IMAGE . $product['p.image'])) {
                $image = $this->MsLoader->MsFile->resizeImage($product['p.image'], $this->config->get('msconf_product_seller_product_list_seller_area_image_width'), $this->config->get('msconf_product_seller_product_list_seller_area_image_height'));
            } else {
                $image = $this->MsLoader->MsFile->resizeImage('no_image.png', $this->config->get('msconf_product_seller_product_list_seller_area_image_width'), $this->config->get('msconf_product_seller_product_list_seller_area_image_height'));
            }

            $this->load->model('catalog/category');

            $detail = '';
            $year = '';

            if ($product['p.make_id']) {
                $make = $this->model_catalog_category->getCategory($product['p.make_id']);
                $detail = $make['name'];
            }

            if ($product['p.model_id']) {
                $model = $this->model_catalog_category->getCategory($product['p.model_id']);
                $detail .= ' ' . $model['name'];
            }

            if ($product['p.model']) {
                $year = ' ' . $product['p.model'];
            }

            // actions
            $actions = "";
            if ($product['mp.product_status'] != MsProduct::STATUS_DISABLED) {
                if ($product['mp.product_status'] == MsProduct::STATUS_ACTIVE)
                    $actions .= "<a data-toggle='tooltip' href='" . $this->url->link('product/product', 'product_id=' . $product['product_id'], 'SSL') . "' class='btn btn-primary' title='" . $this->language->get('ms_viewinstore') . "'><i class='glyphicon glyphicon-search'></i></a>";

                if ($product['mp.product_approved']) {
                    if ($product['mp.product_status'] == MsProduct::STATUS_INACTIVE)
                        $actions .= "<a data-toggle='tooltip' href='" . $this->url->link('seller/account-product/publish', 'product_id=' . $product['product_id'], 'SSL') . "' class='btn btn-primary' title='" . $this->language->get('ms_publish') . "'><i class='glyphicon glyphicon-plus'></i></a>";

                    if ($product['mp.product_status'] == MsProduct::STATUS_ACTIVE)
                        $actions .= "&nbsp;<a data-toggle='tooltip' href='" . $this->url->link('seller/account-product/unpublish', 'product_id=' . $product['product_id'], 'SSL') . "' class='btn btn-danger' title='" . $this->language->get('ms_unpublish') . "'><i class='glyphicon glyphicon-minus'></i></a>";
                }
                $actions .= "&nbsp;<a data-toggle='tooltip' href='" . $this->url->link('seller/account-product/edit', 'product_id=' . $product['product_id'], 'SSL') . "' class='btn btn-primary' title='" . $this->language->get('ms_edit') . "'><i class='fa fa-pencil'></i></a>";
                $actions .= "&nbsp;<a data-toggle='tooltip' href='" . $this->url->link('seller/account-product/view', 'product_id=' . $product['product_id'], 'SSL') . "' class='btn btn-primary' title='" . $this->language->get('ms_view_reservations') . "'><i class='fa fa-calendar'></i></a>";
            } else {
                if ($this->config->get('msconf_allow_relisting')) {
                    $actions .= "&nbsp;<a data-toggle='tooltip' href='" . $this->url->link('seller/account-product/update', 'product_id=' . $product['product_id'] . "&relist=1", 'SSL') . "' class='ms-button ms-button-relist' title='" . $this->language->get('ms_relist') . "'></a>";
                }
            }
            // product status
            $status = "";
            if ($product['mp.product_status'] == MsProduct::STATUS_ACTIVE) {
                $status = "<span class='active' style='color: #080;'>" . $this->language->get('ms_product_status_' . $product['mp.product_status']) . "</td></span>";
            } else {
                $status = "<span class='inactive' style='color: #b00;'>" . $this->language->get('ms_product_status_' . $product['mp.product_status']) . "</td></span>";
            }

            // List until
            if (isset($product['mp.list_until']) && $product['mp.list_until'] != NULL) {
                $list_until = date($this->language->get('date_format_short'), strtotime($product['mp.list_until']));
            } else {
                $list_until = $this->language->get('ms_not_defined');
            }

            $columns[] = array_merge(
                    $product, array(
                'image' => "<img src='$image' style='padding: 1px; border: 1px solid #DDDDDD' />",
                'product_name' => $detail . $year,
                'daily' => $daily,
                'weekly' => $weekly,
                'weekend' => $weekend,
                'number_sold' => $product['mp.number_sold'],
                'product_earnings' => $this->currency->format($sale_data['seller_total'], $this->config->get('config_currency')),
                'product_status' => $status,
                'date_created' => date($this->language->get('date_format_short'), strtotime($product['p.date_created'])),
                'list_until' => $list_until,
                'actions' => $actions
                    )
            );
        }

        $this->response->setOutput(json_encode(array(
            'iTotalRecords' => $total,
            'iTotalDisplayRecords' => $total,
            'aaData' => $columns
        )));
    }

    public function jxUpdateFile() {
        $json = array();
        $json['errors'] = $this->MsLoader->MsFile->checkPostMax($_POST, $_FILES);

        if ($json['errors']) {
            return $this->response->setOutput(json_encode($json));
        }

        if (isset($this->request->post['file_id']) && isset($this->request->post['product_id'])) {
            $download_id = (int) substr($this->request->post['file_id'], strrpos($this->request->post['file_id'], '-') + 1);
            $product_id = (int) $this->request->post['product_id'];
            $seller_id = $this->customer->getId();
            if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id) && $this->MsLoader->MsProduct->hasDownload($product_id, $download_id)) {
                $file = array_shift($_FILES);
                $errors = $this->MsLoader->MsFile->checkDownload($file);

                if ($errors) {
                    $json['errors'] = array_merge($json['errors'], $errors);
                } else {
                    $fileData = $this->MsLoader->MsFile->uploadDownload($file);
                    $json['fileName'] = $fileData['fileName'];
                    $json['fileMask'] = $fileData['fileMask'];
                }
            }
        }

        return $this->response->setOutput(json_encode($json));
    }

    public function jxUploadSellerAvatar() {
        $json = array();
        $file = array();

        $json['errors'] = $this->MsLoader->MsFile->checkPostMax($_POST, $_FILES);

        if ($json['errors']) {
            return $this->response->setOutput(json_encode($json));
        }

        foreach ($_FILES as $file) {
            $errors = $this->MsLoader->MsFile->checkImage($file);

            if ($errors) {
                $json['errors'] = array_merge($json['errors'], $errors);
            } else {
                $fileName = $this->MsLoader->MsFile->uploadImage($file);
                $thumbUrl = $this->MsLoader->MsFile->resizeImage($this->config->get('msconf_temp_image_path') . $fileName, $this->config->get('msconf_preview_product_image_width'), $this->config->get('msconf_preview_product_image_height'));
                $json['files'][] = array(
                    'name' => $fileName,
                    'thumb' => $thumbUrl
                );
            }
        }

        return $this->response->setOutput(json_encode($json));
    }

    public function jxUploadImages() {
        $json = array();
        $file = array();
        $json['errors'] = $this->MsLoader->MsFile->checkPostMax($_POST, $_FILES);

        if ($json['errors']) {
            return $this->response->setOutput(json_encode($json));
        }

        // allow a maximum of N images
        $msconf_images_limits = $this->config->get('msconf_images_limits');
        foreach ($_FILES as $file) {
            if ($msconf_images_limits[1] > 0 && $this->request->post['fileCount'] >= $msconf_images_limits[1]) {
                $json['errors'][] = sprintf($this->language->get('ms_error_product_image_maximum'), $msconf_images_limits[1]);
                $json['cancel'] = 1;
                $this->response->setOutput(json_encode($json));
                return;
            } else {
                $errors = $this->MsLoader->MsFile->checkImage($file);

                if ($errors) {
                    $json['errors'] = array_merge($json['errors'], $errors);
                } else {
                    $fileName = $this->MsLoader->MsFile->uploadImage($file);

                    $thumbUrl = $this->MsLoader->MsFile->resizeImage($this->config->get('msconf_temp_image_path') . $fileName, $this->config->get('msconf_preview_product_image_width'), $this->config->get('msconf_preview_product_image_height'));
                    $json['files'][] = array(
                        'name' => $fileName,
                        'thumb' => $thumbUrl
                    );
                }
            }
        }

        return $this->response->setOutput(json_encode($json));
    }

    public function jxUploadDownloads() {
        $json = array();
        $file = array();

        $json['errors'] = $this->MsLoader->MsFile->checkPostMax($_POST, $_FILES);

        if ($json['errors']) {
            return $this->response->setOutput(json_encode($json));
        }

        // allow a maximum of N images
        $msconf_downloads_limits = $this->config->get('msconf_downloads_limits');
        foreach ($_FILES as $file) {
            if ($msconf_downloads_limits[1] > 0 && $this->request->post['fileCount'] >= $msconf_downloads_limits[1]) {
                $json['errors'][] = sprintf($this->language->get('ms_error_product_download_maximum'), $msconf_downloads_limits[1]);
                $json['cancel'] = 1;
                $this->response->setOutput(json_encode($json));
                return;
            } else {
                $errors = $this->MsLoader->MsFile->checkDownload($file);

                if ($errors) {
                    $json['errors'] = array_merge($json['errors'], $errors);
                } else {
                    $fileData = $this->MsLoader->MsFile->uploadDownload($file);

                    $json['files'][] = array(
                        'fileName' => $fileData['fileName'],
                        'fileMask' => $fileData['fileMask'],
                        'filePages' => isset($pages) ? $pages : ''
                    );
                }
            }
        }

        return $this->response->setOutput(json_encode($json));
    }

    public function jxGetFee() {
        $data = $this->request->get;

        if (!isset($data['price']) && !is_numeric($data['price']))
            $data['price'] = 0;

        $rates = $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $this->customer->getId()));
        echo $this->currency->format((float) $rates[MsCommission::RATE_LISTING]['flat'] + ((float) $rates[MsCommission::RATE_LISTING]['percent'] * $data['price'] / 100), $this->config->get('config_currency'));
    }

    public function jxSubmitProduct() {
        //ob_start();
        $data = $this->request->post;

        $seller = $this->MsLoader->MsSeller->getSeller($this->customer->getId());

        if (isset($data['product_id']) && !empty($data['product_id'])) {
            if ($this->MsLoader->MsProduct->productOwnedBySeller($data['product_id'], $this->customer->getId())) {
                $product = $this->MsLoader->MsProduct->getProduct($data['product_id']);
                $data['images'] = $this->MsLoader->MsProduct->getProductImages($data['product_id']);
            } else {
                return;
            }
        }

        $json = array();

        // Only check default language for errors
        $i = 0;
        $default = 0;
        $attributes = array();
        $product_attributes = array();

        foreach ($this->MsLoader->MsAttribute->getAttributes(array('multilang' => 1, 'enabled' => 1)) as $attribute) {
            $attributes[$attribute['attribute_id']] = $attribute;
            $attributes[$attribute['attribute_id']]['values'] = $this->MsLoader->MsAttribute->getAttributeValues($attribute['attribute_id']);
        }

        foreach ($data['languages'] as $language_id => $language) {
            // main language inputs are mandatory

            $description_length = $this->config->get('msconf_enable_rte') ? mb_strlen(strip_tags(htmlspecialchars_decode($language['product_description'], ENT_COMPAT))) : mb_strlen(htmlspecialchars_decode($language['product_description'], ENT_COMPAT));
            if ($i == 0) {
                $default = $language_id;

                if (empty($language['product_name'])) {
                    $json['errors']['product_name_' . $language_id] = $this->language->get('ms_error_product_name_empty');
                } else if (mb_strlen($language['product_name']) < 4 || mb_strlen($language['product_name']) > 50) {
                    $json['errors']['product_name_' . $language_id] = sprintf($this->language->get('ms_error_product_name_length'), 4, 50);
                }

                if (empty($language['product_description'])) {
                    $json['errors']['product_description_' . $language_id] = $this->language->get('ms_error_product_description_empty');
                } else if ($description_length < 25 || $description_length > 4000) {
                    $json['errors']['product_description_' . $language_id] = sprintf($this->language->get('ms_error_product_description_length'), 25, 4000);
                }
            } else {
                if (!empty($language['product_name']) && (mb_strlen($language['product_name']) < 4 || mb_strlen($language['product_name']) > 50)) {
                    $json['errors']['product_name_' . $language_id] = sprintf($this->language->get('ms_error_product_name_length'), 4, 50);
                } else if (empty($language['product_name'])) {
                    $data['languages'][$language_id]['product_name'] = $data['languages'][$default]['product_name'];
                }

                if (!empty($language['product_description']) && ($description_length < 25 || $description_length > 4000)) {
                    $json['errors']['product_description_' . $language_id] = sprintf($this->language->get('ms_error_product_description_length'), 25, 4000);
                } else if (empty($language['product_description'])) {
                    $data['languages'][$language_id]['product_description'] = $data['languages'][$default]['product_description'];
                }
            }

            if (in_array('metaDescription', $this->config->get('msconf_product_included_fields'))) {
                $data['languages'][$language_id]['product_meta_description'] = $data['languages'][$default]['product_meta_description'];
            }
            if (in_array('metaKeywords', $this->config->get('msconf_product_included_fields'))) {
                $data['languages'][$language_id]['product_meta_keyword'] = $data['languages'][$default]['product_meta_keyword'];
            }

            if (!empty($language['product_tags']) && mb_strlen($language['product_tags']) > 1000) {
                $json['errors']['product_tags_' . $language_id] = $this->language->get('ms_error_product_tags_length');
            }

            // strip disallowed tags in description
            if ($this->config->get('msconf_enable_rte')) {
                if ($this->config->get('msconf_rte_whitelist') != '') {
                    $allowed_tags = explode(",", $this->config->get('msconf_rte_whitelist'));
                    $allowed_tags_ready = "";
                    foreach ($allowed_tags as $tag) {
                        $allowed_tags_ready .= "<" . trim($tag) . ">";
                    }
                    $data['languages'][$language_id]['product_description'] = htmlspecialchars(strip_tags(htmlspecialchars_decode($language['product_description'], ENT_COMPAT), $allowed_tags_ready), ENT_COMPAT, 'UTF-8');
                }
            } else {
                $data['languages'][$language_id]['product_description'] = htmlspecialchars(nl2br($language['product_description']), ENT_COMPAT, 'UTF-8');
            }

            // multilang attributes
            if (isset($language['product_attributes'])) {
                $product_attributes = $language['product_attributes'];
                unset($data['languages'][$language_id]['product_attributes']);

                foreach ($attributes as $attribute_id => $attribute) {
                    // required attributes empty, errors, for first language only
                    if ($i == 0 && $attribute['required'] && (!isset($product_attributes[$attribute_id]) || empty($product_attributes[$attribute_id]) || empty($product_attributes[$attribute_id]['value']))) {
                        $json['errors']["languages[$language_id][product_attributes][$attribute_id]"] = $this->language->get('ms_error_product_attribute_required');
                        continue;
                    }

                    // attribute validation
                    if ($attribute['attribute_type'] == MsAttribute::TYPE_TEXT) {
                        if (mb_strlen($product_attributes[$attribute_id]['value']) > 100) {
                            $json['errors']["languages[$language_id][product_attributes][$attribute_id]"] = sprintf($this->language->get('ms_error_product_attribute_long'), 100);
                            continue;
                        }
                        // text input validation
                    } else if ($attribute['attribute_type'] == MsAttribute::TYPE_TEXTAREA) {
                        if (mb_strlen($product_attributes[$attribute_id]['value']) > 2000) {
                            $json['errors']["languages[$language_id][product_attributes][$attribute_id]"] = sprintf($this->language->get('ms_error_product_attribute_long'), 2000);
                            continue;
                        }

                        // enable to allow RTE for attributes
                        // $product_attributes[$attribute_id]['value'] = strip_tags(html_entity_decode($product_attributes[$attribute_id]['value']), $allowed_tags_ready);
                    }

                    // set attributes
                    $data['languages'][$language_id]['product_attributes'][$attribute_id] = array(
                        'attribute_type' => $attribute['attribute_type'],
                        // sorcery
                        'value' => !empty($product_attributes[$attribute_id]['value']) ? $product_attributes[$attribute_id]['value'] : (isset($data['languages'][$default]['product_attributes'][$attribute_id]['value']) ? $data['languages'][$default]['product_attributes'][$attribute_id]['value'] : ''),
                        'value_id' => $product_attributes[$attribute_id]['value_id']
                    );
                }
            }

            $i++;
        }

        if ((float) $data['product_price'] == 0) {
            if (!is_numeric($data['product_price'])) {
                $json['errors']['product_price'] = $this->language->get('ms_error_product_price_invalid');
            } else if ($this->config->get('msconf_allow_free_products') == 0) {
                $json['errors']['product_price'] = $this->language->get('ms_error_product_price_empty');
            }
        } else if ((float) $data['product_price'] < (float) $this->config->get('msconf_minimum_product_price')) {
            $json['errors']['product_price'] = $this->language->get('ms_error_product_price_low');
        } else if (($this->config->get('msconf_maximum_product_price') != 0) && ((float) $data['product_price'] > (float) $this->config->get('msconf_maximum_product_price'))) {
            $json['errors']['product_price'] = $this->language->get('ms_error_product_price_high');
        }

        $msconf_downloads_limits = $this->config->get('msconf_downloads_limits');
        if (!isset($data['product_downloads'])) {
            if ($msconf_downloads_limits[0] > 0) {
                $json['errors']['product_download'] = sprintf($this->language->get('ms_error_product_download_count'), $msconf_downloads_limits[0]);
            }
        } else {
            if ($msconf_downloads_limits[1] > 0 && count($data['product_downloads']) > $msconf_downloads_limits[1]) {
                $json['errors']['product_download'] = sprintf($this->language->get('ms_error_product_download_maximum'), $msconf_downloads_limits[1]);
            } else if ($msconf_downloads_limits[0] > 0 && count($data['product_downloads']) < $msconf_downloads_limits[0]) {
                $json['errors']['product_download'] = sprintf($this->language->get('ms_error_product_download_count'), $msconf_downloads_limits[0]);
            } else {
                foreach ($data['product_downloads'] as $key => $download) {
                    if (!empty($download['filename'])) {
                        if (!$this->MsLoader->MsFile->checkFileAgainstSession($download['filename'])) {
                            $json['errors']['product_download'] = $this->language->get('ms_error_file_upload_error');
                        }
                    } else if (!empty($download['download_id']) && !empty($product['product_id'])) {
                        if (!$this->MsLoader->MsProduct->hasDownload($product['product_id'], $download['download_id'])) {
                            $json['errors']['product_download'] = $this->language->get('ms_error_file_upload_error');
                        }
                    } else {
                        unset($data['product_downloads'][$key]);
                    }
                    //str_replace($this->MsLoader->MsSeller->getNickname() . '_', '', $download);
                    //$download = substr_replace($download, '.' . $this->MsLoader->MsSeller->getNickname() . '_', strpos($download,'.'), strlen('.'));
                }
            }
        }

        $msconf_images_limits = $this->config->get('msconf_images_limits');
        if (!isset($data['product_images'])) {
            if ($msconf_images_limits[0] > 0) {
                $json['errors']['product_image'] = sprintf($this->language->get('ms_error_product_image_count'), $msconf_images_limits[0]);
            }
        } else {
            if ($msconf_images_limits[1] > 0 && count($data['product_images']) > $msconf_images_limits[1]) {
                $json['errors']['product_image'] = sprintf($this->language->get('ms_error_product_image_maximum'), $msconf_images_limits[1]);
            } else if ($msconf_images_limits[0] > 0 && count($data['product_images']) < $msconf_images_limits[0]) {
                $json['errors']['product_image'] = sprintf($this->language->get('ms_error_product_image_count'), $msconf_images_limits[0]);
            } else {
                foreach ($data['product_images'] as $image) {
                    if (!$this->MsLoader->MsFile->checkFileAgainstSession($image)) {
                        $json['errors']['product_image'] = $this->language->get('ms_error_file_upload_error');
                    }
                }

                $data['product_thumbnail'] = array_shift($data['product_images']);
            }
        }

        if (!empty($data['product_message']) && mb_strlen($data['product_message']) > 1000) {
            $json['errors']['product_message'] = $this->language->get('ms_error_product_message_length');
        }

        // Special Prices
        unset($data['product_specials'][0]); // Remove sample row
        if (isset($data['product_specials']) && is_array($data['product_specials'])) {
            $product_specials = $data['product_specials'];
            foreach ($product_specials as $product_special) {
                if (!isset($product_special['priority']) || $product_special['priority'] == null || $product_special['priority'] == "") {
                    $json['errors']['specials'] = $this->language->get('ms_error_invalid_special_price_priority');
                }
                if ((!$this->MsLoader->MsHelper->isUnsignedFloat($product_special['price'])) || ((float) $product_special['price'] < (float) 0)) {
                    $json['errors']['specials'] = $this->language->get('ms_error_invalid_special_price_price');
                }
                if (!isset($product_special['date_start']) || ($product_special['date_start'] == NULL) || (!isset($product_special['date_end']) || $product_special['date_end'] == NULL)) {
                    $json['errors']['specials'] = $this->language->get('ms_error_invalid_special_price_dates');
                }
            }
        }

        // Quantity Discounts
        unset($data['product_discounts'][0]); // Remove sample row
        if (isset($data['product_discounts']) && is_array($data['product_discounts'])) {
            $product_discounts = $data['product_discounts'];
            foreach ($product_discounts as $product_discount) {
                if (!isset($product_discount['priority']) || $product_discount['priority'] == null || $product_discount['priority'] == "") {
                    $json['errors']['quantity_discounts'] = $this->language->get('ms_error_invalid_quantity_discount_priority');
                }
                if ((int) $product_discount['quantity'] < (int) 2) {
                    $json['errors']['quantity_discounts'] = $this->language->get('ms_error_invalid_quantity_discount_quantity');
                }
                if ((!$this->MsLoader->MsHelper->isUnsignedFloat($product_discount['price'])) || ((float) $product_discount['price'] < (float) 0)) {
                    $json['errors']['quantity_discounts'] = $this->language->get('ms_error_invalid_quantity_discount_price');
                }
                if (!isset($product_discount['date_start']) || ($product_discount['date_start'] == NULL) || (!isset($product_discount['date_end']) || $product_discount['date_end'] == NULL)) {
                    $json['errors']['quantity_discounts'] = $this->language->get('ms_error_invalid_quantity_discount_dates');
                }
            }
        }

        // uncomment to enable RTE for message field 
        /*
          if(isset($data['product_message'])) {
          $data['product_message'] = strip_tags(html_entity_decode($data['product_message']), $allowed_tags_ready);
          }
         */

        if (isset($data['product_category']) && !empty($data['product_category'])) {
            $categories = $this->MsLoader->MsProduct->getCategories();
            $disabled = array();
            foreach ($categories as $k => $c) {
                if ($c['disabled'])
                    $disabled[] = $c['category_id'];
            }

            // convert to array if needed
            $data['product_category'] = is_array($data['product_category']) ? $data['product_category'] : array($data['product_category']);

            // remove disabled categories if set
            $data['product_category'] = array_diff($data['product_category'], $disabled);

            if (!$this->config->get('msconf_allow_multiple_categories') && count($data['product_category']) > 1) {
                $data['product_category'] = array($data['product_category'][0]);
            }
        }

        // data array could have been modified in the previous step
        if (!isset($data['product_category']) || empty($data['product_category'])) {
            $json['errors']['product_category'] = $this->language->get('ms_error_product_category_empty');
        }

        if (in_array('model', $this->config->get('msconf_product_included_fields'))) {
            if (empty($data['product_model'])) {
                $json['errors']['product_model'] = $this->language->get('ms_error_product_model_empty');
            } else if (mb_strlen($data['product_model']) < 4 || mb_strlen($data['product_model']) > 64) {
                $json['errors']['product_model'] = sprintf($this->language->get('ms_error_product_model_length'), 4, 64);
            }
        }

        // generic attributes
        $attributes = array();
        $product_attributes = array();

        if (isset($data['product_attributes'])) {
            $product_attributes = $data['product_attributes'];
            unset($data['product_attributes']);
        }

        foreach ($this->MsLoader->MsAttribute->getAttributes(array('multilang' => 0, 'enabled' => 1)) as $attribute) {
            $attributes[$attribute['attribute_id']] = $attribute;
            $attributes[$attribute['attribute_id']]['values'] = $this->MsLoader->MsAttribute->getAttributeValues($attribute['attribute_id']);
        }

        foreach ($attributes as $attribute_id => $attribute) {
            // attributes with no values defined, skip
            if (empty($attribute['values']) && in_array($attribute['attribute_type'], array(MsAttribute::TYPE_CHECKBOX, MsAttribute::TYPE_SELECT, MsAttribute::TYPE_RADIO)))
                continue;

            // required attributes empty, errors
            // haha
            if (($attribute['required'] || $attribute['attribute_type'] == MsAttribute::TYPE_RADIO) && (!isset($product_attributes[$attribute_id]) || empty($product_attributes[$attribute_id]) || (isset($product_attributes[$attribute_id]['value'])) && empty($product_attributes[$attribute_id]['value']))) {
                $json['errors']["product_attributes[$attribute_id]"] = $this->language->get('ms_error_product_attribute_required');
                continue;
            }

            // attribute validation
            if (in_array($attribute['attribute_type'], array(MsAttribute::TYPE_SELECT, MsAttribute::TYPE_RADIO, MsAttribute::TYPE_IMAGE)) && isset($product_attributes[$attribute_id])) {
                // select, radio, image
                if ((int) $product_attributes[$attribute_id] == 0) {
                    // not required, not checked
                } else {
                    // @TODO check for permitted value id
                    $data['product_attributes'][$attribute_id] = array(
                        'attribute_type' => $attribute['attribute_type'],
                        'value' => $product_attributes[$attribute_id]
                    );
                }
                continue;
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_CHECKBOX) {
                // checkbox
                if (isset($product_attributes[$attribute_id])) {
                    foreach ($product_attributes[$attribute_id] as $key => $attribute_value_id) {
                        if ((int) $attribute_value_id != 0) {
                            // @TODO check for permitted value id
                            $data['product_attributes'][$attribute_id]['attribute_type'] = $attribute['attribute_type'];
                            $data['product_attributes'][$attribute_id]['values'][] = (int) $attribute_value_id;
                        }
                    }
                }
                continue;
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_TEXT) {
                if (mb_strlen($product_attributes[$attribute_id]['value']) > 100) {
                    $json['errors']["product_attributes[$attribute_id]"] = sprintf($this->language->get('ms_error_product_attribute_long'), 100);
                    continue;
                }
                // text input validation
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_TEXTAREA) {
                if (mb_strlen($product_attributes[$attribute_id]['value']) > 2000) {
                    $json['errors']["product_attributes[$attribute_id]"] = sprintf($this->language->get('ms_error_product_attribute_long'), 2000);
                    continue;
                }
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_DATE) {
                // date input validation
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_DATETIME) {
                // datetime input validation
            } else if ($attribute['attribute_type'] == MsAttribute::TYPE_TIME) {
                // datetime input validation
            }

            // set attributes
            //if (isset($data['product_attributes'][$attribute_id])) { ?
            $data['product_attributes'][$attribute_id] = array(
                'attribute_type' => $attribute['attribute_type'],
                'value' => $product_attributes[$attribute_id]['value'],
                'value_id' => $product_attributes[$attribute_id]['value_id'],
            );
            //}
        }

        // options
        //unset($data['product_option'][0]); // Remove sample row		

        if (!isset($data['product_subtract'])) {
            $data['product_subtract'] = 0;
        }

        if ($this->config->get('msship_enable_shipping') == 1) { // enable shipping
            $data['product_enable_shipping'] = 1;
        } else if ($this->config->get('msship_enable_shipping') == 2) { // seller select
            if (!isset($data['product_enable_shipping']) || $data['product_enable_shipping'] != 1) {
                $data['product_enable_shipping'] = 0;
            } else {
                $data['product_enable_shipping'] = 1;
            }
        } else { // disable shipping
            $data['product_enable_shipping'] = 0;
        }

        // Set the quantity
        $seller_group = $this->MsLoader->MsSellerGroup->getSellerGroup($seller['ms.seller_group']);
        if ($this->config->get('msconf_enable_quantities') == 1) { // Enable quantities
            if (isset($seller_group['product_quantity']) && $seller_group['product_quantity'] != 0) { // Seller group quantity is set
                $data['product_quantity'] = (int) $seller_group['product_quantity'];
            } else {
                $data['product_quantity'] = (int) $data['product_quantity'];
            }
            $data['product_subtract'] = 1;
        } else if ($this->config->get('msconf_enable_quantities') == 2) { // Shipping dependent
            if ($this->config->get('msship_enable_shipping') == 1) {
                $data['product_subtract'] = 1;
                if (isset($seller_group['product_quantity']) && $seller_group['product_quantity'] != 0) { // Seller group quantity is set
                    $data['product_quantity'] = (int) $seller_group['product_quantity'];
                } else {
                    if (!isset($data['product_quantity']))
                        $data['product_quantity'] = 0;
                }
            } else if ($this->config->get('msship_enable_shipping') == 2) {
                if (!$data['product_enable_shipping']) {
                    $data['product_quantity'] = 999;
                } else {
                    $data['product_subtract'] = 1;
                    if (isset($seller_group['product_quantity']) && $seller_group['product_quantity'] != 0) { // Seller group quantity is set
                        $data['product_quantity'] = (int) $seller_group['product_quantity'];
                    } else {
                        if (!isset($data['product_quantity']))
                            $data['product_quantity'] = 0;
                    }
                }
            } else { // Shipping disabled and quantity is shipping dependent
                $data['product_quantity'] = 999;
            }
        } else { // Disable quantities
            $data['product_quantity'] = 999;
        }

        // SEO urls generation for products
        if ($this->config->get('msconf_enable_seo_urls_product')) {
            $latin_check = '/[^\x{0030}-\x{007f}]/u';
            $product_name = $data['languages'][$default]['product_name'];
            $non_latin_chars = preg_match($latin_check, $product_name);
            if ($this->config->get('msconf_enable_non_alphanumeric_seo') && $non_latin_chars) {
                $data['keyword'] = implode("-", str_replace("-", "", explode(" ", preg_replace("/[^\p{L}\p{N} ]/u", '', strtolower($product_name)))));
            } else {
                $data['keyword'] = implode("-", str_replace("-", "", explode(" ", preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($product_name)))));
            }
        }

        // Listing until
        if (!isset($data['listing_until']) || $data['listing_until'] == "") {
            $data['listing_until'] = NULL;
        }

        // post-validation
        if (empty($json['errors'])) {
            $mails = array();

            // Relist the product
            if ($this->config->get('msconf_allow_relisting')) {
                if ((isset($data['product_id']) && !empty($data['product_id'])) && $this->MsLoader->MsProduct->getStatus((int) $data['product_id']) == MsProduct::STATUS_DISABLED) {
                    $this->MsLoader->MsProduct->changeStatus((int) $data['product_id'], MsProduct::STATUS_ACTIVE);
                }
            }

            // If it is allowed for inactive seller to list new products
            if ($this->config->get('msconf_allow_inactive_seller_products') && $this->MsLoader->MsSeller->getStatus() == MsSeller::STATUS_INACTIVE) {
                $data['enabled'] = 0;
                $data['product_status'] = MsProduct::STATUS_INACTIVE;
                $data['product_approved'] = 0;
                // No e-mails are sent here
            } else {
                // Set product status
                switch ($seller['ms.product_validation']) {
                    case MsProduct::MS_PRODUCT_VALIDATION_APPROVAL:
                        $data['enabled'] = 0;
                        $data['product_status'] = MsProduct::STATUS_INACTIVE;
                        $data['product_approved'] = 0;
                        /* if (isset($data['product_id']) && !empty($data['product_id'])) {
                          //$request_type = MsRequestProduct::TYPE_PRODUCT_UPDATE;
                          } else {
                          //$request_type = MsRequestProduct::TYPE_PRODUCT_CREATE;
                          } */

                        if (!isset($data['product_id']) || empty($data['product_id'])) {
                            $mails[] = array(
                                'type' => MsMail::SMT_PRODUCT_AWAITING_MODERATION
                            );
                            $mails[] = array(
                                'type' => MsMail::AMT_NEW_PRODUCT_AWAITING_MODERATION,
                                'data' => array(
                                    'message' => isset($data['product_message']) ? $data['product_message'] : ''
                                )
                            );
                        } else {
                            $mails[] = array(
                                'type' => MsMail::SMT_PRODUCT_AWAITING_MODERATION
                            );
                            $mails[] = array(
                                'type' => MsMail::AMT_EDIT_PRODUCT_AWAITING_MODERATION,
                                'data' => array(
                                    'message' => isset($data['product_message']) ? $data['product_message'] : ''
                                )
                            );
                        }
                        break;

                    case MsProduct::MS_PRODUCT_VALIDATION_NONE:
                    default:
                        $data['enabled'] = 1;
                        $data['product_status'] = MsProduct::STATUS_ACTIVE;
                        $data['product_approved'] = 1;

                        if (!isset($data['product_id']) || empty($data['product_id'])) {
                            $mails[] = array(
                                'type' => MsMail::AMT_PRODUCT_CREATED
                            );
                        } else {
                            // product edited mail if needed
                        }
                        break;
                }
            }

            if (isset($data['product_id']) && !empty($data['product_id'])) {
                $product_id = $this->MsLoader->MsProduct->editProduct($data);
                if ($product['product_status'] == MsProduct::STATUS_UNPAID) {
                    $commissions = $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $this->customer->getId()));
                    $fee = (float) $commissions[MsCommission::RATE_LISTING]['flat'] + $commissions[MsCommission::RATE_LISTING]['percent'] * $data['product_price'] / 100;
                    if ($fee > 0) {
                        switch ($commissions[MsCommission::RATE_LISTING]['payment_method']) {
                            case MsPayment::METHOD_PAYPAL:
                                // initiate paypal payment
                                // change status to unpaid
                                $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_UNPAID);

                                // unset seller email
                                foreach ($mails as $key => $value) {
                                    if ($value['type'] == SMT_PRODUCT_AWAITING_MODERATION)
                                        unset($mails[$key]);
                                }
                                // Send product edited emails
                                foreach ($mails as &$mail) {
                                    $mail['data']['product_id'] = $product_id;
                                }
                                $this->MsLoader->MsMail->sendMails($mails);

                                // check if payment exists
                                $payment = $this->MsLoader->MsPayment->getPayments(array(
                                    'seller_id' => $this->customer->getId(),
                                    'product_id' => $product_id,
                                    'payment_type' => array(MsPayment::TYPE_LISTING),
                                    'payment_status' => array(MsPayment::STATUS_UNPAID),
                                    'payment_method' => array(MsPayment::METHOD_PAYPAL),
                                    'single' => 1
                                ));

                                if (!$payment) {
                                    // create new payment
                                    $payment_id = $this->MsLoader->MsPayment->createPayment(array(
                                        'seller_id' => $this->customer->getId(),
                                        'product_id' => $product_id,
                                        'payment_type' => MsPayment::TYPE_LISTING,
                                        'payment_status' => MsPayment::STATUS_UNPAID,
                                        'payment_method' => MsPayment::METHOD_PAYPAL,
                                        'amount' => $fee,
                                        'currency_id' => $this->currency->getId($this->config->get('config_currency')),
                                        'currency_code' => $this->currency->getCode($this->config->get('config_currency')),
                                        'description' => sprintf($this->language->get('ms_transaction_listing'), $data['languages'][$default]['product_name'], $this->currency->format(-$fee, $this->config->get('config_currency')))
                                    ));
                                } else {
                                    $payment_id = $payment['payment_id'];

                                    // edit payment
                                    $this->MsLoader->MsPayment->updatePayment($payment_id, array(
                                        'amount' => $fee,
                                        'date_created' => 1,
                                        'description' => sprintf($this->language->get('ms_transaction_listing'), $data['languages'][$default]['product_name'], $this->currency->format(-$fee, $this->config->get('config_currency')))
                                    ));
                                }
                                // assign payment variables
                                $json['data']['amount'] = $this->currency->format($fee, $this->config->get('config_currency'), '', FALSE);
                                $json['data']['custom'] = $payment_id;

                                return $this->response->setOutput(json_encode($json));
                                break;

                            case MsPayment::METHOD_BALANCE:
                            default:
                                // deduct from balance
                                $this->MsLoader->MsBalance->addBalanceEntry($this->customer->getId(), array(
                                    'product_id' => $product_id,
                                    'balance_type' => MsBalance::MS_BALANCE_TYPE_LISTING,
                                    'amount' => -$fee,
                                    'description' => sprintf($this->language->get('ms_transaction_listing'), $data['languages'][$default]['product_name'], $this->currency->format(-$fee, $this->config->get('config_currency')))
                                        )
                                );

                                break;
                        }
                    }
                }

                $this->session->data['success'] = $this->language->get('ms_success_product_updated');
            } else {
                //$data['list_until'] = date('Y-m-d', strtotime($data['list_until']));
                $commissions = $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $this->customer->getId()));
                $fee = (float) $commissions[MsCommission::RATE_LISTING]['flat'] + $commissions[MsCommission::RATE_LISTING]['percent'] * $data['product_price'] / 100;
                $product_id = $this->MsLoader->MsProduct->saveProduct($data);

                // send product created emails
                foreach ($mails as &$mail) {
                    $mail['data']['product_id'] = $product_id;
                }
                $this->MsLoader->MsMail->sendMails($mails);

                if ($fee > 0) {
                    switch ($commissions[MsCommission::RATE_LISTING]['payment_method']) {
                        case MsPayment::METHOD_PAYPAL:
                            // initiate paypal payment
                            // set product status to unpaid
                            $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_UNPAID);

                            // add payment details
                            $payment_id = $this->MsLoader->MsPayment->createPayment(array(
                                'seller_id' => $this->customer->getId(),
                                'product_id' => $product_id,
                                'payment_type' => MsPayment::TYPE_LISTING,
                                'payment_status' => MsPayment::STATUS_UNPAID,
                                'payment_method' => MsPayment::METHOD_PAYPAL,
                                'amount' => $fee,
                                'currency_id' => $this->currency->getId($this->config->get('config_currency')),
                                'currency_code' => $this->currency->getCode($this->config->get('config_currency')),
                                'description' => sprintf($this->language->get('ms_transaction_listing'), $data['languages'][$default]['product_name'], $this->currency->format(-$fee, $this->config->get('config_currency')))
                            ));

                            // assign payment variables
                            $json['data']['amount'] = $this->currency->format($fee, $this->config->get('config_currency'), '', FALSE);
                            $json['data']['custom'] = $payment_id;

                            return $this->response->setOutput(json_encode($json));
                            break;

                        case MsPayment::METHOD_BALANCE:
                        default:
                            // deduct from balance
                            $this->MsLoader->MsBalance->addBalanceEntry($this->customer->getId(), array(
                                'product_id' => $product_id,
                                'balance_type' => MsBalance::MS_BALANCE_TYPE_LISTING,
                                'amount' => -$fee,
                                'description' => sprintf($this->language->get('ms_transaction_listing'), $data['languages'][$default]['product_name'], $this->currency->format(-$fee, $this->config->get('config_currency')))
                                    )
                            );

                            break;
                    }
                }

                $this->session->data['success'] = $this->language->get('ms_success_product_created');
            }

            $json['redirect'] = $this->url->link('seller/account-product', '', 'SSL');
        }

        /*
          $output = ob_get_clean();
          if ($output) {
          $this->log->write('MMERCH PRODUCT FORM: ' . $output);
          if (!$this->session->data['success']) $json['fail'] = 1;
          }
         */
        $this->response->setOutput(json_encode($json));
    }

    public function jxRenderOptions() {
        $this->data['options'] = $this->MsLoader->MsOption->getOptions();
        foreach ($this->data['options'] as &$option) {
            $option['values'] = $this->MsLoader->MsOption->getOptionValues($option['option_id']);
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-product-form-options.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/multiseller/account-product-form-options.tpl';
        } else {
            $this->template = 'default/template/multiseller/account-product-form-options.tpl';
        }

        $this->response->setOutput($this->load->view($this->template, $this->data));
    }

    public function jxRenderOptionValues() {
        $this->data['option'] = $this->MsLoader->MsOption->getOptions(
                array(
                    'option_id' => $this->request->get['option_id'],
                    'single' => 1
                )
        );

        $this->data['values'] = $this->MsLoader->MsOption->getOptionValues($this->request->get['option_id']);
        $this->data['option_index'] = 0;

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-product-form-options-values.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/multiseller/account-product-form-options-values.tpl';
        } else {
            $this->template = 'default/template/multiseller/account-product-form-options-values.tpl';
        }

        $this->response->setOutput($this->load->view($this->template, $this->data));
    }

    public function jxRenderProductOptions() {
        $this->load->model('catalog/product');
        $options = $this->model_catalog_product->getProductOptions($this->request->get['product_id']);

        $output = '';
        if ($options) {
            $this->data['option_index'] = 0;
            foreach ($options as $o) {
                $this->data['option'] = $o;
                $this->data['product_option_values'] = $o['product_option_value'];
                $this->data['values'] = $this->MsLoader->MsOption->getOptionValues($o['option_id']);

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-product-form-options-values.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/multiseller/account-product-form-options-values.tpl';
                } else {
                    $this->template = 'default/template/multiseller/account-product-form-options-values.tpl';
                }

                $output .= $this->load->view($this->template, $this->data);
                $this->data['option_index'] ++;
            }
        }

        $this->response->setOutput($output);
    }

    public function jxAutocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('catalog/manufacturer');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'start' => 0,
                'limit' => 5
            );

            $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

            foreach ($results as $result) {
                $json[] = array(
                    'manufacturer_id' => $result['manufacturer_id'],
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

    public function jxShippingCategories() {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');

        $product_id = empty($this->request->post['product_id']) ? 0 : $this->request->post['product_id'];
        $seller_id = $this->customer->getId();
        $product = NULL;

        if (!empty($product_id)) {
            if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id)) {
                $product = $this->MsLoader->MsProduct->getProduct($product_id);
            } else
                $product = NULL;
        }

        $this->data['product'] = $product;
        $this->data['product']['category_id'] = $this->MsLoader->MsProduct->getProductCategories($product_id);
        $this->data['product']['shipping'] = $this->request->post['type'];
        $this->data['categories'] = $this->MsLoader->MsProduct->getCategories();
        $this->data['msconf_allow_multiple_categories'] = $this->config->get('msconf_allow_multiple_categories');
        $this->data['msconf_enable_categories'] = $this->config->get('msconf_enable_categories');
        $this->data['msconf_physical_product_categories'] = $this->config->get('msconf_physical_product_categories');
        $this->data['msconf_digital_product_categories'] = $this->config->get('msconf_digital_product_categories');

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-product-form-shipping-categories');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

    public function index() {
        // paypal listing payment confirmation
        if (isset($this->request->post['payment_status']) && strtolower($this->request->post['payment_status']) == 'completed') {
            $this->data['success'] = $this->language->get('ms_success_product_published');
        }

        // Links
        $this->data['link_back'] = $this->url->link('seller/account-dashboard', '', 'SSL');
        $this->data['link_create_product'] = $this->url->link('seller/account-product/create', '', 'SSL');

        // Title and friends
        $this->document->setTitle($this->language->get('ms_account_products_heading'));
        $this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
                'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
            ),
            array(
                'text' => $this->language->get('ms_account_products_breadcrumbs'),
                'href' => $this->url->link('seller/account-product', '', 'SSL'),
            )
        ));

        $this->load->model('account/reservations');
        $this->load->model('catalog/product');

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => 5
        );

        $results = $this->model_account_reservations->getOrders($filter_data);

        foreach ($results as $result) {
            $this->data['orders'][] = array(
                'order_id' => $result['order_id'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')',
                'product' => $this->model_catalog_product->getMakeModelCategories($result['product_id']) . ' ' . $result['model'],
                'start_time' => date("m-d-Y", strtotime($result['start_date'])) . ' ' . date("h:i A", strtotime($result['start_time'])),
                'view' => $this->url->link('seller/account-order/viewOrder&order_id=' . $result['order_id'])
            );
        }

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-product');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

    private function _initForm() {
        $this->load->model('catalog/category');
        $this->load->model('catalog/product');
        $this->load->model('localisation/currency');
        $this->load->model('localisation/language');

        $this->document->addScript('catalog/view/javascript/plupload/plupload.full.js');
        $this->document->addScript('catalog/view/javascript/plupload/jquery.plupload.queue/jquery.plupload.queue.js');
        $this->document->addScript('catalog/view/javascript/account-product-form.js');
        $this->document->addScript('catalog/view/javascript/multimerch/account-product-form-options.js');
        $this->document->addScript('https://rawgit.com/RubaXa/Sortable/master/Sortable.js');

        // rte
        if ($this->config->get('msconf_enable_rte')) {
            $this->document->addScript('catalog/view/javascript/multimerch/summernote/summernote.js');
            $this->document->addStyle('catalog/view/javascript/multimerch/summernote/summernote.css');
        }

        $this->data['seller'] = $this->MsLoader->MsSeller->getSeller($this->customer->getId());
        $this->data['seller_group'] = $this->MsLoader->MsSellerGroup->getSellerGroup($this->data['seller']['ms.seller_group']);

        $product_id = isset($this->request->get['product_id']) ? (int) $this->request->get['product_id'] : 0;
        if ($product_id)
            $product_status = $this->MsLoader->MsProduct->getStatus($product_id);

        if (!$product_id || $product_status == MsProduct::STATUS_UNPAID) {
            $this->data['seller']['commissions'] = $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $this->customer->getId()));
            switch ($this->data['seller']['commissions'][MsCommission::RATE_LISTING]['payment_method']) {
                case MsPayment::METHOD_PAYPAL:
                    $this->data['ms_commission_payment_type'] = $this->language->get('ms_account_product_listing_paypal');
                    $this->data['payment_data'] = array(
                        'sandbox' => $this->config->get('msconf_paypal_sandbox'),
                        'action' => $this->config->get('msconf_paypal_sandbox') ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr",
                        'business' => $this->config->get('msconf_paypal_address'),
                        'item_name' => sprintf($this->language->get('ms_account_product_listing_itemname'), $this->config->get('config_name')),
                        'item_number' => isset($this->request->get['product_id']) ? (int) $this->request->get['product_id'] : '',
                        'amount' => '',
                        'currency_code' => $this->config->get('config_currency'),
                        'return' => $this->url->link('seller/account-product'),
                        'cancel_return' => $this->url->link('seller/account-product'),
                        'notify_url' => $this->url->link('payment/multimerch-paypal/listingIPN'),
                        'custom' => 'custom'
                    );

                    list($this->template, $this->children) = $this->MsLoader->MsHelper->loadTemplate('payment-paypal', array());
                    $this->data['payment_form'] = $this->render();
                    break;

                case MsPayment::METHOD_BALANCE:
                default:
                    $this->data['ms_commission_payment_type'] = $this->language->get('ms_account_product_listing_balance');
                    break;
            }
        }
        $this->data['salt'] = $this->MsLoader->MsSeller->getSalt($this->customer->getId());
        $this->data['categories'] = $this->MsLoader->MsProduct->getCategories();
        $this->data['date_available'] = date('Y-m-d', time() - 86400);
        $this->data['tax_classes'] = $this->MsLoader->MsHelper->getTaxClasses();
        $this->data['stock_statuses'] = $this->MsLoader->MsHelper->getStockStatuses();

        $attributes = $this->MsLoader->MsAttribute->getAttributes(
                array(
            // current language
            'language_id' => $this->config->get('config_language_id'),
            'enabled' => 1
                ), array(
            'order_by' => 'ma.sort_order',
            'order_way' => 'ASC'
                )
        );

        if (!empty($attributes)) {
            foreach ($attributes as $attr) {
                if ($attr['attribute_type'] == MsAttribute::TYPE_RADIO)
                    $attr['required'] = 1;

                $attr['values'] = $this->MsLoader->MsAttribute->getAttributeValues($attr['attribute_id']);

                if (empty($attr['values']) && in_array($attr['attribute_type'], array(MsAttribute::TYPE_CHECKBOX, MsAttribute::TYPE_SELECT, MsAttribute::TYPE_RADIO)))
                    continue;

                foreach ($attr['values'] as &$value) {
                    $value['image'] = (!empty($value['image']) ? $this->MsLoader->MsFile->resizeImage($value['image'], 50, 50) : $this->MsLoader->MsFile->resizeImage('no_image.png', 50, 50));
                }

                if ($attr['multilang'] && in_array($attr['attribute_type'], array(MsAttribute::TYPE_TEXT, MsAttribute::TYPE_TEXTAREA))) {
                    $this->data['multilang_attributes'][] = $attr;
                } else {
                    $this->data['normal_attributes'][] = $attr;
                }
            }
        }

        $this->data['languages'] = $this->model_localisation_language->getLanguages();
        $this->data['msconf_allow_multiple_categories'] = $this->config->get('msconf_allow_multiple_categories');
        $this->data['msconf_images_limits'] = $this->config->get('msconf_images_limits');
        $this->data['msconf_downloads_limits'] = $this->config->get('msconf_downloads_limits');
        $this->data['msconf_enable_quantities'] = $this->config->get('msconf_enable_quantities');
        $this->data['msconf_enable_categories'] = $this->config->get('msconf_enable_categories');
        $this->data['msconf_physical_product_categories'] = $this->config->get('msconf_physical_product_categories');
        $this->data['msconf_digital_product_categories'] = $this->config->get('msconf_digital_product_categories');
        $this->data['ms_account_product_download_note'] = sprintf($this->language->get('ms_account_product_download_note'), $this->config->get('msconf_allowed_download_types'));
        $this->data['ms_account_product_image_note'] = sprintf($this->language->get('ms_account_product_image_note'), $this->config->get('msconf_allowed_image_types'));
        $this->data['back'] = $this->url->link('seller/account-product', '', 'SSL');
    }

    public function create() {
        $this->_initForm();
        $this->data['product_attributes'] = FALSE;
        $this->data['product'] = FALSE;
        $this->data['heading'] = $this->language->get('ms_account_newproduct_heading');
        $this->document->setTitle($this->language->get('ms_account_newproduct_heading'));

        $this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
                'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
            ),
            array(
                'text' => $this->language->get('ms_account_products_breadcrumbs'),
                'href' => $this->url->link('seller/account-product', '', 'SSL'),
            ),
            array(
                'text' => $this->language->get('ms_account_newproduct_breadcrumbs'),
                'href' => $this->url->link('seller/account-product/create', '', 'SSL'),
            )
        ));

        // Product listing period
        if ($this->data['seller_group']['product_period'] > 0) {
            $this->data['list_until'] = date('Y-m-d', strtotime(date('Y-m-d')) + (24 * 3600 * $this->data['seller_group']['product_period']));
        } else {
            $this->data['list_until'] = NULL;
        }

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-product-form');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

    public function update() {
        $product_id = isset($this->request->get['product_id']) ? (int) $this->request->get['product_id'] : 0;
        $clone = isset($this->request->get['clone']) ? (int) $this->request->get['clone'] : 0;
        $relist = isset($this->request->get['relist']) ? (int) $this->request->get['relist'] : 0;
        $seller_id = $this->customer->getId();

        if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id)) {
            $product = $this->MsLoader->MsProduct->getProduct($product_id);
        } else {
            $product = NULL;
        }

        if (!$product)
            return $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));

        // Fees for re-listing
        if ($relist) {
            $commissions = $this->MsLoader->MsCommission->calculateCommission(array('seller_id' => $this->customer->getId()));
            $fee = (float) $commissions[MsCommission::RATE_LISTING]['flat'] + $commissions[MsCommission::RATE_LISTING]['percent'] * $product['price'] / 100;

            if ($fee > 0) {
                $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_UNPAID);
            }
        }

        $this->_initForm();

        if (!empty($this->data['normal_attributes']) || !empty($this->data['multilang_attributes'])) {
            $a = $this->MsLoader->MsAttribute->getProductAttributeValues($product_id);
            $this->data['multilang_attribute_values'] = $a[1];
            $this->data['normal_attribute_values'] = $a[0];
        }

        $product['specials'] = $this->MsLoader->MsProduct->getProductSpecials($product_id);
        $product['discounts'] = $this->MsLoader->MsProduct->getProductDiscounts($product_id);

        if (!empty($product['thumbnail'])) {

            if ($clone) {
                $oldPath = DIR_IMAGE . $product['thumbnail'];
                $product['thumbnail'] = $this->config->get('msconf_temp_image_path') . basename($product['thumbnail']);
                copy($oldPath, DIR_IMAGE . $product['thumbnail']);
            }

            $product['images'][] = array(
                'name' => $product['thumbnail'],
                'thumb' => $this->MsLoader->MsFile->resizeImage($product['thumbnail'], $this->config->get('msconf_preview_product_image_width'), $this->config->get('msconf_preview_product_image_height'))
            );

            if (!in_array($product['thumbnail'], $this->session->data['multiseller']['files']))
                $this->session->data['multiseller']['files'][] = $product['thumbnail'];
        }

        $images = $this->MsLoader->MsProduct->getProductImages($product_id);
        foreach ($images as $image) {

            if ($clone) {
                $oldPath = DIR_IMAGE . $image['image'];
                $image['image'] = $this->config->get('msconf_temp_image_path') . basename($image['image']);
                copy($oldPath, DIR_IMAGE . $image['image']);
            }

            $product['images'][] = array(
                'name' => $image['image'],
                'thumb' => $this->MsLoader->MsFile->resizeImage($image['image'], $this->config->get('msconf_preview_product_image_width'), $this->config->get('msconf_preview_product_image_height'))
            );

            if (!in_array($image['image'], $this->session->data['multiseller']['files']))
                $this->session->data['multiseller']['files'][] = $image['image'];
        }

        $downloads = $this->MsLoader->MsProduct->getProductDownloads($product_id);
        $product['downloads'] = array();
        foreach ($downloads as $download) {

            //$download_seller = $this->MsLoader->MsSeller->getSeller($this->MsLoader->MsProduct->getSellerId($download['product_id']));

            if ($clone) {
                $oldPath = DIR_DOWNLOAD . $download['filename'];
                //$download['filename']	= time() . '_' . md5(rand()) . '.' . $this->MsLoader->MsSeller->getNickname() . substr($download['mask'], strlen($download_seller['ms.nickname']));
                $download['filename'] = time() . '_' . md5(rand()) . '.' . $download['mask'];
                copy($oldPath, DIR_DOWNLOAD . $this->config->get('msconf_temp_download_path') . $download['filename']);
            }

            //$ext = explode('.', $download['mask']); $ext = end($ext);
            $product['downloads'][] = array(
                'name' => $download['mask'],
                'src' => $download['filename'],
                //'href' => HTTPS_SERVER . 'download/' . $download['filename'],
                'href' => $this->url->link('seller/account-product/download', 'download_id=' . $download['download_id'] . '&product_id=' . $product_id, 'SSL'),
                'id' => $download['download_id'],
            );

            if (!in_array($download['filename'], $this->session->data['multiseller']['files']))
                $this->session->data['multiseller']['files'][] = $download['filename'];
        }

        $currencies = $this->model_localisation_currency->getCurrencies();
        $decimal_place = $currencies[$this->config->get('config_currency')]['decimal_place'];
        $decimal_point = $this->language->get('decimal_point');
        $thousand_point = $this->language->get('thousand_point');
        //$product['price'] = number_format(round($this->currency->convert($product['price'], $this->MsLoader->MsProduct->getDefaultCurrency(), $_SESSION['currency'] ), (int)$decimal_place), (int)$decimal_place, $decimal_point, '');
        $product['price'] = round($product['price'], (int) $decimal_place);

        if (isset($product['manufacturer_id'])) {
            $product['manufacturer_id'] = (int) $product['manufacturer_id'];
            $this->load->model('catalog/manufacturer');
            $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product['manufacturer_id']);
            if ($manufacturer_info) {
                $product['manufacturer'] = $manufacturer_info['name'];
            } else {
                $product['manufacturer'] = '';
            }
        } else {
            $product['manufacturer_id'] = 0;
            $product['manufacturer'] = '';
        };

        if (isset($product['tax_class_id'])) {
            $product['tax_class_id'] = $product['tax_class_id'];
        } else {
            $product['tax_class_id'] = 0;
        }

        if (isset($product['stock_status_id'])) {
            $product['stock_status_id'] = $product['stock_status_id'];
        } else {
            $product['stock_status_id'] = $this->config->get('config_stock_status_id');
        }

        if (isset($product['date_available'])) {
            $this->data['date_available'] = date('Y-m-d', strtotime($product['date_available']));
        }

        $this->data['product'] = $product;
        $this->data['product']['category_id'] = $this->MsLoader->MsProduct->getProductCategories($product_id);

        $breadcrumbs = array(
            array(
                'text' => $this->language->get('ms_account_dashboard_breadcrumbs'),
                'href' => $this->url->link('seller/account-dashboard', '', 'SSL'),
            ),
            array(
                'text' => $this->language->get('ms_account_products_breadcrumbs'),
                'href' => $this->url->link('seller/account-product', '', 'SSL'),
            )
        );

        if ($clone) {
            $this->data['product']['product_id'] = 0;
            $this->data['product']['cloned_product_id'] = $product_id;
            $this->data['clone'] = 1;
            // Product listing period
            if ($this->data['seller_group']['product_period'] > 0) {
                $this->data['list_until'] = date('Y-m-d', strtotime(date('Y-m-d')) + (24 * 3600 * $this->data['seller_group']['product_period']));
            } else {
                $this->data['list_until'] = NULL;
            }

            $breadcrumbs[] = array(
                'text' => $this->language->get('ms_account_cloneproduct_breadcrumbs'),
                'href' => $this->url->link('seller/account-product/update', '', 'SSL'),
            );
            $this->data['heading'] = $this->language->get('ms_account_cloneproduct_heading');
            $this->document->setTitle($this->language->get('ms_account_cloneproduct_heading'));
        } else if ($relist) {
            // Product listing period
            if ($this->data['seller_group']['product_period'] > 0) {
                $this->data['list_until'] = date('Y-m-d', strtotime(date('Y-m-d')) + (24 * 3600 * $this->data['seller_group']['product_period']));
            } else {
                $this->data['list_until'] = NULL;
            }

            $breadcrumbs[] = array(
                'text' => $this->language->get('ms_account_relist_product_breadcrumbs'),
                'href' => $this->url->link('seller/account-product/update', '', 'SSL'),
            );
            $this->data['heading'] = $this->language->get('ms_account_relist_product_heading');
            $this->document->setTitle($this->language->get('ms_account_relist_product_heading'));
        } else {
            $breadcrumbs[] = array(
                'text' => $this->language->get('ms_account_editproduct_breadcrumbs'),
                'href' => $this->url->link('seller/account-product/update', '', 'SSL'),
            );
            $this->data['heading'] = $this->language->get('ms_account_editproduct_heading');
            $this->document->setTitle($this->language->get('ms_account_editproduct_heading'));
        }

        $this->data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs($breadcrumbs);

        list($template, $children) = $this->MsLoader->MsHelper->loadTemplate('account-product-form');
        $this->response->setOutput($this->load->view($template, array_merge($this->data, $children)));
    }

    /* public function delete() {
      $product_id = (int) $this->request->get['product_id'];
      $seller_id = (int) $this->agency->getSellerId();

      if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id)) {
      $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_DELETED);
      $this->session->data['success'] = $this->language->get('ms_success_product_deleted');
      }
      $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));
      } */

    public function delete() {
        $product_id = isset($this->request->get['product_id']) ? $this->request->get['product_id'] : 0;
        $this->MsLoader->MsProduct->deleteProduct($product_id);
        $this->session->data['success'] = $this->language->get('ms_success_product_deleted');
        $this->response->redirect($this->url->link('seller/account-product'));
    }

    public function publish() {
        $product_id = (int) $this->request->get['product_id'];
        $seller_id = (int) $this->customer->getId();

        if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id) && $this->MsLoader->MsProduct->getStatus($product_id) == MsProduct::STATUS_INACTIVE) {
            $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_ACTIVE);
            $this->session->data['success'] = $this->language->get('ms_success_product_published');
        }

        $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));
    }

    public function unpublish() {
        $product_id = (int) $this->request->get['product_id'];
        $seller_id = (int) $this->customer->getId();

        if ($this->MsLoader->MsProduct->productOwnedBySeller($product_id, $seller_id) && $this->MsLoader->MsProduct->getStatus($product_id) == MsProduct::STATUS_ACTIVE) {
            $this->MsLoader->MsProduct->changeStatus($product_id, MsProduct::STATUS_INACTIVE);
            $this->session->data['success'] = $this->language->get('ms_success_product_unpublished');
        }

        $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));
    }

    public function download() {
        if (!$this->customer->isLogged()) {
            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        if (isset($this->request->get['download_id'])) {
            $download_id = $this->request->get['download_id'];
        } else {
            $download_id = 0;
        }

        if (isset($this->request->get['product_id'])) {
            $product_id = $this->request->get['product_id'];
        } else {
            $product_id = 0;
        }

        if (!$this->MsLoader->MsProduct->hasDownload($product_id, $download_id) || !$this->MsLoader->MsProduct->productOwnedBySeller($product_id, $this->customer->getId()))
            $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));

        $download_info = $this->MsLoader->MsProduct->getDownload($download_id);

        if ($download_info) {
            $file = DIR_DOWNLOAD . $download_info['filename'];
            $mask = basename($download_info['mask']);

            if (!headers_sent()) {
                if (file_exists($file)) {
                    header('Content-Type: application/octet-stream');
                    header('Content-Description: File Transfer');
                    header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));

                    readfile($file, 'rb');
                    exit;
                } else {
                    exit('Error: Could not find file ' . $file . '!');
                }
            } else {
                exit('Error: Headers already sent out!');
            }
        } else {
            $this->response->redirect($this->url->link('seller/account-product', '', 'SSL'));
        }
    }

    protected function validateForm() {

        foreach ($this->request->post['product_description'] as $language_id => $value) {
            /* if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
              $this->error['name'][$language_id] = $this->language->get('error_name');
              }

              if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
              $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
              } */
        }

        if (!ctype_digit($this->request->post['model']) || $this->request->post['model'] == '') {
            $this->error['model'] = $this->language->get('error_model');
        }

        if ($this->request->post['make_id'] == '') {
            $this->error['make_id'] = $this->language->get('error_make_id');
        }

        if (utf8_strlen($this->request->post['keyword']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

            if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !isset($this->request->get['product_id'])) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

}

?>
