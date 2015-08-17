<?php

class ControllerSellerAccountReviews extends Controller {

    private $error = array();

    public function index() {
        
        if (!$this->agency->isLogged()) {
            $this->response->redirect($this->url->link('account/agencylogin'));
        }
        
        $this->load->language('multiseller/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

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

    public function detail() {
        $this->load->language('multiseller/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->getForm();
    }

    public function delete() {
        $this->load->language('multiseller/review');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/review');

        $this->model_catalog_review->deleteReview($this->request->get['review_id']);

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

        $this->response->redirect($this->url->link('seller/account-reviews' . $url));
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

        $data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => "Agency Dashboard",
                'href' => $this->url->link('seller/account-dashboard'),
            ),
            array(
                'text' => "Reviews",
                'href' => $this->url->link('seller/account-reviews'),
            )
        ));

        $data['add'] = $this->url->link('seller/account-bank/add');

        $data['reviews'] = array();

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $seller_id = $this->agency->getSellerId();
        
        $reviews_total = $this->model_catalog_review->getTotalReviews($seller_id);

        $results = $this->model_catalog_review->getReviews($seller_id);

        foreach ($results as $result) {
            $stars = $result['rating'];
            $nostars = 5 - $result['rating'];
            $data['reviews'][] = array(
                'review_id' => $result['review_id'],
                'author' => $result['author'],
                'text' => summary($result['text']),
                'stars' => $stars,
                'nostars' => $nostars,
                'status' => $result['status'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'view' => $this->url->link('seller/account-reviews/detail&review_id=' . $result['review_id'])
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');

        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['column_username'] = $this->language->get('column_username');
        $data['column_status'] = $this->language->get('column_status');
        $data['column_date_added'] = $this->language->get('column_date_added');
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
        $pagination->total = $reviews_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('user/user' . $url . '&page={page}');

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($reviews_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($reviews_total - $this->config->get('config_limit_admin'))) ? $reviews_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $reviews_total, ceil($reviews_total / $this->config->get('config_limit_admin')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-review-list.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/multiseller/account-review-list.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/catalog/multiseller/account-review-list.tpl', $data));
        }
    }

    protected function getForm() {
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_form'] = $this->language->get('text_view');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_author'] = $this->language->get('entry_author');
        $data['entry_rating'] = $this->language->get('entry_rating');
        $data['entry_text'] = $this->language->get('entry_text');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_date_added'] = $this->language->get('entry_date_added');

        $data['back'] = $this->url->link('seller/account-reviews');

        $data['breadcrumbs'] = $this->MsLoader->MsHelper->setBreadcrumbs(array(
            array(
                'text' => "Agency Dashboard",
                'href' => $this->url->link('seller/account-dashboard'),
            ),
            array(
                'text' => "Reviews",
                'href' => $this->url->link('seller/account-reviews'),
            )
        ));

        $this->load->model('catalog/review');

        $review = $this->model_catalog_review->getReview($this->request->get['review_id']);
        
        $data['author'] = $review['author'];
        $data['text'] = $review['text'];
        $data['status'] = $review['status'];
        $data['date_added'] = date($this->language->get('datetime_format'), strtotime($review['date_added']));

        $data['stars'] = $review['rating'];
        $data['nostars'] = 5 - $review['rating'];

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/multiseller/account-review-form.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/multiseller/account-review-form.tpl', $data));
        } else {
            $this->response->setOutput($this->load->view('default/template/multiseller/account-review-form.tpl', $data));
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
