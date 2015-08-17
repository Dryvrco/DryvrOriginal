<?php

/**
 * TMD(http://opencartextensions.in/)
 *
 * Copyright (c) 2006 - 2012 TMD
 * This package is Copyright so please us only one domain 
 * 
 */
set_time_limit(0);
ini_set('memory_limit', '999M');
error_reporting(-1);

class ControllerToolimport extends Controller {

    private $error = array();

    public function index() {

        $totalnewproduct = 0;
        $totalupdateproduct = 0;
        $this->language->load('tool/import');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('tool/import');
        
        $this->load->model('catalog/product');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->user->hasPermission('modify', 'tool/backup')) {

            $this->load->library('excelreader/PHPExcel');
            $this->load->library('excelreader/PHPExcel/IOFactory');


            if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
                $content = file_get_contents($this->request->files['import']['tmp_name']);
            } else {
                $content = false;
            }

            if ($content) {
                ////////////////////////// Started Import work  //////////////
                try {
                    $objPHPExcel = PHPExcel_IOFactory::load($this->request->files['import']['tmp_name']);
                } catch (Exception $e) {
                    die('Error loading file "' . pathinfo($this->path . $files, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                }
                /* 	@ get a file data into $sheetDatas variable */
                $sheetDatas = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
                /* 	@ $i variable for getting data. in first iteration of loop we get size and color name of product */
                $i = 0;
                /*
                  @ arranging the data according to our need
                 */
                foreach ($sheetDatas as $sheetData) {
                    if ($i != 0) {
                        $product_id = $sheetData['A'];

                            /* Step Product other  info collect */
                            $product_id = $sheetData['A'];
                            $model_id = $sheetData['B'];
                            $model = $sheetData['C'];
                            $daily = $sheetData['D'];
                            $weekly = $sheetData['E'];
                            $monthly = $sheetData['F'];
                            $description = $sheetData['G'];
                            $meta_title = $sheetData['H'];
                            $status = $sheetData['I'];
                            $delivery = $sheetData['J'];
                            $min_age = $sheetData['K'];
                            $airport = $sheetData['L'];
                            $insurance = $sheetData['M'];
                            $security = $sheetData['N'];
                            $data = '';
                            $data = array(
                                'product_id' => $product_id,
                                'make_id' => $this->model_catalog_product->getMakeId($model_id),
                                'model_id' => $model_id,
                                'model' => $model,
                                'daily' => $daily,
                                'weekly' => $weekly,
                                'monthly' => $monthly,
                                'image' => $mainimage,
                                'description' => $description,
                                'meta_title' => $meta_title,
                                'status' => $status,
                                'delivery' => $delivery,
                                'min_age' => $min_age,
                                'airport' => $airport,
                                'insurance' => $insurance,
                                'security' => $security
                            );

                            //$product_id = $this->model_tool_import->getproductbymodel($model);
                            if ($product_id=='') {
                                $this->model_tool_import->addproduct($data);
                                $totalnewproduct++;
                            } else {
                                $this->model_tool_import->editproduct($data, $product_id);
                                $totalupdateproduct++;
                            }
                        
                    }
                    $i++;
                }
                $this->session->data['success'] = $totalupdateproduct . ' :: Total product update ' . $totalnewproduct . ':: Total New product added';

                ////////////////////////// Started Import work  //////////////
                //$this->response->redirect($this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'));
            } else {
                $this->error['warning'] = $this->language->get('error_empty');
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['button_import'] = $this->language->get('button_import');
        $data['entry_import'] = $this->language->get('entry_import');


        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['import'] = $this->url->link('tool/import', 'token=' . $this->session->data['token'], 'SSL');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/import.tpl', $data));
    }

}

?>