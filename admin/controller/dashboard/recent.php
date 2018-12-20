<?php
class ControllerDashboardRecent extends Controller {
	public function index() {
		$this->load->language('dashboard/recent');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['orders'] = array();

		$filter_data = array(
			'sort'  => 'HWMR',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);
        $this->load->model('solicitud/solicitud'); 
		$results = $this->model_solicitud_solicitud->obtenerSolicitudes($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = array(
				'order_id'   => $result['HWMR'],
				'customer'   => $result['HWMRNO'],
				'status'     => $result['STATUS'],
				'date_added' => $result['HWFECHAENTREGA'],
				'total'      => $result['HWENTREGADO'],
				'view'       => $this->url->link('tracking/solicitud/edit', 'token=' . $this->session->data['token'] . '&hwmr=' . $result['HWMR'], 'SSL'),
			);
		}

		return $this->load->view('dashboard/recent.tpl', $data); 
	}
}