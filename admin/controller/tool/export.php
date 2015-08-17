<?php

set_time_limit(0);
ini_set('memory_limit', '9999M');
error_reporting(-1);

class ControllerToolExport extends Controller {

    private $error = array();

    public function index() {
        $this->language->load('tool/export');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['heading_title'] = $this->language->get('heading_title');

        $data['button_export'] = $this->language->get('button_export');
        $data['button_exportoc'] = $this->language->get('button_exportoc');
        $data['entry_exportxls'] = $this->language->get('entry_exportxls');
        $data['entry_exportocxls'] = $this->language->get('entry_exportocxls');
        $data['entry_number'] = $this->language->get('entry_number');
        $data['help_number'] = $this->language->get('help_number');
        $data['entry_category'] = $this->language->get('entry_category');


        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->request->get['number'])) {
            $data['number'] = $this->request->get['number'];
        } else {
            $data['number'] = '0';
        }


        $product_cont = $this->getTotalProducts();

        if (isset($this->request->get['end'])) {
            $data['end'] = $this->request->get['end'];
        } elseif (!empty($product_cont)) {
            $data['end'] = $product_cont;
        } else {
            $data['end'] = '';
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
            'href' => $this->url->link('tool/export', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['restore'] = $this->url->link('tool/export/export', 'token=' . $this->session->data['token'], 'SSL');

        $data['export'] = $this->url->link('tool/export/export', 'token=' . $this->session->data['token'], 'SSL');
        $data['export1'] = $this->url->link('tool/export/export1', 'token=' . $this->session->data['token'], 'SSL');

        $this->load->model('catalog/category');
        $data['categories'] = array();

        $data1 = array(
        );
        $results = $this->model_catalog_category->getCategories($data1);

        foreach ($results as $result) {

            $data['categories'][] = array(
                'category_id' => $result['category_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
                'selected' => isset($this->request->post['selected']) && in_array($result['category_id'], $this->request->post['selected'])
            );
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('tool/export.tpl', $data));
    }

    public function getTotalProducts() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product");

        return $query->row['total'];
    }

    public function export() {
        $this->load->library('export/PHPExcel');
        $this->load->library('export/PHPExcel/IOFactory');
        //include  'PHPExcel.php';

        $data1 = array();
        $start = $this->request->post['number'];
        $end2 = $this->request->post['end'];
        if (!empty($this->request->post['category'])) {
            $category = true;
            $categoryvalue = $this->request->post['category'];
        } else {
            $category = false;
        }

        $sql = "SELECT * FROM `" . DB_PREFIX . "product` as p left join " . DB_PREFIX . "product_description as pd on p.`product_id`= pd.`product_id` ";

        /*if ($category) {
            $sql .=" left join " . DB_PREFIX . "product_to_category as pc on pc.`product_id`= p.`product_id`   ";
        }*/

        $sql .=" where pd.language_id = '" . (int) $this->config->get('config_language_id') . "' ";

        if ($category) {
            $sql .="  and (p.make_id='" . $categoryvalue . "' OR p.model_id='" . $categoryvalue . "')";
        }


        if (!empty($end2) && !empty($start)) {
            $sql .=" limit " . (int) $start . "," . (int) $end2 . "";
        }


        $query = $this->db->query($sql);

        foreach ($query->rows as $row) {

            //////////////////////////// seo_keyword///
            $seo_keyword = '';
            $query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'product_id=" . (int) $row['product_id'] . "' limit 0,1");
            if ($query1->row) {
                $seo_keyword = $query1->row['keyword'];
            }
            ///////////////////////////////seo_keyword///////
            ////////////////////////////////manufacturer///////////
            $manufacturer = '';
            $query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer m LEFT JOIN " . DB_PREFIX . "manufacturer_to_store m2s ON (m.manufacturer_id = m2s.manufacturer_id) WHERE m.manufacturer_id = '" . (int) $row['manufacturer_id'] . "' AND m2s.store_id = '" . (int) $this->config->get('config_store_id') . "'");
            if ($query1->row) {
                $manufacturer = $query1->row['name'];
            }
            ////////////////////////////////manufacturer///////////
            ///////////////////////////////////// Category ////////////
            $categories = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_category where product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $category_id) {
                    $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' &gt; ') AS name, c.parent_id, c.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c ON (cp.path_id = c.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (c.category_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int) $this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int) $this->config->get('config_language_id') . "'";
                    $sql .= " AND cd2.category_id LIKE '" . $category_id['category_id'] . "%'";
                    $sql .= " GROUP BY cp.category_id ORDER BY name";
                    $categoryqyery = $this->db->query($sql);
                    $categories .=$categoryqyery->row['name'] . ';';
                }
            }
            ///////////////////////////////////// Category ////////////
            ///////////////////////////////////// Stores ////////////
            $stores = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store where product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $store_id) {
                    $stores .=$store_id['store_id'] . ';';
                }
            }
            ///////////////////////////////////// Stores ////////////
            ///////////////////////////////////// images ////////////
            $images = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_image where product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $image) {
                    $images .=$image['image'] . ';';
                }
            }

            ///////////////////////////////////// images ////////////
            ///////////////////////////////////// Product Special ////////////
            $product_sp = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_special where product_id='" . $row['product_id'] . "' order by product_special_id DESC limit 0,1");
            if ($sq11->rows) {
                foreach ($sq11->rows as $sp) {
                    $product_sp .=$sp['date_start'] . ':' . $sp['date_end'] . ':' . $sp['price'] . ';';
                }
            }
            ///////////////////////////////////// Product Special ////////////
            ////////////////////////////////// Option Collection option:type
            $options = '';
            $option_value_ids = array();
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po left join " . DB_PREFIX . "option_description od on od.option_id=po.option_id  left join `" . DB_PREFIX . "option` o on o.option_id=po.option_id  where po.product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $option) {
                    $options .=$option['name'] . ':' . $option['type'];
                    $option_value_ids[] = array('option_id' => $option['option_id'], 'name' => $option['name']);
                }
            }



            ////////////////////////////////// Option Collection
            ////////////////////////////////// Option value collections 
            ///////////////option:value1-qty-Subtract Stock-Price-Points-Weight;
            $optionvalue = '';
            foreach ($option_value_ids as $option) {

                $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value po left join " . DB_PREFIX . "option_value_description od on od.option_value_id=po.option_value_id  left join " . DB_PREFIX . "option_value ov on ov.option_value_id=po.option_value_id  where po.product_id='" . $row['product_id'] . "'");
                foreach ($sq11->rows as $option_value) {
                    $optionvalue .=$option['name'] . ':' . $option_value['name'] . '-' . $option_value['quantity'] . '-' . $option_value['subtract'] . '-' . round($option_value['price'], 2) . '-' . $option_value['points'] . '-' . round($option_value['weight'], 2) . '-' . $option_value['sort_order'] . ';';
                }
            }
            ////////////////////////////////// Option value collections
            ////////////////////////////// Filter group name collection////////
            $filter_group = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_filter po left join " . DB_PREFIX . "filter od on od.filter_id=po.filter_id left join " . DB_PREFIX . "filter_group_description fgd on fgd.filter_group_id=od.filter_group_id left join " . DB_PREFIX . "filter_group fg on fg.filter_group_id=od.filter_group_id where po.product_id='" . $row['product_id'] . "' and fgd.language_id='1'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $filter_groups) {
                    $filter_group .=$filter_groups['name'] . ':' . $filter_groups['sort_order'] . ';';
                }
            }
            ////////////////////////////// Filter group name collection////////
            ////////////////////////////// Filter group name collection////////
            $filter_name = '';
            $sq11 = $this->db->query("SELECT fgd.name as groupname,od.name as name,fgdn.sort_order FROM " . DB_PREFIX . "product_filter po left join " . DB_PREFIX . "filter_description od on od.filter_id=po.filter_id left join " . DB_PREFIX . "filter_group_description fgd on fgd.filter_group_id=od.filter_group_id left join " . DB_PREFIX . "filter fgdn on fgdn.filter_id=po.filter_id   where po.product_id='" . $row['product_id'] . "' and fgd.language_id='1' and od.language_id='1'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $filter_names) {
                    $filter_name .=$filter_names['groupname'] . '=' . $filter_names['name'] . ':' . $filter_names['sort_order'] . ';';
                }
            }
            ////////////////////////////// Filter group name collection////////
            ////////////////////////////// Discount collection////////
            $discounts = '';
            $sq11 = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_discount where product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $discount) {
                    $discounts .=$discount['customer_group_id'] . ':' . $discount['quantity'] . ':' . $discount['priority'] . ':' . $discount['price'] . ':' . $discount['date_start'] . ':' . $discount['date_end'] . ';';
                }
            }
            ////////////////////////////// Discount collection////////
            ////////////////////////////// att collection////////
            $atts = '';
            $sq11 = $this->db->query("SELECT agd.name as groupname,ag.sort_order as groupsort,ad.name as attname,a.sort_order as attsort,pa.text as text from  " . DB_PREFIX . "product_attribute pa   left join " . DB_PREFIX . "attribute a on a.attribute_id=pa.attribute_id  left join " . DB_PREFIX . "attribute_description ad on ad.attribute_id=pa.attribute_id left join " . DB_PREFIX . "attribute_group ag on ag.attribute_group_id=a.attribute_group_id  left join " . DB_PREFIX . "attribute_group_description agd on agd.attribute_group_id=ag.attribute_group_id  where pa.product_id='" . $row['product_id'] . "' and ad.language_id='1' and agd.language_id='1'  and ad.language_id='1'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $att) {
                    $atts .=$att['groupname'] . ':' . $att['groupsort'] . '=' . $att['attname'] . '-' . $att['text'] . '-' . $att['attsort'] . ';';
                }
            }
            ////////////////////////////// att collection////////
            /////////////////////////////// Related product//////
            $related = '';
            $sq11 = $this->db->query("SELECT pn.model as model FROM " . DB_PREFIX . "product_related  pr  left join " . DB_PREFIX . "product pn on pn.product_id=pr.related_id where pr.product_id='" . $row['product_id'] . "'");
            if ($sq11->rows) {
                foreach ($sq11->rows as $rp) {
                    $related .=$rp['model'] . ';';
                }
            }

            /////////////////////////////// Related product ///////////////////////////////
            
           $this->load->model('catalog/product');

            $data1[] = array(
                'product_id' => $row['product_id'],
                'model_id' => $row['model_id'],
                'model' => $row['model'],
                'daily' => $row['daily'],
                'weekly' => $row['weekly'],
                'monthly' => $row['monthly'],
                'description' => $row['description'],
                'meta_title' => $row['meta_title'],
                'status' => $row['status'],
                'delivery' => $row['delivery'],
                'min_age' => $row['min_age'],
                'airport' => $row['airport'],
                'insurance' => $row['insurance'],
                'security' => $row['security']
            );
        }

        $objPHPExcel = new PHPExcel();

        // Set properties

        $objPHPExcel->getProperties()->setCreator("Efcoders");
        $objPHPExcel->getProperties()->setLastModifiedBy("Efcoders");
        $objPHPExcel->getProperties()->setTitle("Office Excel");
        $objPHPExcel->getProperties()->setSubject("Office Excel");
        $objPHPExcel->getProperties()->setDescription("Office Excel");
        $objPHPExcel->setActiveSheetIndex(0);
        $i = 1;
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, 'Vehicle ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, 'Model ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Model');
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, 'Daily Charges');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, 'Weekly Charges');
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, 'Monthly Charges');
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, 'Description');
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, 'Meta Title');
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, 'Status (1=Enabled, 0=Disabled)');
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, 'Delivery');
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, 'Minimum Age');
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, 'Airport Charges');
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, 'Insurance (1=Yes, 0=No)');
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, 'Security');
        $i = 2;

        foreach ($data1 as $product) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $product['product_id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $product['model_id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $product['model']);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $product['daily']);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $product['weekly']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $product['monthly']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $product['description']);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $product['meta_title']);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $product['status']);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $product['delivery']);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $product['min_age']);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $product['airport']);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $product['insurance']);
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $product['security']);
            $i++;
        }


        $filename = 'DRYVR-' . time() . '.xls';
        $objPHPExcel->getActiveSheet()->setTitle('All product');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($filename);
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $objWriter->save('php://output');
        unlink($filename);
    }

    public function cleanData(&$str) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"'))
            $str = '"' . str_replace('"', '""', $str) . '"';
    }

}

?>