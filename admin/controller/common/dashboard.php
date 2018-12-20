<?php
class ControllerCommonDashboard extends Controller {
	public function index() {
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_ingresado']  = $this->language->get('text_ingresado');
		$data['text_finalizado'] = $this->language->get('text_finalizado');
		$data['text_enproceso']  = $this->language->get('text_enproceso');
		$data['text_porvencer']  = $this->language->get('text_porvencer');
		$data['text_demorado']   = $this->language->get('text_demorado');
		$data['text_revisar']    = $this->language->get('text_revisar');

		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['token'] = $this->session->data['token'];

		$data['header']      = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
	
		$data['ingresado']   = $this->load->controller('dashboard/ingresado');
		$data['finalizado']  = $this->load->controller('dashboard/finalizado');
		$data['enproceso']   = $this->load->controller('dashboard/enproceso');
		$data['porvencer']   = $this->load->controller('dashboard/porvencer');
		$data['demorado']    = $this->load->controller('dashboard/demorado');
        $data['revisar']     = $this->load->controller('dashboard/revisar');
		//$data['chart']     = $this->load->controller('dashboard/chart');
		$data['recent']      = $this->load->controller('dashboard/recent');
        $data['footer'] = $this->load->controller('common/footer');

		$this->load->model('solicitud/solicitud');

		$filter_data = array (
			'user_id' => $this->session->data['ausrid']
		);
		
		if ($this->session->data['ausrid']!='ADMIN') {
		$accountInfo = $this->model_solicitud_solicitud->getCuenta($filter_data);
		}
        $data['cuenta'] = $accountInfo['cuenta']; 
		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}