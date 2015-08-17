<?php

class ControllerAccountEdit extends Controller {

    private $error = array();

    public function index() {
        if (!$this->customer->isLogged()) {
            $this->session->data['redirect'] = $this->url->link('account/edit', '', 'SSL');

            $this->response->redirect($this->url->link('account/login', '', 'SSL'));
        }

        if ($this->agency->isLogged()) {
            $this->response->redirect($this->url->link('account/agencylogin'));
        }

        $this->load->language('account/edit');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
        $this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

        $this->load->model('account/customer');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_account_customer->editCustomer($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('edit', $activity_data);

            $this->response->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_edit'),
            'href' => $this->url->link('account/edit', '', 'SSL')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_your_details'] = $this->language->get('text_your_details');
        $data['text_additional'] = $this->language->get('text_additional');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_loading'] = $this->language->get('text_loading');

        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_lastname'] = $this->language->get('entry_lastname');
        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_telephone'] = $this->language->get('entry_telephone');
        $data['entry_fax'] = $this->language->get('entry_fax');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_back'] = $this->language->get('button_back');
        $data['button_upload'] = $this->language->get('button_upload');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['firstname'])) {
            $data['error_firstname'] = $this->error['firstname'];
        } else {
            $data['error_firstname'] = '';
        }

        if (isset($this->error['lastname'])) {
            $data['error_lastname'] = $this->error['lastname'];
        } else {
            $data['error_lastname'] = '';
        }

        if (isset($this->error['email'])) {
            $data['error_email'] = $this->error['email'];
        } else {
            $data['error_email'] = '';
        }

        if (isset($this->error['telephone'])) {
            $data['error_telephone'] = $this->error['telephone'];
        } else {
            $data['error_telephone'] = '';
        }

        if (isset($this->error['custom_field'])) {
            $data['error_custom_field'] = $this->error['custom_field'];
        } else {
            $data['error_custom_field'] = array();
        }

