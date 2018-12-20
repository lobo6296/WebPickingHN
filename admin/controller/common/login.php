<?php
class ControllerCommonLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('common/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true));
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->session->data['token'] = token(32);
			
			if (isset($this->request->post['redirect']) && 
			  (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || 
			   strpos($this->request->post['redirect'], HTTPS_SERVER) === 0)) {
				$this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
			} else {
				$this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_login'] = $this->language->get('text_login');
		$data['text_forgotten'] = $this->language->get('text_forgotten');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');

		$data['button_login'] = $this->language->get('button_login');

		if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
			$this->error['warning'] = $this->language->get('error_token');
		}

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

		$data['action'] = $this->url->link('common/login', '', true);

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];

			unset($this->request->get['route']);
			unset($this->request->get['token']);

			$url = '';

			if ($this->request->get) {
				$url .= http_build_query($this->request->get);
			}

			$data['redirect'] = $this->url->link($route, $url, true);
		} else {
			$data['redirect'] = '';
		}

		if ($this->config->get('config_password')) {
			$data['forgotten'] = $this->url->link('common/forgotten', '', true);
		} else {
			$data['forgotten'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
	
		$this->response->setOutput($this->load->view('common/login', $data));
	}

	protected function validate() {	

	if (isset($this->request->post['username'])) {
	  $username = $this->request->post['username'];
	}	

	if (isset($this->request->post['password'])) {
	  $password = $this->request->post['password'];
	}		

	if (isset($this->request->post['estacion'])) {
	  $estacion = $this->request->post['estacion'];
	}	
			
	//echo "Credenciales: ".$username.' '.$password.' '.$estacion;
	
	if (!$this->user->login($username,$password,$estacion)) {
	  $this->error['warning'] = $this->language->get('error_login');		
	}
	
	return !$this->error;
	   
	/*
	$allowed = array('tigo.com.gt');
	$usuario = $this->request->post['username'];

	//Se verifica si en el usuario viene alguna @
	if (strpos($usuario,'@')>0) {
	  if (filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
        $explodedEmail = explode('@', $usuario);
        $domain = array_pop($explodedEmail);

        if (!in_array($domain, $allowed)) {
          $this->error['warning'] = "Invalid domain. Please login again.";
		  return !$this->error;
        }
      }		
	} 
	if (isset($explodedEmail)){
	  $usuario= array_pop($explodedEmail);
	}

	$ds=ldap_connect("COMCEL-SERVER.comcel.com.gt"); 
	$clave  =$this->request->post['password'];

    if ($ds) { 
                  $r=ldap_bind($ds); 
				  error_reporting(0);
	              if ($bind = ldap_bind($ds,$usuario."@tigo.com.gt",$clave)) {
					error_reporting(E_ALL);  
					$info=$this->user->logear((string)$usuario,html_entity_decode($clave, ENT_QUOTES, 'UTF-8')); 
					return true;
                  }
    }	
        $this->error['warning'] = $this->language->get('error_login');		
	    return !$this->error;*/
    }
}
