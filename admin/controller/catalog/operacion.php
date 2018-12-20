<?php
class ControllerCatalogOperacion extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/operacion');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/operacion');
		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/operacion');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/operacion');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/) {
			
			$this->model_catalog_Operacion->addOperacion($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_Operacion_id'])) {
				$url .= '&filter_Operacion_id=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_id'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_Operacion_name'])) {
				$url .= '&filter_Operacion_name=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_subofftype'])) {
				$url .= '&filter_subofftype=' . $this->request->get['filter_subofftype'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/operacion');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/operacion');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') /*&& $this->validateForm()*/) {
			$this->model_catalog_Operacion->editOperacion($this->request->get['Operacion_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_Operacion_name'])) {
				$url .= '&filter_Operacion_name=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_price'])) {
				$url .= '&filter_price=' . $this->request->get['filter_price'];
			}

			if (isset($this->request->get['filter_quantity'])) {
				$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/operacion');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/operacion');

		if (isset($this->request->post['selected']) /*&& $this->validateDelete()*/) {
			foreach ($this->request->post['selected'] as $Operacion_id) {
				$this->model_catalog_Operacion->deleteOperacion($Operacion_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_Operacion_id'])) {
				$url .= '&filter_Operacion_id=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_id'], ENT_QUOTES, 'UTF-8'));
			}			
			
			if (isset($this->request->get['filter_Operacion_name'])) {
				$url .= '&filter_Operacion_name=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_subofftype'])) {
				$url .= '&filter_subofftype=' . urlencode(html_entity_decode($this->request->get['filter_subofftype'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {

	if (isset($this->request->get['filter_cod_operacion'])) {
			$filter_cod_operacion = $this->request->get['filter_cod_operacion'];
		} else {
			$filter_cod_operacion = null;
	}
		
	if (isset($this->request->get['filter_descripcion_operacion'])) {
			$filter_descripcion_operacion = $this->request->get['filter_descripcion_operacion'];
		} else {
			$filter_descripcion_operacion = null;
		}

	if (isset($this->request->get['filter_metodo'])) {
			$filter_metodo = $this->request->get['filter_metodo'];
		} else {
			$filter_metodo = null;
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_cod_operacion'])) {
			$url .= '&filter_cod_operacion=' . urlencode(html_entity_decode($this->request->get['filter_cod_operacion'], ENT_QUOTES, 'UTF-8'));
		}		
		
		if (isset($this->request->get['filter_descripcion_operacion'])) {
			$url .= '&filter_descripcion_operacion=' . urlencode(html_entity_decode($this->request->get['filter_descripcion_operacion'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_metodo'])) {
			$url .= '&filter_metodo=' . urlencode(html_entity_decode($this->request->get['filter_metodo'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add']    = $this->url->link('catalog/operacion/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['copy']   = $this->url->link('catalog/operacion/copy', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('catalog/operacion/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
		    'filter_cod_operacion'         => $filter_cod_operacion,
			'filter_descripcion_operacion' => $filter_descripcion_operacion,
			'filter_metodo'	               => $filter_metodo,
			'filter_cod_ambiente'          => 1,
			'sort'                         => $sort,
			'order'                        => $order,
			'start'                        => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                        => $this->config->get('config_limit_admin')
		);
 
		$operacion_total = $this->model_catalog_operacion->getTotalOperaciones($filter_data);
	
		$results = $this->model_catalog_operacion->getOperaciones($filter_data);

		foreach ($results as $result) {

			$data['operaciones'][] = array(
				'cod_operacion'         => $result['COD_OPERACION'],
				'descripcion_operacion' => $result['DESCRIPCION_OPERACION'],
				'namespace'             => $result['NAMESPACE'],
				'metodo'                => $result['METODO'], 
				'tipo_metodo'           => $result['TIPO_METODO'],
				'creador'               => $result['CREADOR'],
				'activo'                => $result['ACTIVO'],
				'sort_order'            => $result['SORT_ORDER'],
				'edit'           => $this->url->link('catalog/operacion/edit', 'token=' 
				                                   . $this->session->data['token'] 
												   . '&cod_operacion=' . $result['COD_OPERACION'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		/* Operacion List - Filter */
		$data['entry_cod_operacion']         = $this->language->get('entry_cod_operacion');
		$data['entry_descripcion_operacion'] = $this->language->get('entry_descripcion_operacion');
		$data['entry_namespace']             = $this->language->get('entry_namespace');
		$data['entry_metodo']                = $this->language->get('entry_metodo');
		
		/* Operacion List - Columns */
		$data['column_cod_operacion']         = $this->language->get('column_cod_operacion');
		$data['column_descripcion_operacion'] = $this->language->get('column_descripcion_operacion');
		$data['column_namespace']             = $this->language->get('column_namespace');
		$data['column_metodo']                = $this->language->get('column_metodo');
		$data['column_sort_order']            = $this->language->get('column_sort_order');
		$data['column_activo']                = $this->language->get('column_activo');
		$data['column_action']                = $this->language->get('column_action');		
		
		$data['button_copy']   = $this->language->get('button_copy');
		$data['button_add']    = $this->language->get('button_add');
		$data['button_edit']   = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_cod_operacion'])) {
			$url .= '&filter_cod_operacion=' . urlencode(html_entity_decode($this->request->get['filter_cod_operacion'], ENT_QUOTES, 'UTF-8'));
		}
		
		if (isset($this->request->get['filter_descripcion_operacion'])) {
			$url .= '&filter_descripcion_operacion=' . urlencode(html_entity_decode($this->request->get['filter_descripcion_operacion'], ENT_QUOTES, 'UTF-8'));
		}		
		
		if (isset($this->request->get['filter_metodo'])) {
			$url .= '&filter_metodo=' . urlencode(html_entity_decode($this->request->get['filter_metodo'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
        $data['sort_id'] = $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . '&sort=cod_operacion' . $url, true);
		$data['sort_name'] = $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . '&sort=descripcion_operacion' . $url, true);
		$data['sort_order'] = $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_cod_operacion'])) {
			$url .= '&filter_cod_operacion=' . urlencode(html_entity_decode($this->request->get['filter_cod_operacion'], ENT_QUOTES, 'UTF-8'));
		}		
		
		if (isset($this->request->get['filter_descripcion_operacion'])) {
			$url .= '&filter_descripcion_operacion=' . urlencode(html_entity_decode($this->request->get['filter_descripcion_operacion'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_metodo'])) {
			$url .= '&filter_metodo=' . urlencode(html_entity_decode($this->request->get['filter_metodo'], ENT_QUOTES, 'UTF-8'));
		}		
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $Operacion_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($Operacion_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($Operacion_total - $this->config->get('config_limit_admin'))) ? $Operacion_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $Operacion_total, ceil($Operacion_total / $this->config->get('config_limit_admin')));

		$data['filter_cod_operacion'] = $filter_cod_operacion;
		$data['filter_descripcion_operacion'] = $filter_descripcion_operacion;
		$data['filter_metodo'] = $filter_metodo;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/operacion_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['Operacion_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
/*
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_plus'] = $this->language->get('text_plus');
		$data['text_minus'] = $this->language->get('text_minus');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_option_value'] = $this->language->get('text_option_value');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_percent'] = $this->language->get('text_percent');
		$data['text_amount'] = $this->language->get('text_amount');

		/* General Tab */ 
		$data['entry_Operacion_id']         = $this->language->get('entry_Operacion_id');
		$data['entry_Operacion_name']       = $this->language->get('entry_Operacion_name');
		$data['entry_Operacion_id']         = $this->language->get('entry_Operacion_id');
		$data['entry_Operacion_name']       = $this->language->get('entry_Operacion_name');
		$data['entry_subofftype']          = $this->language->get('entry_subofftype');
		$data['entry_Operacion_code']       = $this->language->get('entry_Operacion_code');
		$data['entry_Operacion_short_name'] = $this->language->get('entry_Operacion_short_name');
		$data['entry_payment_mode']        = $this->language->get('entry_payment_mode');
		$data['entry_catalog']             = $this->language->get('entry_catalog');
		$data['entry_subofftype']          = $this->language->get('entry_subofftype');
		$data['entry_rent_charge']         = $this->language->get('entry_rent_charge');
		$data['entry_sort_order']          = $this->language->get('entry_sort_order');
		$data['entry_action']              = $this->language->get('entry_action');
		
		$data['entry_account_name']        = $this->language->get('entry_account_name');
        $data['entry_balancetype']         = $this->language->get('entry_balancetype');
        $data['entry_account_id']          = $this->language->get('entry_account_id');
        $data['entry_comverse_name']       = $this->language->get('entry_comverse_name');
		
		$data['entry_corr_id']             = $this->language->get('entry_corr_id');
        $data['entry_free_unit']           = $this->language->get('entry_free_unit');
        $data['entry_free_unit_amount']    = $this->language->get('entry_free_unit_amount');
        $data['entry_expiring_date']       = $this->language->get('entry_expiring_date');
		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_Operacion_name'])) {
			$url .= '&filter_Operacion_name=' . urlencode(html_entity_decode($this->request->get['filter_Operacion_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['Operacion_id'])) {
			$data['action'] = $this->url->link('catalog/operacion/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('catalog/operacion/edit', 'token=' . $this->session->data['token'] . '&Operacion_id=' . $this->request->get['Operacion_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('catalog/operacion', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['Operacion_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$Operacion_info = $this->model_catalog_Operacion->getOperacion($this->request->get['Operacion_id']);
		}

		$data['token'] = $this->session->data['token'];
        
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['Operacion'])) {
			$data['Operacion'] = $this->request->post['Operacion'];
		} elseif (isset($this->request->get['Operacion_id'])) {
			$data['Operacion'] = $this->model_catalog_Operacion->getOperacionDescriptions($this->request->get['Operacion_id']);
		} else {
			$data['Operacion'] = array();
		}
	
		// Accounts
		if (isset($this->request->post['accounts'])) {
			$accounts = $this->request->post['accounts'];
		} elseif (isset($this->request->get['Operacion_id'])) {
			$accounts = $this->model_catalog_Operacion->getAccounts($this->request->get['Operacion_id']);
		} else {
			$accounts = array();
		}
		
		$data['accounts'] = array();

		foreach ($accounts as $account) {
				$data['accounts'][] = array(
					'account_id'      => $account['account_id'],
					'account_name'    => $account['account_name'],
					'balancetype'     => $account['balancetype'],
					'balancetypename' => $account['balancetypename'],
					'Operacion_id'     => $account['Operacion_id'],
                    'account_name'    => $account['account_name'],
                    'balancetype'     => $account['balancetype'],
                    'balancetypename' => $account['balancetypename'],
                    'account_id'      => $account['account_id'],
                    'comverse_name'   => $account['comverse_name'],
                    'sort_order'      => $account['sort_order']
				);
		}
		
		// Supplementary Det
		if (isset($this->request->post['supplementaryDet'])) {
			$supplementaryDet = $this->request->post['supplementaryDet'];
		} elseif (isset($this->request->get['Operacion_id'])) {
			$supplementaryDet = $this->model_catalog_Operacion->getOfferDet($this->request->get['Operacion_id']);
		} else {
			$supplementaryDet = array();
		}
		
		$data['supplementaryDet'] = array(); 

		foreach ($supplementaryDet as $det) {
				$data['supplementaryDet'][] = array(
					'correlativo'  => $det['correlativo'],
					'account_name' => $det['account_name'],
					'amount'       => $det['amount'],
					'unit'         => $det['unit'],
					'validity'     => $det['validity'],
					'recurrency'   => $det['recurrency']
				);
		}		
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/operacion_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/operacion')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['product_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}

			if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
				$this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');

			$url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

			if ($url_alias_info && isset($this->request->get['Operacion_id']) && $url_alias_info['query'] != 'Operacion_id=' . $this->request->get['Operacion_id']) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}

			if ($url_alias_info && !isset($this->request->get['Operacion_id'])) {
				$this->error['keyword'] = sprintf($this->language->get('error_keyword'));
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/operacion')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/operacion')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_Operacion_id']) || 
		    isset($this->request->get['filter_Operacion_name'])) {
				
			$this->load->model('catalog/operacion');

			if (isset($this->request->get['filter_Operacion_id'])) {
				$filter_Operacion_id = $this->request->get['filter_Operacion_id'];
			} else {
				$filter_Operacion_id = '';
			}			
			
			if (isset($this->request->get['filter_Operacion_name'])) {
				$filter_Operacion_name = $this->request->get['filter_Operacion_name'];
			} else {
				$filter_Operacion_name = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'filter_Operacion_name' => $filter_Operacion_name,
				'filter_Operacion_id' => $filter_Operacion_id,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_Operacion->getOperacions($filter_data);

			foreach ($results as $result) {
				//$option_data = array();        
				//$product_options = $this->model_catalog_Operacion->getProductOptions($result['Operacion_id']);
                /*  
				foreach ($product_options as $product_option) {
					$option_info = $this->model_catalog_option->getOption($product_option['option_id']);

					if ($option_info) {
						$product_option_value_data = array();

						foreach ($product_option['product_option_value'] as $product_option_value) {
							$option_value_info = $this->model_catalog_option->getOptionValue($product_option_value['option_value_id']);

							if ($option_value_info) {
								$product_option_value_data[] = array(
									'product_option_value_id' => $product_option_value['product_option_value_id'],
									'option_value_id'         => $product_option_value['option_value_id'],
									'name'                    => $option_value_info['name'],
									'price'                   => (float)$product_option_value['price'] ? $this->currency->format($product_option_value['price'], $this->config->get('config_currency')) : false,
									'price_prefix'            => $product_option_value['price_prefix']
								);
							}
						}

						$option_data[] = array(
							'product_option_id'    => $product_option['product_option_id'],
							'product_option_value' => $product_option_value_data,
							'option_id'            => $product_option['option_id'],
							'name'                 => $option_info['name'],
							'type'                 => $option_info['type'],
							'value'                => $product_option['value'],
							'required'             => $product_option['required']
						);
					}
				}
                */
				$json[] = array(
					'Operacion_id'    => $result['Operacion_id'],
					'Operacion_name'  => strip_tags(html_entity_decode($result['Operacion_name'], ENT_QUOTES, 'UTF-8')),
					//'option'     => $option_data,
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
