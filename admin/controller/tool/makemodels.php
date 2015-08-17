<?php

set_time_limit(0);
ini_set('memory_limit', '9999M');
error_reporting(-1);

class ControllerToolMakemodels extends Controller {

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
            'href' => $this->url->link('tool/makemodels', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $data['restore'] = $this->url->link('tool/makemodels/export', 'token=' . $this->session->data['token'], 'SSL');

        $data['export'] = $this->url->link('tool/makemodels/export', 'token=' . $this->session->data['token'], 'SSL');
        $data['export1'] = $this->url->link('tool/makemodels/export1', 'token=' . $this->session->data['token'], 'SSL');

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

        $this->response->setOutput($this->load->view('tool/makemodels.tpl', $data));
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

        $sql = "SELECT cp.category_id AS category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR ' -> ') AS name, c1.parent_id, c1.sort_order, c1.type FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int) $this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int) $this->config->get('config_language_id') . "' AND c1.type = 'mm' AND c1.parent_id != '0'";
        
        $sql .= " GROUP BY cp.category_id ORDER BY name ASC";

        $query = $this->db->query($sql);

        foreach ($query->rows as $row) {

            $data1[] = array(
                'category_id' => $row['category_id'],
                'name' => $row['name']
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
        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, 'Model ID');
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, 'Name');
        $i = 2;

        foreach ($data1 as $product) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $product['category_id']);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $product['name']);
            $i++;
        }


        $filename = 'Make-Models-' . time() . '.xls';
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