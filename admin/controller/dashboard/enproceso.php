<?php
class ControllerDashboardEnproceso extends Controller {
	public function index() {
		$this->load->language('dashboard/enproceso');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('report/dashboard');
        /*
		$today = $this->model_report_enproceso->getTotalenprocesos(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

		$yesterday = $this->model_report_enproceso->getTotalenprocesos(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

		$difference = $today - $yesterday;

		if ($difference && $today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}
        */
		$filter_data = array (
		   'filter_tipinv' => $this->session->data['tipinv'],
		   'estado'        => 'En Proceso'
		);
		
		$enproceso_total = $this->model_report_dashboard->getTotalByEstado($filter_data);

		if ($enproceso_total > 1000000000000) {
			$data['total'] = round($enproceso_total / 1000000000000, 1) . 'T';
		} elseif ($enproceso_total > 1000000000) {
			$data['total'] = round($enproceso_total / 1000000000, 1) . 'B';
		} elseif ($enproceso_total > 1000000) {
			$data['total'] = round($enproceso_total / 1000000, 1) . 'M';
		} elseif ($enproceso_total > 1000) {
			$data['total'] = round($enproceso_total / 1000, 1) . 'K';
		} else {
			$data['total'] = round($enproceso_total);
		}

		$data['enproceso'] = $this->url->link('report/solicitud', 'token=' . $this->session->data['token'].'&filter_status=3', 'SSL');

		return $this->load->view('dashboard/enproceso.tpl', $data);
	}
}
