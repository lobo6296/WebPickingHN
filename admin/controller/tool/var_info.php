<?php
//==============================================================================
//	SYSTEM CONSTANTS & GLOBALS
//
//	Author: Max @ Toronto Emporium
//	E-mail: admin@torontoemporium.com
//
//	This has been tested using OC Version 1.5.6 - Default theme
//	NO WARRANTY is implied or expressed. Use at your own risk.
//
//	I am releasing this as FREEWARE covered under the GNU licensing model.
//	Use it or tailor it to your needs, but you MUST keep our credits!
//	Thats all we ask.  No donations required or requested.  LOL
//==============================================================================
class ControllerToolVarInfo extends Controller {
   public function index() {

	  $this->data = $this->load->language('tool/var_info');
	  $this->document->setTitle($this->language->get('heading_title'));
      
		$this->load->model('tool/update_currency');

		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['button_generate'] = $this->language->get('button_generate');
		
		$this->data['text_common'] = $this->language->get('text_common');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
        
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        if (isset($this->session->data['output'])) {
            $this->data['output'] = $this->session->data['output'];
        
            unset($this->session->data['output']);
        } else {
            $this->data['output'] = '';
        }

      $this->document->setTitle($this->data['heading_title']);
      // $this->document->addStyle('view/stylesheet/systeminfo.css');
      
      $this->data['breadcrumbs'] = array();
      $this->data['breadcrumbs'][] = array(
         'text'      => $this->language->get('text_home'),
         'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
         'separator' => false
      );
      $this->data['breadcrumbs'][] = array(
         'text'      => $this->language->get('heading_title'),
         'href'      => $this->url->link('tool/system_info', 'token=' . $this->session->data['token'], 'SSL'),
         'separator' => ' <span class="separator">&#187;</span> '
      );

      
      $this->template = 'tool/var_info.tpl';
      $this->children = array(
         'common/header',   
         'common/footer'   
      );
      
      $this->response->setOutput($this->render());
   }
}
?>