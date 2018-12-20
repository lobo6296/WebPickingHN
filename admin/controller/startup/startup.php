<?php
class ControllerStartupStartup extends Controller {
	public function index() {
		// Settings
		$query = $this->mysql->query("SELECT * FROM setting");
		
		foreach ($query->rows as $setting) {
				
			if (!$setting['serialized']) {				
				$this->config->set($setting['key'], $setting['value']);
			} else {
				$this->config->set($setting['key'], json_decode($setting['value'], true));
			}
		}

		// Language
		$query = $this->mysql->query("SELECT * FROM `language` WHERE code = '" . $this->mysql->escape($this->config->get('config_admin_language')) . "'");
		
		if ($query->num_rows) {
			$this->config->set('config_language_id', $query->row['language_id']);
		}
		
		// Language
		$language = new Language($this->config->get('config_admin_language'));
		$language->load($this->config->get('config_admin_language'));
		$this->registry->set('language', $language);
		
		/*		
		// Encryption
		$this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));	
        */		
	}
}