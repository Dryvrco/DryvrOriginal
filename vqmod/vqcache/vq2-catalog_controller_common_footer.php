<?php

class ControllerCommonFooter extends Controller {

    public function index() {

				$data = array_merge($this->load->language('multiseller/multiseller'), isset($data) ? $data : array());
			
        $this->load->language('common/footer');

        $data['text_information'] = $this->language->get('text_information');
        $data['text_service'] = $this->language->get('text_service');
        $data['text_extra'] = $this->language->get('text_extra');
        $data['text_contact'] = $this->language->get('text_contact');
        $data['text_return'] = $this->language->get('text_return');
        $data['text_sitemap'] = $this->language->get('text_sitemap');
        $data['text_manufacturer'] = $this->language->get('text_manufacturer');
        $data['text_voucher'] = $this->language->get('text_voucher');
        $data['text_affiliate'] = $this->language->get('text_affiliate');
        $data['text_special'] = $this->language->get('text_special');
        $data['text_account'] = $this->language->get('text_account');
        $data['text_order'] = $this->language->get('text_order');
        $data['text_wishlist'] = $this->language->get('text_wishlist');
        $data['text_newsletter'] = $this->language->get('text_newsletter');

        $this->load->model('catalog/information');

        $data['informations'] = array();

        foreach ($this->model_catalog_information->getInformations() as $result) {
            if ($result['bottom']) {
                $data['informations'][] = array(
                    'title' => $result['title'],
                    'href' => $this->url->link('information/information', 'information_id=' . $result['information_id'])
                );
            }
        }

        $this->load->model('common/home');
        $data['car_cities'] = array();

        
        $data['cities'] = $this->model_common_home->getAllCities();

        foreach ($data['cities'] as $city) {
            $data['car_cities'][] = array(
                'name' => $city['name'],
                'href' => $this->url->link('product/listing', 'city_id=' . $city['city_id']) 
            );
        }

        $data['car_makes'] = array();

        $data['makes'] = $this->model_common_home->getMakes();
 
        foreach ($data['makes'] as $make) {  
            $data['car_makes'][] = array(
                'name' => $make['name'],
                'href' => $this->url->link('product/listing', 'make_id=' . $make['category_id']) 
            );
        }
        
        $data['contact'] = $this->url->link('information/contact');
        $data['return'] = $this->url->link('account/return/add', '', 'SSL');
        $data['sitemap'] = $this->url->link('information/sitemap');
        $data['manufacturer'] = $this->url->link('product/manufacturer');
        $data['voucher'] = $this->url->link('account/voucher', '', 'SSL');
        $data['affiliate'] = $this->url->link('affiliate/account', '', 'SSL');
        $data['special'] = $this->url->link('product/special');
        $data['account'] = $this->url->link('account/account', '', 'SSL');
        $data['order'] = $this->url->link('account/order', '', 'SSL');
        $data['wishlist'] = $this->url->link('account/favorites', '', 'SSL');
        $data['newsletter'] = $this->url->link('account/newsletter', '', 'SSL');


		if($this->customer->isLogged() &&  ($this->config->get('advancedlogin_customer_require_enable'))){
		$data['hideadl']=1;
		}
 		else{
		$data['hideadl']=0;
		}
		
		$data['customcss']=$this->config->get('advancedlogin_customcss');
		
		$this->language->load('account/register');
		$data['entry_company']= $this->language->get('entry_company');
        $data['entry_firstname']= $this->language->get('entry_firstname');	
        $data['entry_lastname']= $this->language->get('entry_lastname');			
		
		
    	$data['entry_address_1']= $this->language->get('entry_address_1');
    	$data['entry_address_2']= $this->language->get('entry_address_2');
    	$data['entry_postcode']= $this->language->get('entry_postcode');
    	$data['entry_city']= $this->language->get('entry_city');
    	$data['entry_country']= $this->language->get('entry_country');
    	$data['entry_zone']= $this->language->get('entry_zone');
	
		$data['text_none']= $this->language->get('text_none');
		$data['error_country']= $this->language->get('error_country');
		$data['error_zone']= $this->language->get('error_zone');
		$data['error_postcode']= $this->language->get('error_postcode');
			
		$data['entry_telephone']= $this->language->get('entry_telephone');
    	$data['entry_fax']= $this->language->get('entry_fax');
		$this->load->model('localisation/country');
		$data['usecountry']=0;
		
		$this->language->load('module/advancedlogin');
		$data['text_select']= $this->language->get('text_select');
		
    	$data['countries']= $this->model_localisation_country->getCountries();
		$data['popupheading']=html_entity_decode($this->config->get('advancedlogin_fieldpopup_'.$this->config->get('config_language_id')));
	
		
			
        $data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

        // Whos Online
        if ($this->config->get('config_customer_online')) {
            $this->load->model('tool/online');

            if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
                $url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
            } else {
                $url = '';
            }

            if (isset($this->request->server['HTTP_REFERER'])) {
                $referer = $this->request->server['HTTP_REFERER'];
            } else {
                $referer = '';
            }

            $this->model_tool_online->whosonline($ip, $this->customer->getId(), $url, $referer);
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/footer.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/common/footer.tpl', $data);
        } else {
            return $this->load->view('default/template/common/footer.tpl', $data);
        }
    }

}
