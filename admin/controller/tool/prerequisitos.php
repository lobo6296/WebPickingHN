<?php
class ControllerToolPrerequisitos extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('tool/prerequisitos');
	
		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_step_2'] = $this->language->get('text_step_2');
		$data['text_install_php'] = $this->language->get('text_install_php');
		$data['text_install_extension'] = $this->language->get('text_install_extension');
		$data['text_install_file'] = $this->language->get('text_install_file');
		$data['text_install_directory'] = $this->language->get('text_install_directory');
		$data['text_setting'] = $this->language->get('text_setting');
		$data['text_current'] = $this->language->get('text_current');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_extension'] = $this->language->get('text_extension');
		$data['text_file'] = $this->language->get('text_file');
		$data['text_directory'] = $this->language->get('text_directory');
		$data['text_status'] = $this->language->get('text_status');
		$data['text_on'] = $this->language->get('text_on');
		$data['text_off'] = $this->language->get('text_off');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_writable'] = $this->language->get('text_writable');
		$data['text_unwritable'] = $this->language->get('text_unwritable');
		$data['text_version'] = $this->language->get('text_version');
		$data['text_global'] = $this->language->get('text_global');
		$data['text_magic'] = $this->language->get('text_magic');
		$data['text_file_upload'] = $this->language->get('text_file_upload');
		$data['text_session'] = $this->language->get('text_session');
		$data['text_db'] = $this->language->get('text_db');
		$data['text_gd'] = $this->language->get('text_gd');
		$data['text_curl'] = $this->language->get('text_curl');
		$data['text_mcrypt'] = $this->language->get('text_mcrypt');
		$data['text_zlib'] = $this->language->get('text_zlib');
		$data['text_zip'] = $this->language->get('text_zip');
		$data['text_mbstring'] = $this->language->get('text_mbstring');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('install/step_2');

		$data['php_version'] = phpversion();
		$data['register_globals'] = ini_get('register_globals');
		$data['magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['file_uploads'] = ini_get('file_uploads');
		$data['session_auto_start'] = ini_get('session_auto_start');

		$db = array(
			'mysql', 
			'mysqli', 
			'pgsql', 
			'pdo'
		);

		if (!array_filter($db, 'extension_loaded')) {
			$data['db'] = false;
		} else {
			$data['db'] = true;
		}

		$data['gd'] = extension_loaded('gd');
		$data['curl'] = extension_loaded('curl');
		$data['mcrypt_encrypt'] = function_exists('mcrypt_encrypt');
		$data['zlib'] = extension_loaded('zlib');
		$data['zip'] = extension_loaded('zip');
		
		$data['iconv'] = function_exists('iconv');
		$data['mbstring'] = extension_loaded('mbstring');

		$data['config_catalog'] = DIR_RESOURCES . 'config.php';
		$data['config_admin'] = DIR_RESOURCES . 'admin/config.php';
		
		$data['image'] = DIR_RESOURCES . 'image';
		$data['image_cache'] = DIR_RESOURCES . 'image/cache';
		$data['image_catalog'] = DIR_RESOURCES . 'image/catalog';
		$data['cache'] = DIR_SYSTEM . 'storage/cache';
		$data['logs'] = DIR_SYSTEM . 'storage/logs';
		$data['download'] = DIR_SYSTEM . 'storage/download';
		$data['upload'] = DIR_SYSTEM . 'storage/upload';
		$data['modification'] = DIR_SYSTEM . 'storage/modification';

		$data['back'] = $this->url->link('install/step_1');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');

		$this->response->setOutput($this->load->view('tool/prerequisitos', $data));
	}
}
