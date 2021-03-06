<?php
class ControllerReportReturns extends Controller {
	
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
		
		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
            'filter_date_end'	=> $filter_date_end,
			'filter_sitio'  	=> $filter_sitio,
			'filter_hwpacking'  => $filter_hwpacking,
			'filter_tipinv'     => $this->session->data['tipinv'],
			'tipo'              => $this->request->post['tipo'],
			'titulo'            => 'Returns',
			'reporte'           => 'returns'
		);
		
		  $this->model_tool_export->download($filter_data);	
		 
		  $url = '';
                                                     
		 $this->response->redirect($this->url->link('report/returns', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->index();		
	}
	
	public function pdf() {
 
     $this->load->model('tool/pdf');
	 
	 $filter_data = array (
	   'reporte' => 'returns'
	 );
     $this->model_tool_pdf->generarReporte($filter_data);
 
	// $this->index();	
	}
	
	public function index() {
		$this->load->language('report/returns');

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
		
		$data['pdf'] = $this->url->link('report/returns/pdf', 'token=' . $this->session->data['token'].$url, 'SSL');
		
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
			'href' => $this->url->link('report/returns', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/tigo');

		$data['orders'] = array();

		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'filter_sitio'  	=> $filter_sitio,
			'filter_hwpacking'  => $filter_hwpacking,
			'filter_tipinv'     => $this->session->data['tipinv'],
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$stock_total = $this->model_report_tigo->getTotalReturns($filter_data);

		$results = $this->model_report_tigo->getReturns($filter_data);

		foreach ($results as $result) {
			$data['stock'][] = array(
				'hwpacking'		=> $result['HWPACKING'],
				'hwfechaing'     => $result['HWFECHAING'],
				'sitnom'    	=> $result['SITNOM'],
				'hwtecnico'   	=> $result['HWTECNICO']
			);
		}

		$data['heading_title']        = $this->language->get('heading_title');

		$data['text_list']            = $this->language->get('text_list');
		$data['text_no_results']      = $this->language->get('text_no_results');
		$data['text_confirm']         = $this->language->get('text_confirm');
		$data['text_all_status']      = $this->language->get('text_all_status');

		$data['column_hwpacking']	= $this->language->get('column_hwpacking');
		$data['column_hwfechaing']	= $this->language->get('column_hwfechaing');
		$data['column_sitnom']      = $this->language->get('column_sitnom');
		$data['column_hwtecnico']	= $this->language->get('column_hwtecnico');
		
		$data['entry_date_start']     = $this->language->get('entry_date_start');
		$data['entry_date_end']       = $this->language->get('entry_date_end');
		$data['entry_sitid']          = $this->language->get('entry_sitid');
		$data['entry_hwpacking']      = $this->language->get('entry_hwpacking');
		$data['entry_hwartcod']       = $this->language->get('entry_hwartcod');

		$data['button_filter']        = $this->language->get('button_filter');
		$data['button_excel']         = $this->language->get('button_excel');
		$data['button_pdf']           = $this->language->get('button_pdf');
		
		$data['export'] = $this->url->link('report/returns/download', 'token=' . $this->session->data['token'], true);
		
		$data['excel'] = $this->url->link('report/returns/excel', 'token=' . $this->session->data['token'], 'SSL');
		

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
		$pagination->url = $this->url->link('report/returns', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

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
          $this->response->setOutput(pdf($this->load->view('report/returns.tpl', $data),$data));
		} else {
		*/	
		  $this->response->setOutput($this->load->view('report/returns.tpl', $data));
		/*
		}
		*/
	}
}