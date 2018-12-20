<?php
class ControllerCommonStats extends Controller {
	public function index() {
		$entro=0; 

		if ($entro==1) {
		  return $this->load->view('common/stats', $data);	
		}
		else {
			  return $this->load->view('common/null_stats', $data);
		}
	}
	public function update() {
		$this->load->view('common/null_stats', $data);
	}
}