        $data['action'] = $this->url->link('account/edit', '', 'SSL');

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
        }

        if (isset($this->request->post['firstname'])) {
            $data['firstname'] = $this->request->post['firstname'];
        } elseif (!empty($customer_info)) {
            $data['firstname'] = $customer_info['firstname'];
        } else {
            $data['firstname'] = '';
        }

        if (isset($this->request->post['lastname'])) {
            $data['lastname'] = $this->request->post['lastname'];
        } elseif (!empty($customer_info)) {
            $data['lastname'] = $customer_info['lastname'];
        } else {
            $data['lastname'] = '';
        }

        if (isset($this->request->post['email'])) {
            $data['email'] = $this->request->post['email'];
        } elseif (!empty($customer_info)) {
            $data['email'] = $customer_info['email'];
        } else {
            $data['email'] = '';
        }

        if (isset($this->request->post['telephone'])) {
            $data['telephone'] = $this->request->post['telephone'];
        } elseif (!empty($customer_info)) {
            $data['telephone'] = $customer_info['telephone'];
        } else {
            $data['telephone'] = '';
        }

        if (isset($this->request->post['fax'])) {
            $data['fax'] = $this->request->post['fax'];
        } elseif (!empty($customer_info)) {
            $data['fax'] = $customer_info['fax'];
        } else {
            $data['fax'] = '';
        }

        if (isset($this->request->post['country_id'])) {
            $data['country_id'] = $this->request->post['country_id'];
        } elseif (!empty($customer_info)) {
            $data['country_id'] = $customer_info['country_id'];
        } else {
            $data['country_id'] = '';
        }

        if (isset($this->request->post['zone_id'])) {
            $data['zone_id'] = $this->request->post['zone_id'];
        } elseif (!empty($customer_info)) {
            $data['zone_id'] = $customer_info['zone_id'];
        } else {
            $data['zone_id'] = '';
        }

        if (isset($this->request->post['dob_dd'])) {
            $data['dob_dd'] = $this->request->post['dob_dd'];
        } elseif (!empty($customer_info)) {
            $data['dob_dd'] = $customer_info['dob_dd'];
        } else {
            $data['dob_dd'] = '';
        }

        if (isset($this->request->post['dob_mm'])) {
            $data['dob_mm'] = $this->request->post['dob_mm'];
        } elseif (!empty($customer_info)) {
            $data['dob_mm'] = $customer_info['dob_mm'];
        } else {
            $data['dob_mm'] = '';
        }

        if (isset($this->request->post['dob_yy'])) {
            $data['dob_yy'] = $this->request->post['dob_yy'];
        } elseif (!empty($customer_info)) {
            $data['dob_yy'] = $customer_info['dob_yy'];
        } else {
            $data['dob_yy'] = '';
        }

        if (isset($this->request->post['dl_expiry_dd'])) {
            $data['dl_expiry_dd'] = $this->request->post['dl_expiry_dd'];
        } elseif (!empty($customer_info)) {
            $data['dl_expiry_dd'] = $customer_info['dl_expiry_dd'];
        } else {
            $data['dl_expiry_dd'] = '';
        }

        if (isset($this->request->post['dl_expiry_mm'])) {
            $data['dl_expiry_mm'] = $this->request->post['dl_expiry_mm'];
        } elseif (!empty($customer_info)) {
            $data['dl_expiry_mm'] = $customer_info['dl_expiry_mm'];
        } else {
            $data['dl_expiry_mm'] = '';
        }

        if (isset($this->request->post['dl_expiry_yy'])) {
            $data['dl_expiry_yy'] = $this->request->post['dl_expiry_yy'];
        } elseif (!empty($customer_info)) {
            $data['dl_expiry_yy'] = $customer_info['dl_expiry_yy'];
        } else {
            $data['dl_expiry_yy'] = '';
        }

        if (isset($this->request->post['ins_company_name'])) {
            $data['ins_company_name'] = $this->request->post['ins_company_name'];
        } elseif (!empty($customer_info)) {
            $data['ins_company_name'] = $customer_info['ins_company_name'];
        } else {
            $data['ins_company_name'] = '';
        }

        if (isset($this->request->post['ins_agent_name'])) {
            $data['ins_agent_name'] = $this->request->post['ins_agent_name'];
        } elseif (!empty($customer_info)) {
            $data['ins_agent_name'] = $customer_info['ins_agent_name'];
        } else {
            $data['ins_agent_name'] = '';
        }

        if (isset($this->request->post['ins_policy_number'])) {
            $data['ins_policy_number'] = $this->request->post['ins_policy_number'];
        } elseif (!empty($customer_info)) {
            $data['ins_policy_number'] = $customer_info['ins_policy_number'];
        } else {
            $data['ins_policy_number'] = '';
        }

        if (isset($this->request->post['ins_expiry_dd'])) {
            $data['ins_expiry_dd'] = $this->request->post['ins_expiry_dd'];
        } elseif (!empty($customer_info)) {
            $data['ins_expiry_dd'] = $customer_info['ins_expiry_dd'];
        } else {
            $data['ins_expiry_dd'] = '';
        }

        if (isset($this->request->post['ins_expiry_mm'])) {
            $data['ins_expiry_mm'] = $this->request->post['ins_expiry_mm'];
        } elseif (!empty($customer_info)) {
            $data['ins_expiry_mm'] = $customer_info['ins_expiry_mm'];
        } else {
            $data['ins_expiry_mm'] = '';
        }

        if (isset($this->request->post['ins_expiry_yy'])) {
            $data['ins_expiry_yy'] = $this->request->post['ins_expiry_yy'];
        } elseif (!empty($customer_info)) {
            $data['ins_expiry_yy'] = $customer_info['ins_expiry_yy'];
        } else {
            $data['ins_expiry_yy'] = '';
        }

        if (isset($this->request->post['ins_name'])) {
            $data['ins_name'] = $this->request->post['ins_name'];
        } elseif (!empty($customer_info)) {
            $data['ins_name'] = $customer_info['ins_name'];
        } else {
            $data['ins_name'] = '';
        }

        if (isset($this->request->post['ins_phone'])) {
            $data['ins_phone'] = $this->request->post['ins_phone'];
        } elseif (!empty($customer_info)) {
            $data['ins_phone'] = $customer_info['ins_phone'];
        } else {
            $data['ins_phone'] = '';
        }

        if (isset($this->request->post['consent'])) {
            $data['consent'] = $this->request->post['consent'];
        } elseif (!empty($customer_info)) {
            $data['consent'] = $customer_info['consent'];
        } else {
            $data['consent'] = '';
        }

        if (isset($this->request->post['driver_license'])) {
            $data['driver_license'] = $this->request->post['driver_license'];
        } elseif (!empty($customer_info)) {
            $data['driver_license'] = $customer_info['driver_license'];
        } else {
            $data['driver_license'] = '';
        }

        $tot = strlen($data['driver_license']);
        $tot = $tot - 4;
        $data['editable'] = substr($data['driver_license'], -4);
        $data['noneditable'] = substr($data['driver_license'], 0, $tot);

        for ($x = 0; $x < $tot; $x++) {
            $str .= 'X';
        }
        $data['str'] = $str . $editable;

        if (isset($this->request->post['card_type'])) {
            $data['card_type'] = $this->request->post['card_type'];
        } elseif (!empty($customer_info)) {
            $data['card_type'] = $customer_info['card_type'];
        } else {
            $data['card_type'] = '';
        }

        if (isset($this->request->post['card_number'])) {
            $data['card_number'] = $this->request->post['card_number'];
        } elseif (!empty($customer_info)) {
            $data['card_number'] = $customer_info['card_number'];
        } else {
            $data['card_number'] = '';
        }

        $tot1 = strlen($data['card_number']);
        $tot1 = $tot1 - 4;
        $data['editable1'] = substr($data['card_number'], -4);
        $data['noneditable1'] = substr($data['card_number'], 0, $tot1);

        for ($x = 0; $x < $tot1; $x++) {
            $str1 .= 'X';
        }
        $data['str1'] = $str1 . $editable1;

        if (isset($this->request->post['expiry_date_mm'])) {
            $data['expiry_date_mm'] = $this->request->post['expiry_date_mm'];
        } elseif (!empty($customer_info)) {
            $data['expiry_date_mm'] = $customer_info['expiry_date_mm'];
        } else {
            $data['expiry_date_mm'] = '';
        }

        if (isset($this->request->post['expiry_date_yy'])) {
            $data['expiry_date_yy'] = $this->request->post['expiry_date_yy'];
        } elseif (!empty($customer_info)) {
            $data['expiry_date_yy'] = $customer_info['expiry_date_yy'];
        } else {
            $data['expiry_date_yy'] = '';
        }

        if (isset($this->request->post['card_name'])) {
            $data['card_name'] = $this->request->post['card_name'];
        } elseif (!empty($customer_info)) {
            $data['card_name'] = $customer_info['card_name'];
        } else {
            $data['card_name'] = '';
        }

        if (isset($this->request->post['insurance_info'])) {
            $data['insurance_info'] = $this->request->post['insurance_info'];
        } elseif (!empty($customer_info)) {
            $data['insurance_info'] = $customer_info['insurance_info'];
        } else {
            $data['insurance_info'] = '';
        }

        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        if (isset($this->request->post['custom_field'])) {
            $data['account_custom_field'] = $this->request->post['custom_field'];
        } elseif (isset($customer_info)) {
            $data['account_custom_field'] = unserialize($customer_info['custom_field']);
        } else {
            $data['account_custom_field'] = array();
        }

        $data['back'] = $this->url->link('account/account', '', 'SSL');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/edit.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/account/edit.tpl', $data));
        }
    }

    protected function validate() {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $this->error['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $this->error['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $this->error['email'] = $this->language->get('error_email');
        }

        if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
            $this->error['warning'] = $this->language->get('error_exists');
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $this->error['telephone'] = $this->language->get('error_telephone');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

        foreach ($custom_fields as $custom_field) {
            if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                $this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        return !$this->error;
    }

}
