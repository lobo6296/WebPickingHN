<?php
error_reporting(E_STRICT);
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
		
		$data['text_dashboard']     = $this->language->get('text_dashboard');
		//Catalog
		$data['text_catalog']       = $this->language->get('text_catalog');
		$data['text_offering']      = $this->language->get('text_offering');
		//Proyecto
		$data['text_proyecto']      = $this->language->get('text_proyecto');
		$data['text_abcproyecto']   = $this->language->get('text_abcproyecto');		
		//Design
		$data['text_design']        = $this->language->get('text_design');		
		//Plan
		$data['text_plan']          = $this->language->get('text_plan');
		$data['text_addeditplan']   = $this->language->get('text_addeditplan');
		$data['text_asocplan']      = $this->language->get('text_asocplan');
		//Testing
		$data['text_testing']       = $this->language->get('text_testing');
		$data['text_desa']          = $this->language->get('text_desa');
		$data['text_prod']          = $this->language->get('text_prod');
		$data['text_comverse']      = $this->language->get('text_comverse');
		$data['text_cbs']           = $this->language->get('text_cbs');		
		//Monitor
		$data['text_monitoreo']     = $this->language->get('text_monitoreo');
		$data['text_monplanes']     = $this->language->get('text_monplanes');
        $data['text_monpromo']      = $this->language->get('text_monpromo');
        $data['text_monpresta']     = $this->language->get('text_monpresta');
		//WebServices
		$data['text_servicios']     = $this->language->get('text_servicios');
		$data['text_validateCond']  = $this->language->get('text_validateCond');
		$data['text_accreditation'] = $this->language->get('text_accreditation');
		$data['text_getAvailableProducts'] = $this->language->get('text_getAvailableProducts');
        //Sistema
		$data['text_system']        = $this->language->get('text_system');
		$data['text_setting']       = $this->language->get('text_setting');
		$data['text_users']         = $this->language->get('text_users');
		//Sistema/Usuarios
		$data['text_user']          = $this->language->get('text_user');
		$data['text_user_group']    = $this->language->get('text_user_group');
		$data['text_api']           = $this->language->get('text_api');
		//Localizacion
		$data['text_localisation']  = $this->language->get('text_localisation');
		$data['text_language']      = $this->language->get('text_language');	
		//Herramientas
		$data['text_tools']         = $this->language->get('text_tools');
        $data['text_carga']        = $this->language->get('text_carga');	
		$data['text_cargaoffers']  = $this->language->get('text_cargaoffers');
        $data['text_backup']        = $this->language->get('text_backup');		
		$data['text_error_log']     = $this->language->get('text_error_log');
		$data['text_prerequisitos']     = $this->language->get('text_prerequisitos');
	    //Reportes
		$data['text_reports']          = $this->language->get('text_reports');		
		//Reportes/AR Services
		$data['text_arservices']       = $this->language->get('text_arservices');
		$data['text_queryrechargelog'] = $this->language->get('text_queryrechargelog');
		//Reportes/BC Services
		$data['text_bcservices']        = $this->language->get('text_bcservices');
		$data['text_querycustomerinfo'] = $this->language->get('text_querycustomerinfo');
        //Reportes/BB Services
		$data['text_bbservices']        = $this->language->get('text_bbservices');
		$data['text_querycdr']          = $this->language->get('text_querycdr');
      

		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
		//Catalog
		$data['offering'] = $this->url->link('catalog/offering', 'token=' . $this->session->data['token'], true);
		//Proyectos
		$data['proyecto'] = $this->url->link('proyecto/proyecto', 'token=' . $this->session->data['token'], true);
		
        //Plan
		$data['plan'] = $this->url->link('testing/plan', 'token=' . $this->session->data['token'] . '&ambiente=1', true);
		$data['asocplan'] = $this->url->link('testing/asociarplan', 'token=' . $this->session->data['token'] . '&ambiente=1', true);		
		//Testing
		$data['desacomverse'] = $this->url->link('testing/testing', 'token=' . $this->session->data['token'] . '&ambiente=1', true);
		$data['desacbs'] = $this->url->link('testing/testing', 'token=' . $this->session->data['token'] . '&ambiente=2', true);
		$data['prodcomverse'] = $this->url->link('testing/testing', 'token=' . $this->session->data['token'] . '&ambiente=3', true);
		$data['prodcbs'] = $this->url->link('testing/testing', 'token=' . $this->session->data['token']. '&ambiente=4', true);		
		//Monitor
		$data['monitoreo_planes'] = $this->url->link('error/en_construccion', 'token=' . $this->session->data['token']. '&ambiente=4', true);
		$data['monitoreo_promociones'] = $this->url->link('error/en_construccion', 'token=' . $this->session->data['token']. '&ambiente=4', true);
		$data['monitoreo_prestamos'] = $this->url->link('error/en_construccion', 'token=' . $this->session->data['token']. '&ambiente=4', true);		
		//WebServices
		$data['ws_validatecondition']   = $this->url->link('ws/validatecondition', 'token=' . $this->session->data['token'], true);
		$data['ws_accreditation']   = $this->url->link('error/en_construccion', 'token=' . $this->session->data['token'], true);
		//Sistema
		$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
		//Sistema/Usuarios
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], true);
		$data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true);
		$data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], true);
		//Idioma
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true);
		//Tools
		$data['carga'] = $this->url->link('tool/carga', 'token=' . $this->session->data['token'], true);
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true);	
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true);
		$data['prerequisitos'] = $this->url->link('tool/prerequisitos', 'token=' . $this->session->data['token'], true);
	    //Reportes
		//Reportes/AR Services
		$data['report_queryrechargelog'] = $this->url->link('report/queryrechargelog', 'token=' . $this->session->data['token'], true);
		//Reportes/BC Services
		$data['report_querycustomerinfo'] = $this->url->link('report/querycustomerinfo', 'token=' . $this->session->data['token'], true);
        //Reportes/BB Services
		$data['report_querycdr'] = $this->url->link('report/querycdr', 'token=' . $this->session->data['token'], true);

		return $this->load->view('common/menu', $data);
	}
}
