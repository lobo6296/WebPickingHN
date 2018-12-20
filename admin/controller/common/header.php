<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_logout'] = $this->language->get('text_logout');
		
		// Reportes
	    $data['stockreport']				= $this->url->link('report/stockreport', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bypackinglist'] 				= $this->url->link('report/bypackinglist', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bymovements'] 				= $this->url->link('report/bymovements', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bybomnumber'] 				= $this->url->link('report/bybomnumber', 'token=' . $this->session->data['token'] , 'SSL');
		$data['damaged'] 					= $this->url->link('report/damaged', 'token=' . $this->session->data['token'] , 'SSL');
		$data['averangeoccupancy']			= $this->url->link('report/averangeoccupancy', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bydata']						= $this->url->link('report/bydata', 'token=' . $this->session->data['token'] , 'SSL');
		$data['packinglist']				= $this->url->link('report/packinglist', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bysite']						= $this->url->link('report/bysite', 'token=' . $this->session->data['token'] , 'SSL');
		$data['bysiteindetailthemovement']	= $this->url->link('report/bysiteindetailthemovement', 'token=' . $this->session->data['token'] , 'SSL');
		$data['inbounds']					= $this->url->link('report/inbounds', 'token=' . $this->session->data['token'] , 'SSL');
		$data['outbounds']					= $this->url->link('report/outbounds', 'token=' . $this->session->data['token'] , 'SSL');
		$data['returns']					= $this->url->link('report/returns', 'token=' . $this->session->data['token'] , 'SSL');
		$data['overtime']					= $this->url->link('report/overtime', 'token=' . $this->session->data['token'] , 'SSL');
		$data['generalstockbycode']			= $this->url->link('report/generalstockbycode', 'token=' . $this->session->data['token'] , 'SSL');
		$data['summaryofmovement']			= $this->url->link('report/summaryofmovement', 'token=' . $this->session->data['token'] , 'SSL');
		$data['inboundbydate']				= $this->url->link('report/inboundbydate', 'token=' . $this->session->data['token'] , 'SSL');

		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/dashboard', '', true);
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);
			$current_ip=$this->config->get('current_ip');
			$ip_cbs    =$this->config->get($current_ip);
			$data['ip_cbs'] = $ip_cbs;
						
							
			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}

		return $this->load->view('common/header', $data);
	}
}
