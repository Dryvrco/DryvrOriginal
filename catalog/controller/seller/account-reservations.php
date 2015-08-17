<?php

class ControllerSellerAccountReservations extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('multiseller/reservations');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/reservations');

        $this->getList();
    }

    public function add() {
        $this->load->language('multiseller/bank');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/bank');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')/* && $this->validateForm() */) {

            $this->model_account_bank->addBank($this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('seller/account-bank' . $url, 'SSL'));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('multiseller/bank');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/bank');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')/* && $this->validateForm() */) {

            $this->model_account_bank->editBank($this->request->get['bank_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('seller/account-bank' . $url));
        }
        $this->getForm();
    }

    public function delete() {
        $this->load->language('multiseller/bank');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('account/bank');

        $this->model_account_bank->deleteBank($this->request->get['bank_id']);


        $this->session->data['success'] = $this->language->get('text_success');

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $this->response->redirect($this->url->link('seller/account-bank' . $url));
    }

    protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'username';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

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
            'href' => $this->url->link('seller/account-dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('seller/account-reservations')
        );

        $data['users'] = array();

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $total_reservations = $this->model_account_reservations->getTotalOrders($filter_data);

        $results = $this->model_account_reservations->getOrders($filter_data);

        foreach ($results as $result) {
            $data['orders'][] = array(
                'order_id' => $result['order_id'],
                'customer' => $result['firstname'] . ' ' . $result['lastname'],
                'status' => $result['name'],
                'date_added' => date("m-d-Y", strtotime($result['start_date'])) . ' ' . date("h:i A", strtotime($result['start_time'])),
                'view' => $this->url->link('seller/account-order/viewOrder&order_id=' . $result['order_id'])
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('heading_title');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['column_order_id'] = $this->language->get('column_order_id');
        $data['column_customer'] = $this->language->get('column_customer');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_time_start'] = $this->language->get('column_time_start');
        $data['column_action'] = $this->language->get('column_action');

        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');

        if (isset($this->error['warning'])) {
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

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array) $this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_username'] = $this->url->link('user/user&sort=username' . $url);
        $data['sort_status'] = $this->url->link('user/user&sort=status' . $url);
        $data['sort_date_added'] = $this->url->link('user/user&sort=date_added' . $url);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $total_reservations;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('user/user' . $url . '&page={page}', 'SSL');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($total_reservations) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($total_reservations - $this->config->get('config_limit_admin'))) ? $total_reservations : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $total_reservations, ceil($total_reservations / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-reservation-list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/multiseller/account-reservation-list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/catalog/multiseller/account-reservation-list.tpl', $data));
        }
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = !isset($this->request->get['bank_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_bank_name'] = $this->language->get('entry_bank_name');
        $data['entry_routing_number'] = $this->language->get('entry_routing_number');
        $data['entry_account_number'] = $this->language->get('entry_account_number');
        $data['entry_account_title'] = $this->language->get('entry_account_title');
        $data['entry_swift_code'] = $this->language->get('entry_swift_code');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['confirm'])) {
            $data['error_confirm'] = $this->error['confirm'];
        } else {
            $data['error_confirm'] = '';
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

        $url = '';

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
            'href' => $this->url->link('common/dashboard')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('user/user', 'token=' . $this->session->data['token'] . $url, 'SSL')
        );

        if (!isset($this->request->get['bank_id'])) {
            $data['action'] = $this->url->link('seller/account-bank/add');
        } else {
            $data['action'] = $this->url->link('seller/account-bank/edit&bank_id=' . $this->request->get['bank_id'] . $url);
        }

        $data['cancel'] = $this->url->link('seller/account-bank');

        if (isset($this->request->get['bank_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $user_info = $this->model_account_bank->getBank($this->request->get['bank_id']);
        }

        if (isset($this->request->post['bank_name'])) {
            $data['bank_name'] = $this->request->post['bank_name'];
        } elseif (!empty($user_info)) {
            $data['bank_name'] = $user_info['bank_name'];
        } else {
            $data['bank_name'] = '';
        }

        if (isset($this->request->post['routing_number'])) {
            $data['routing_number'] = $this->request->post['routing_number'];
        } elseif (!empty($user_info)) {
            $data['routing_number'] = $user_info['routing_number'];
        } else {
            $data['routing_number'] = '';
        }

        if (isset($this->request->post['account_number'])) {
            $data['firstname'] = $this->request->post['account_number'];
        } elseif (!empty($user_info)) {
            $data['account_number'] = $user_info['account_number'];
        } else {
            $data['account_number'] = '';
        }

        if (isset($this->request->post['account_title'])) {
            $data['account_title'] = $this->request->post['account_title'];
        } elseif (!empty($user_info)) {
            $data['account_title'] = $user_info['account_title'];
        } else {
            $data['account_title'] = '';
        }

        if (isset($this->request->post['swift_code'])) {
            $data['swift_code'] = $this->request->post['swift_code'];
        } elseif (!empty($user_info)) {
            $data['swift_code'] = $user_info['swift_code'];
        } else {
            $data['swift_code'] = '';
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-bank-form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/multiseller/account-bank-form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/multiseller/account-bank-form.tpl', $data));
        }
    }

    protected function validateForm() {
        /* if (!$this->user->hasPermission('modify', 'user/user')) {
          $this->error['warning'] = $this->language->get('error_permission');
          } */
        /*
          if ((utf8_strlen($this->request->post['username']) < 3) || (utf8_strlen($this->request->post['username']) > 20)) {
          $this->error['username'] = $this->language->get('error_username');
          }

          $user_info = $this->model_account_bank->getUserByUsername($this->request->post['username']);

          if (!isset($this->request->get['user_id'])) {
          if ($user_info) {
          $this->error['warning'] = $this->language->get('error_exists');
          }
          } else {
          if ($user_info && ($this->request->get['user_id'] != $user_info['user_id'])) {
          $this->error['warning'] = $this->language->get('error_exists');
          }
          }

          if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
          $this->error['firstname'] = $this->language->get('error_firstname');
          }

          if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
          $this->error['lastname'] = $this->language->get('error_lastname');
          }

          if ($this->request->post['password'] || (!isset($this->request->get['user_id']))) {
          if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
          $this->error['password'] = $this->language->get('error_password');
          }

          if ($this->request->post['password'] != $this->request->post['confirm']) {
          $this->error['confirm'] = $this->language->get('error_confirm');
          }
          }

          return !$this->error; */
    }

    protected function validateDelete() {
        /* if (!$this->user->hasPermission('modify', 'user/user')) {
          $this->error['warning'] = $this->language->get('error_permission');
          } */

        foreach ($this->request->post['selected'] as $user_id) {
            if ($this->user->getId() == $user_id) {
                $this->error['warning'] = $this->language->get('error_account');
            }
        }

        return !$this->error;
    }

}
