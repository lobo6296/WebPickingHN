<?php
class ControllerReportbysiteindetailthemovement extends Controller {
	
	public function download() {
		$this->load->model('tool/export');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
        if (isset($this->request->post['date_start'])) {
			$filter_date_start = $this->request->post['date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->post['date_end'])) {
			$filter_date_end = $this->request->post['date_end'];
		} else {
			$filter_date_end = null;
		}

		if (isset($this->request->post['sitio'])) {
			$filter_sitio = trim($this->request->post['sitio']," ");
		} else {
			$filter_sitio = null;
		}

		if (isset($this->request->post['hwpacking'])) {
			$filter_hwpacking = trim($this->request->post['hwpacking']," ");
		} else {
			$filter_hwpacking = null;
		}

        if (isset($this->request->post['hwartcod'])) {
			$filter_hwartcod = trim($this->request->post['hwartcod']," ");
		} else {
			$filter_hwartcod = null;
		}
		
		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_sitio'  	=> $filter_sitio,
			'filter_hwpacking'  => $filter_hwpacking,
			'filter_hwartcod'   => $filter_hwartcod,
			'filter_tipinv'     => $this->session->data['tipinv'],
			'tipo'              => $this->request->post['tipo'],
			'titulo'            => 'Deliveries by site in detail the movement',
			'reporte'           => 'bysiteindetailthemovement'
		);
		
		  $this->model_tool_export->download($filter_data);	
		 
		  $url = '';
                                                     
		 $this->response->redirect($this->url->link('report/bysiteindetailthemovement', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->index();		
	}
	
	public function pdf() {
 
     $this->load->model('tool/pdf');
	 
	 $filter_data = array (
	   'reporte' => 'bysiteindetailthemovement'
	 );
     $this->model_tool_pdf->generarReporte($filter_data);
 
	// $this->index();	
	}
	
	public function index() {
		$this->load->language('report/bysiteindetailthemovement');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		
		if (isset($this->request->get['filter_sitio'])) {
			$filter_sitio = trim($this->request->get['filter_sitio']," ");
		} else {
			$filter_sitio = null;
		}
		
		if (isset($this->request->get['filter_hwpacking'])) {
			$filter_hwpacking = trim($this->request->get['filter_hwpacking']," ");
		} else {
			$filter_hwpacking = null;
		}

        if (isset($this->request->get['filter_hwartcod'])) {
			$filter_hwartcod = trim($this->request->get['filter_hwartcod']," ");
		} else {
			$filter_hwartcod = null;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_sitio'])) {
			$url .= '&filter_sitio=' . $this->request->get['filter_sitio'];
		}
		
		if (isset($this->request->get['filter_hwpacking'])) {
			$url .= '&filter_hwpacking=' . $this->request->get['filter_hwpacking'];
		}
        
        if (isset($this->request->get['filter_hwartcod'])) {
			$url .= '&filter_hwartcod=' . $this->request->get['filter_hwartcod'];
		}
		
		$data['pdf'] = $this->url->link('report/bysiteindetailthemovement/pdf', 'token=' . $this->session->data['token'].$url, 'SSL');
		
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
			'href' => $this->url->link('report/bysiteindetailthemovement', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/tigo');

		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_sitio'  	=> $filter_sitio,
			'filter_hwpacking'  => $filter_hwpacking,
            'filter_hwartcod'   => $filter_hwartcod,
			'filter_tipinv'     => $this->session->data['tipinv'],
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$stock_total = $this->model_report_tigo->getTotalBysiteindetailthemovement($filter_data);

		$results = $this->model_report_tigo->getBysiteindetailthemovement($filter_data);

		foreach ($results as $result) {
			$data['stock'][] = array(
				'hwdespacho'			=> $result['HWDESPACHO'],
				'hwfdespacho'     		=> $result['HWFDESPACHO'],
				'hwmrno'    			=> $result['HWMRNO'],
				'sitnom' 				=> $result['SITNOM'],
				'hwpacking'    			=> $result['HWPACKING'],
				'hwcontract'    		=> $result['HWCONTRACT'],
				'hwcaja'    			=> $result['HWCAJA'],
				'hwartcod'    			=> $result['HWARTCOD'],
				'hwartdesc'				=> $result['HWARTDESC'],
				'hwserie'     			=> $result['HWSERIE'],
				'hwseriepredefinida'	=> $result['HWSERIEPREDEFINIDA'],
				'hwserieactivofijo'   	=> $result['HWSERIEACTIVOFIJO'],
				'hwcantdesp' 			=> $result['HWCANTDESP'],
				'hwunimed'    			=> $result['HWUNIMED']
			);
		}

		$data['heading_title']        = $this->language->get('heading_title');

		$data['text_list']            = $this->language->get('text_list');
		$data['text_no_results']      = $this->language->get('text_no_results');
		$data['text_confirm']         = $this->language->get('text_confirm');
		$data['text_all_status']      = $this->language->get('text_all_status');

		$data['column_hwdespacho']          = $this->language->get('column_hwdespacho');
		$data['column_hwfdespacho']     	= $this->language->get('column_hwfdespacho');		
		$data['column_hwfechasol']    		= $this->language->get('column_hwfechasol');
		$data['column_sitnom']   	  		= $this->language->get('column_sitnom');
		$data['column_hwpacking'] 			= $this->language->get('column_hwpacking');
		$data['column_hwcontract'] 			= $this->language->get('column_hwcontract');
		$data['column_hwcaja'] 				= $this->language->get('column_hwcaja');
		$data['column_hwmrno'] 				= $this->language->get('column_hwmrno');
		$data['column_hwartcod']      		= $this->language->get('column_hwartcod');
		$data['column_hwartdesc']        	= $this->language->get('column_hwartdesc');
		$data['column_hwserie']      		= $this->language->get('column_hwserie');
		$data['column_hwseriepredefinida']	= $this->language->get('column_hwseriepredefinida');
		$data['column_hwserieactivofijo']   = $this->language->get('column_hwserieactivofijo');
		$data['column_hwcantdesp']      	= $this->language->get('column_hwcantdesp');
		$data['column_hwunimed']    		= $this->language->get('column_hwunimed');
		$data['column_mrhw_estado']    		= $this->language->get('column_mrhw_estado');
		$data['column_tigosubcta_descrip']  = $this->language->get('column_tigosubcta_descrip');
		$data['column_hwentrego']       	= $this->language->get('column_hwentrego');
		$data['column_hwrecibio']      		= $this->language->get('column_hwrecibio');
		
		$data['entry_date_start']     = $this->language->get('entry_date_start');
		$data['entry_date_end']       = $this->language->get('entry_date_end');
		$data['entry_sitid']          = $this->language->get('entry_sitid');
		$data['entry_hwpacking']      = $this->language->get('entry_hwpacking');
		$data['entry_hwartcod']       = $this->language->get('entry_hwartcod');

		$data['button_filter']        = $this->language->get('button_filter');
		$data['button_excel']         = $this->language->get('button_excel');
		$data['button_pdf']           = $this->language->get('button_pdf');
		
		$data['export'] = $this->url->link('report/bysiteindetailthemovement/download', 'token=' . $this->session->data['token'], true);
		
		$data['excel'] = $this->url->link('report/bysiteindetailthemovement/excel', 'token=' . $this->session->data['token'], 'SSL');
		

		$data['token'] = $this->session->data['token'];
		
		$pdf = false;
		$pdf = (isset($this->request->get['pdf'])) ? true : false;
		
  
		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_hwpacking'])) {
			$url .= '&filter_hwpacking=' . $this->request->get['filter_hwpacking'];
		}
		
		if (isset($this->request->get['filter_hwartcod'])) {
			$url .= '&filter_hwartcod=' . $this->request->get['filter_hwartcod'];

		}

		if (isset($this->request->get['filter_sitio'])) {
			$url .= '&filter_sitio=' . $this->request->get['filter_sitio'];
		}			
		
		$results = $this->model_report_tigo->getSitios();
		
		$data['sitios'] = $results;
		$data['text_all_sitios'] = $this->language->get('text_all_sitios');

		$pagination = new Pagination();
		$pagination->total = $stock_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/bysiteindetailthemovement', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($stock_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($stock_total - $this->config->get('config_limit_admin'))) ? $stock_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $stock_total, ceil($stock_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end']   = $filter_date_end;
		$data['filter_hwpacking']  = $filter_hwpacking;		
		$data['filter_hwartcod']   = $filter_hwartcod;
		$data['filter_sitio']      = $filter_sitio;
		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        /*
		if ($pdf) {
          $this->response->setOutput(pdf($this->load->view('report/bysiteindetailthemovement.tpl', $data),$data));
		} else {
		*/	
		  $this->response->setOutput($this->load->view('report/bysiteindetailthemovement.tpl', $data));
		/*
		}
		*/
	}
}