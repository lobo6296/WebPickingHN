<?php
class ControllerCommonColumnLeft extends Controller {
	
	public function index() {
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->load->language('common/column_left');
	
			$this->load->model('user/user');
	
			$this->load->model('tool/image');
	
			$user_info = $this->model_user_user->getUser($this->user->getId());

			if ($user_info) {
				$data['firstname'] = $user_info['firstname'];
				$data['lastname'] = $user_info['lastname'];
	
				$data['user_group'] = $user_info['user_group'];
	
				if (is_file(DIR_IMAGE . $user_info['image'])) {
					$data['image'] = $this->model_tool_image->resize($user_info['image'], 45, 45);
				} else {
					$data['image'] = '';
				}
			} else {
				$data['firstname'] = '';
				$data['lastname'] = '';
				$data['user_group'] = '';
				$data['image'] = '';
			}
			$this->load->model('design/menu');
			$menu = $this->model_design_menu->obtenerMenu();

			$data['profile'] = $this->load->controller('common/profile');
            $data['menus']=$menu;			
			$data['stats'] = $this->load->controller('common/stats');	

			return $this->load->view('common/column_left', $data);
		}
	}
}