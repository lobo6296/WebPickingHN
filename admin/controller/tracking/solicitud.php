<?php
class ControllerTrackingSolicitud extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('tracking/solicitud');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('solicitud/solicitud');

		$this->getList();
	}

	public function add() {
		
		$this->load->language('tracking/solicitud');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('solicitud/solicitud');

		$hwmr = $this->session->data['hwmr'];

		if ($hwmr == 0){
			$this->session->data['operacion']='add';
		} else {
			$this->session->data['operacion']='edit';
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			
			/*

			$this->model_solicitud_solicitud->addMdr($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url, true));
			*/
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('tracking/solicitud');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('solicitud/solicitud');

		if (($this->request->server['REQUEST_METHOD'] == 'GET') && $this->validateForm()) {
			
			$salida = array(
			   'metodo' => 'Edit',
			   'post'   => $this->request->post
			);

			$this->model_solicitud_solicitud->editMdr($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('tracking/solicitud');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tracking/solicitud');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $user_id) {
				$this->model_solicitud_solicitud->deleteUser($user_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function getList() {
		$this->load->language('tracking/solicitud');
        $this->load->model('solicitud/solicitud');
		
		if (isset($this->request->get['filter_mdr'])) {
			$filter_mdr = $this->request->get['filter_mdr'];
		} else {
			$filter_mdr = null;
		}		
		
		if (isset($this->request->get['filter_cormdr'])) {
			$filter_cormdr = $this->request->get['filter_cormdr'];
		} else {
			$filter_cormdr = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'hwmr';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href' => $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$data['add'] = $this->url->link('tracking/solicitud/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('tracking/solicitud/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['users'] = array();

		$filter_data = array(
			'filter_cormdr'	=> $filter_cormdr, 
			'filter_mdr'	=> $filter_mdr,
			'sort'  		=> $sort,
			'order' 		=> $order,
			'start' 		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 		=> $this->config->get('config_limit_admin')
		);

		$solicitudes_total  = $this->model_solicitud_solicitud->totalSolicitudes($filter_data);

		$results = $this->model_solicitud_solicitud->obtenerSolicitudes($filter_data);

		$util = New Util();

        foreach ($results as $solicitud) {
			
			if ($solicitud['MRHW_ESTADO']==0)
				$clase='fa fa-pencil';
			else {
				$clase='fa fa-eye';
			}
			if ($solicitud['STATUS']=='Ingresado') {
			  $bgcolor="#33b5e5";//celeste
            } elseif ($solicitud['STATUS']=='Finalilzado') {
			  $bgcolor="#bdbdbd"; //gris
			} elseif ($solicitud['STATUS']=='En Proceso') {
              $bgcolor="#00C851"; //verde	
			} elseif ($solicitud['STATUS']=='Por vencer') { 
        	  $bgcolor="#ffbb33"; //amarillo
			} elseif ($solicitud['STATUS']=='Demorado') {  
			  $bgcolor="#ff4444"; //rojo
			} else {
                    $bgcolor = "#f5f5f5";    
			}

			$data['solicitudes'][] = array(	
				'hwmr'   		  => $solicitud['HWMR'],
				'hwmrno' 		  => $solicitud['HWMRNO'],
				'hwfechasol'  	  => $solicitud['HWFECHASOL'],
                'hwfechaentrega'  => $solicitud['HWFECHAENTREGA'],
                'hwentregado'     => $solicitud['HWENTREGADO'],
				'mrhw_estado'     => $solicitud['MRHW_ESTADO'],
				'mrhw_estadodes'  => ($solicitud['MRHW_ESTADO']==0)?'INICIADA':'FINALIZADA',
				'estado'          => $solicitud['STATUS'],
				'color'		      => $bgcolor,
				'clase'           => $clase,
				'edit'            => $this->url->link('tracking/solicitud/edit', 'token=' . $this->session->data['token'] . '&hwmr=' . $solicitud['HWMR'] .'&mrhw_estado='.$solicitud['MRHW_ESTADO'] . $url, true)
			);
		}

		$data['heading_title'] 		= $this->language->get('heading_title');

		$data['entry_cormdr']		= $this->language->get('entry_cormdr');
		$data['entry_mdr']     		= $this->language->get('entry_mdr');
		$data['text_list'] 			= $this->language->get('text_list');
		$data['text_no_results']	= $this->language->get('text_no_results');
		$data['text_confirm'] 		= $this->language->get('text_confirm');

		$data['column_mdr']         = $this->language->get('column_mdr');
		$data['column_mdrno']       = $this->language->get('column_mdrno');
		$data['column_status']      = $this->language->get('column_status');
		$data['column_fsolicitud']  = $this->language->get('column_fsolicitud');
		$data['column_action']      = $this->language->get('column_action');

		$data['button_add']         = $this->language->get('button_add');
		$data['button_edit']        = $this->language->get('button_edit');
		$data['button_view']        = $this->language->get('button_view');
		$data['button_cancel']      = $this->language->get('button_cancel');
		$data['button_delete']      = $this->language->get('button_delete');
		$data['button_filter']      = $this->language->get('button_filter');

		$data['fecha_inicial']      = date("d-m-Y");
		
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_hwmr'] = $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . '&sort=hwmr' . $url, true);
		$data['sort_status'] = $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $solicitudes_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($solicitudes_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($solicitudes_total - $this->config->get('config_limit_admin'))) ? $solicitudes_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $solicitudes_total, ceil($solicitudes_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('tracking/solicitud_list', $data));
	}

	public function validarSolicitud() {
		if (isset($this->request->get['hwmr'])){
			$this->load->model('solicitud/solicitud');
		//	echo 'dos ' ." \n";
			if(isset($this->request->get['hwmr'])){
				//echo 'tres ' ." \n";
				$hwmr = $this->request->get['hwmr'];
			} else {
				$hwmr = '';
			}
			//echo $hwmr;
			$results = $this->model_solicitud_solicitud->FinalizaMdr($hwmr);
		}
	}

	protected function getForm() {

		$data['heading_title'] = $this->language->get('heading_title');
		


		if (isset($this->request->get['mrhw_estado'])) {
		  $mrhw_estado = $this->request->get['mrhw_estado'];
		}	
		else {
		      $mrhw_estado=0;
		}

		if (isset($this->request->get['hwmr'])) {
			$hwmr = $this->request->get['hwmr'];
			$this->session->data['hwmr'] = $hwmr; 	
		}	
		else {
			//$hwmr=0;
		}
		
		if ($mrhw_estado==1) {
		  $data['text_form'] = $this->language->get('text_view');	
		} else {
		  $data['text_form'] = !isset($this->request->get['hwmr']) ? $this->language->get('text_add') : 
		                       $this->language->get('text_edit');
		}
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
        
		$data['entry_corr_mdr'] = $this->language->get('entry_corr_mdr');
		$data['entry_autonombre'] = $this->language->get('entry_autonombre');
		$data['entry_vehiculo'] = $this->language->get('entry_vehiculo');
		$data['entry_placa'] = $this->language->get('entry_placa');
		$data['entry_emprecibe'] = $this->language->get('entry_emprecibe');
		$data['entry_perrecibe'] = $this->language->get('entry_perrecibe');
		$data['entry_rtn'] = $this->language->get('entry_rtn');
		$data['entry_rtn_emp'] = $this->language->get('entry_rtn_emp');
		$data['entry_hwmrno'] = $this->language->get('entry_hwmrno');
		$data['entry_sitio'] = $this->language->get('entry_sitio');
		$data['entry_hwfechaentrega'] = $this->language->get('entry_hwfechaentrega');
		$data['entry_hwviasol'] = $this->language->get('entry_hwviasol');
		$data['entry_hwtipsol'] = $this->language->get('entry_hwtipsol');
		$data['entry_serie'] = $this->language->get('entry_serie');
		$data['entry_subcuenta'] = $this->language->get('entry_subcuenta');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['error_solicita'])) {
			$data['error_solicita'] = $this->error['error_solicita'];
		} else {
			$data['error_solicita'] = '';
		}

		//echo "ENTRO 1 \n";
		//echo "mdr " . $hwmr . "\n";
		if (isset($this->session->data['operacion'])) {
			//echo "1 \n";
		  $operacion = $this->session->data['operacion'];
		 //echo $operacion . "\n";
		  if ($hwmr > 0) {
			$operacion = 'edit';
			$this->session->data['operacion'] = 'edit';
		  }
		  //echo $operacion . "\n";
		 
		}
        else {
		//	echo "2 \n";
			if (isset($this->request->get['hwmr'])) {
			//	echo "3 \n";
				$operacion = 'edit';
				$hwmr = $this->request->get['hwmr'];
				$this->session->data['operacion']='edit';
			} else {
			//	echo "4 \n";
				$operacion = 'new'; 
				$this->session->data['operacion']='new';
				$hwmr = null;
			}
			
        } 		
				
		if ($operacion=='edit') {
		//	echo "5 \n";
			$hwmr = $this->session->data['hwmr'];
		//	echo "hwmr nuevo = " . $hwmr . "\n";	
			$mdr_info = $this->model_solicitud_solicitud->getMdr($hwmr);
			$solicitud_detalles = $this->model_solicitud_solicitud->getDetMdr($hwmr);
			//echo "mdr \n";
			//echo $hwmr;
			//echo "\n";
			//print_r($mdr_info);
			//echo "\n";
			//print_r($solicitud_detalles);
			//echo "\n";

			$data['solicitud_detalles'] = $solicitud_detalles;
			//$this->session->data['hwmr']=null;
		} elseif ($operacion=='add'){
			//echo "6 \n";
			if (empty($this->session->data['hwmr'])) {
			//	echo "7 \n";
				$hwmr = $this->model_solicitud_solicitud->getCorrelativo();	
				$this->session->data['hwmr'] = $hwmr;
			} else {
			//	echo "8 \n";
					$hwmr=$this->session->data['hwmr']; 
			}				
		} 

		$data['HWMR'] = $hwmr;
		
		$url = '';

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
			'href' => $this->url->link('solicitud/solicitud', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['hwmr'])) {
			$data['action'] = $this->url->link('tracking/solicitud/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('tracking/solicitud/edit', 'token=' . $this->session->data['token'] . '&hwmr=' . $this->request->get['hwmr'] . $url, true);
		}

		$data['cancel'] = $this->url->link('tracking/solicitud', 'token=' . $this->session->data['token'] . $url, true);

		$data['tiposol'] = array (1 => 'Traslado',
							  	2 => 'Normal',
								3 => 'Emergencia');

		$data['filter_tipsol'] = 1;						

		/*$data['viaosol'] = array ('W' => 'Web',
								'C' => 'Correo Electrónico',
								'T' => 'Telefónicamente',
								'M' => 'MDR',
								'F' => 'FAX');*/
		
		$data['vehiculos'] = $this->model_solicitud_solicitud->getVehiculos();
		$data['empresas'] = $this->model_solicitud_solicitud->getEmpresa();

		$data['filter_vehiculos']='52';	
		$data['filter_empresa']='1';						

		$data['arr_estado'] = array('B' => 'Buen Estado',
								'M' => 'Mal Estado');
		
		$data['filter_estado']='B';	


		if (isset($this->request->post['TIPCODE'])) {
			$data['TIPCODE'] = $this->request->post['TIPCODE'];
		} elseif (!empty($mdr_info)) {
			$data['TIPCODE'] = $mdr_info['TIPCODE'];
		} else {
			$data['TIPCODE'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		if (isset($this->request->post['AUTCOD'])) {
			$data['AUTCOD'] = $this->request->post['AUTCOD'];
		} elseif (!empty($mdr_info)) {
			$data['AUTCOD'] = $mdr_info['AUTCOD'];
		} else {
			$data['AUTCOD'] = '';
		}

		if (isset($this->request->post['AUTNOMBRE'])) {
			$data['AUTNOMBRE'] = $this->request->post['AUTNOMBRE'];
		} elseif (!empty($mdr_info)) {
			$data['AUTNOMBRE'] = $mdr_info['AUTNOMBRE'];
		} else {
			$data['AUTNOMBRE'] = '';
		}

		if (isset($this->request->post['HWMRNO'])) {
			$data['HWMRNO'] = $this->request->post['HWMRNO'];
		} elseif (!empty($mdr_info)) {
			$data['HWMRNO'] = $mdr_info['HWMRNO'];
		} else {
			$data['HWMRNO'] = '';
		}

		if (isset($this->request->post['placa'])) {
			$data['PLACA'] = $this->request->post['placa'];
		} elseif (!empty($mdr_info)) {
			$data['PLACA'] = $mdr_info['VEHI_PLACA'];
		} else {
			$data['PLACA'] = '';
		}

		if (isset($this->request->post['perrecibe'])) {
			$data['perrecibe'] = $this->request->post['perrecibe'];
		} elseif (!empty($mdr_info)) {
			$data['PERRECIBE'] = $mdr_info['PERNOMBRE'];
		} else {
			$data['PERRECIBE'] = '';
		}

		if (isset($this->request->post['RTN'])) {
			$data['rtn'] = $this->request->post['PERRTN'];
		} elseif (!empty($mdr_info)) {
			$data['RTN'] = $mdr_info['PERRTN'];
		} else {
			$data['RTN'] = '';
		}

		if (isset($this->request->post['RTNEMP'])) {
			$data['rtnemp'] = $this->request->post['EMPRTN'];
		} elseif (!empty($mdr_info)) {
			$data['RTNEMP'] = $mdr_info['EMPRTN'];
		} else {
			$data['RTNEMP'] = '';
		}

		/*if (isset($this->request->post['RTNEMP'])) {
			$data['rtnemp'] = $this->request->post['RTNEMP'];
		} elseif (!empty($mdr_info)) {
			$data['RTNEMP'] = $mdr_info['RTNEMP'];
		} else {
			$data['RTNEMP'] = '';
		}*/

		if (isset($this->request->post['HWFECHAENTREGA'])) {
			$data['HWFECHAENTREGA'] = $this->request->post['HWFECHAENTREGA'];
		} elseif (!empty($mdr_info)) {
			$data['HWFECHAENTREGA'] = $mdr_info['HWFECHAENTREGA'];
		} else {
			$data['HWFECHAENTREGA'] = '';
		}

		if (isset($this->request->post['EMPID'])) {
			$data['filter_empresa'] = $this->request->post['EMPID'];
		} elseif (!empty($mdr_info)) {
			$data['filter_empresa'] = $mdr_info['EMPID'];;	
		} else {
			$data['EMPID'] = '';
		}

		if (isset($this->request->post['VEHI_CODIGO'])) {
			$data['filter_vehiculos'] = $this->request->post['VEHI_CODIGO'];
		} elseif (!empty($mdr_info)) {
			$data['filter_vehiculos'] = $mdr_info['VEHI_CODIGO'];;	
		} else {
			$data['VEHI_CODIGO'] = '';
		}

		if (isset($this->request->post['HWFDIGITASOL'])) {
			$data['HWFDIGITASOL'] = $this->request->post['HWFDIGITASOL'];
		} elseif (!empty($mdr_info)) {
			$data['HWFDIGITASOL'] = $mdr_info['HWFDIGITASOL'];
		} else {
			$data['HWFDIGITASOL'] = '';
		}

		if (isset($this->request->post['HWVIASOL'])) {
			$data['HWVIASOL'] = $this->request->post['HWVIASOL'];
		} elseif (!empty($mdr_info)) {
			$data['HWVIASOL'] = $mdr_info['HWVIASOL'];
		} else {
			$data['HWVIASOL'] = '';
		}

		if (isset($this->request->post['HWTIPOSOL'])) {
			$data['HWTIPOSOL'] = $this->request->post['HWTIPOSOL'];
		} elseif (!empty($mdr_info)) {
			$data['HWTIPOSOL'] = $mdr_info['HWTIPOSOL'];
		} else {
			$data['HWTIPOSOL'] = '';
		}

		if (isset($this->request->post['MRHW_ESTADO'])) {
			$data['MRHW_ESTADO'] = $this->request->post['MRHW_ESTADO'];
		} elseif (!empty($mdr_info)) {
			$data['MRHW_ESTADO'] = $mdr_info['MRHW_ESTADO'];
		} else {
			$data['MRHW_ESTADO'] = '';
		}
		
		$data['column_articulo']       = $this->language->get('column_articulo');
		$data['column_descripcion']    = $this->language->get('column_descripcion');
		$data['column_elige']          = $this->language->get('column_elige');
        $data['column_linea']          = $this->language->get('column_linea');
        $data['column_packing']        = $this->language->get('column_packing');
        $data['column_caja']           = $this->language->get('column_caja');
        $data['column_centregada']     = $this->language->get('column_centregada');
        $data['column_estado']         = $this->language->get('column_estado');
		$data['column_csolicitada']    = $this->language->get('column_csolicitada');
		$data['column_cdisp']    	   = $this->language->get('column_cdisp');
        $data['column_sitio']          = $this->language->get('column_sitio');
		$data['column_action']          = $this->language->get('column_action');

		$data['entry_hwartcod']        = $this->language->get('entry_hwartcod');
        $data['entry_hwartdesc']       = $this->language->get('entry_hwartdesc');
        $data['entry_hwlinea']         = $this->language->get('entry_hwlinea');
        $data['entry_hwpacking']       = $this->language->get('entry_hwpacking');
        $data['entry_hwcaja']          = $this->language->get('entry_hwcaja');
		$data['entry_hwsolcant']       = $this->language->get('entry_hwsolcant');
		$data['entry_hwcantdis']       = $this->language->get('entry_hwcantdis');
        $data['entry_hwsolest']        = $this->language->get('entry_hwsolest');
        $data['entry_hwsolaent']       = $this->language->get('entry_hwsolaent');
        $data['entry_hwmrdetsitid']    = $this->language->get('entry_hwmrdetsitid');
		
		$data['text_loading']          = $this->language->get('text_loading');
		
		$data['token'] = $this->session->data['token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		
		$this->response->setOutput($this->load->view('tracking/solicitud_form', $data));
	}

	public function cargarInfo() {
		$json = array();
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function putDetalle(){
		$json = array();

		if (isset($this->request->get['hwmr'])){
			$this->load->model('solicitud/solicitud');

			if(isset($this->request->get['hwmr'])){
				$hwmr = $this->request->get['hwmr'];
			} else {
				$hwmr = '';
			}

			if(isset($this->request->get['linea'])){
				$linea = $this->request->get['linea'];
			} else {
				$linea = '';
			}

			if(isset($this->request->get['hwsolest'])){
				$hwsolest = $this->request->get['hwsolest'];
			} else {
				$hwsolest = '';
			}

			$filter_data = array(
				'hwmr'		=> strtoupper($hwmr),
				'lineadet'	=> strtoupper($linea),
				'hwsolest'	=> strtoupper($hwsolest),
			);

			$results = $this->model_solicitud_solicitud->putDetMdr($filter_data);
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($results));
	}

	public function postDetalle(){
		$json = array();

		if (isset($this->request->get['hwmr'])){
			$this->load->model('solicitud/solicitud');

			if(isset($this->request->get['hwmr'])){
				$hwmr = $this->request->get['hwmr'];
			} else {
				$hwmr = '';
			}

			if(isset($this->request->get['autonombre'])){
				$autonombre = $this->request->get['autonombre'];
			} else {
				$autonombre = '';
			}

			if(isset($this->request->get['hwviasol'])){
				$hwviasol = $this->request->get['hwviasol'];
			} else {
				$hwviasol = '';
			}

			if(isset($this->request->get['hwtipsol'])){
				$hwtipsol = $this->request->get['hwtipsol'];
			} else {
				$hwtipsol = '';
			}

			if(isset($this->request->get['hwfechaentrega'])){
				$hwfechaentrega = $this->request->get['hwfechaentrega'];
			} else {
				$hwfechaentrega = '';
			}

			if(isset($this->request->get['clinea'])){
				$clinea = $this->request->get['clinea'];
			} else {
				$clinea = '';
			}

			if(isset($this->request->get['hwartcod'])){
				$hwartcod = $this->request->get['hwartcod'];
			} else {
				$hwartcod = '';
			}
			if(isset($this->request->get['hwsolcant'])){
				$hwsolcant = $this->request->get['hwsolcant'];
			} else {
				$hwsolcant = '';
			}
			if(isset($this->request->get['hwlinea'])){
				$hwlinea = $this->request->get['hwlinea'];
			} else {
				$hwlinea = '';
			}
			if(isset($this->request->get['hwpacking'])){
				$hwpacking = $this->request->get['hwpacking'];
				$hwpacking = Trim($hwpacking);
			} else {
				$hwpacking = '';
			}
			if(isset($this->request->get['hwcaja'])){
				$hwcaja = $this->request->get['hwcaja'];
			} else {
				$hwcaja = '';
			}
			if(isset($this->request->get['hwsolest'])){
				$hwsolest = $this->request->get['hwsolest'];
			} else {
				$hwsolest = '';
			}
			if(isset($this->request->get['hwsolaent'])){
				$hwsolaent = $this->request->get['hwsolaent'];
			} else {
				$hwsolaent = '';
			}
			if(isset($this->request->get['sitnom'])){
				$sitnom = $this->request->get['sitnom'];
				$sitnom = Trim($sitnom);
			} else {
				$sitnom = '';
			}
			if(isset($this->request->get['placa'])){
				$placa = $this->request->get['placa'];
				$placa = Trim($placa);
			} else {
				$placa = '';
			}
			if(isset($this->request->get['rtn'])){
				$rtn = $this->request->get['rtn'];
				$rtn = Trim($rtn);
			} else {
				$rtn = '';
			}
			if(isset($this->request->get['rtnemp'])){
				$rtnemp = $this->request->get['rtnemp'];
				$rtnemp = Trim($rtnemp);
			} else {
				$rtnemp = '';
			}
			if(isset($this->request->get['perrecibe'])){
				$perrecibe = $this->request->get['perrecibe'];
				$perrecibe = Trim($perrecibe);
			} else {
				$perrecibe = '';
			}
			if(isset($this->request->get['empresa'])){
				$empresa = $this->request->get['empresa'];
				$empresa = Trim($empresa);
			} else {
				$empresa = '';
			}
			if(isset($this->request->get['vehiculo'])){
				$vehiculo = $this->request->get['vehiculo'];
				$vehiculo = Trim($vehiculo);
			} else {
				$vehiculo = '';
			}

			$filter_encabezado = array(
				'hwmr'				=> strtoupper($hwmr),
				'autonombre'		=> strtoupper($autonombre),
				'hwtipsol'			=> strtoupper($hwtipsol),
				'hwfechaentrega'	=> strtoupper($hwfechaentrega),
				'sitnom'			=> strtoupper($sitnom),
				'placa'				=> strtoupper($placa),
				'rtn'				=> strtoupper($rtn),
				'perrecibe'			=> strtoupper($perrecibe),
				'empresa'			=> strtoupper($empresa),
				'vehiculo'			=> strtoupper($vehiculo),
				'rtnemp'			=> strtoupper($rtnemp),
			);

			$result = $this->model_solicitud_solicitud->addMdr($filter_encabezado);

			$filter_data = array(
				'hwmr'		=> strtoupper($hwmr),
				'lineadet'	=> strtoupper($clinea),
				'hwartcod'	=> strtoupper($hwartcod),
				'hwsolcant'	=> strtoupper($hwsolcant),
				'hwlinea' 	=> strtoupper($hwlinea),
				'hwpacking'	=> strtoupper($hwpacking),
				'hwcaja'	=> strtoupper($hwcaja),
				'hwsolest'	=> strtoupper($hwsolest),
				'hwsolaent'	=> strtoupper($hwsolaent),
				'sitnom'	=> strtoupper($sitnom),
			);

			$results = $this->model_solicitud_solicitud->postDetMdr($filter_data);
			$this->session->data['operacion']='add';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($results));
	}

	public function validaMdr() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		  $this->load->model('solicitud/solicitud');
		  
		  $hwmrno = $this->request->post['hwmrno'];
		  $hwmr = $this->request->post['hwmr'];
		  
		  $mdr = $this->model_solicitud_solicitud->getValidaMdr(strtoupper($hwmrno),strtoupper($hwmr));
		
		  if ($mdr == 9999999999) {
			  echo "true";
		  } else {	  
		          echo "false"; 
		  }
		}
	}

	public function validaAutorizado() {
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		  $this->load->model('solicitud/solicitud');
		  
		  $autonombre = $this->request->post['autonombre'];
		  $solicita = $this->model_solicitud_solicitud->getAutorizado(strtoupper($autonombre));
		
		  if ($solicita == 9999) {
			  echo "false";
		  } else {	  
		        echo "true"; 
		  }
		}
	}	
	
	public function autocompleteCantidad(){
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('solicitud/solicitud'); //model_solicitud_solicitud

			if (isset($this->request->post['hwsolcant'])) {
				$hwsolcant = $this->request->post['hwsolcant'];
			} else {
				$hwsolcant = '';
			}
			if (isset($this->request->post['hwpacking'])) {
				$hwpacking = $this->request->post['hwpacking'];
			} else {
				$hwpacking = '';
			}
			if (isset($this->request->post['hwartcod'])) {
				$hwartcod = $this->request->post['hwartcod'];
			} else {
				$hwartcod = '';
			}
			if (isset($this->request->post['subcuenta'])) {
				$subcuenta = $this->request->post['subcuenta'];
			} else {
				$subcuenta = '';
			}      
			if (isset($this->request->post['hwcaja'])) {
				$hwcaja = $this->request->post['hwcaja'];
			} else {
				$hwcaja = '';
			}
			
			if (isset($this->request->post['hwsolest'])) {
				$hwsolest = $this->request->post['hwsolest'];
			} else {
				$hwsolest = '';
			}//hwsolest

			if (isset($this->request->post['limit'])) {
				$limit = $this->request->post['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
				'hwsolcant'	=> strtoupper($hwsolcant),
				'hwpacking'	=> strtoupper($hwpacking),
				'hwartcod' 	=> strtoupper($hwartcod),
				'hwcaja'	=> strtoupper($hwcaja),
				'hwsolest'	=> strtoupper($hwsolest),
				'subcuenta'	=> strtoupper($subcuenta),
				'start'		=> 0,
				'limit'     => $limit
			);

			$results = $this->model_solicitud_solicitud->autoCompleteCant($filter_data);

			foreach ($results as $result) {
				$resultado[]=array(
					'caja'			=>$result['HWCAJA'],
					'linea'			=>$result['HWLINEA'],
					'disponible'	=>$result['DISPONIBLE'],
					'cantidad'		=>strtoupper($hwsolcant),
					'hwpacking'		=>$result['HWPACKING'],
				);
			}				
		}
		//print_r($resultado);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}
	
	public function refrescar(){
		$json = array();

		if (isset($this->request->get['hwmr']))
		{
			$this->load->model('solicitud/solicitud');

			if (isset($this->request->get['hwmr'])) {
				$hwmr = $this->request->get['hwmr'];
			} else {
				$hwmr = '';
			}
		
			$results =  $this->model_solicitud_solicitud->getDetMdr($hwmr);

			foreach ($results as $result) {
				$resultado[]=array(
				    'hwlinsol'   => $result['HWLINSOL'],
					'hwartcod'	 => $result['HWARTCOD'],
					'hwartdesc'	 => $result['HWARTDESC'],
					'hwlinea'	 => $result['HWLINEA'],
					'hwpacking'	 => $result['HWPACKING'],
					'hwcaja'	 => $result['HWCAJA'],
					'hwsolcant'	 => $result['HWSOLCANT'],
					'hwsolest'	 => $result['HWSOLEST'],
					'hwsolaent'	 => $result['HWSOLAENT'],
					'sitnom'	 => $result['SITNOM'],
				);
			}
				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));	
	}
	
	public function autocompletePacking(){
		$json = array();

		if (isset($this->request->get['hwpacking']))
		{
			$this->load->model('solicitud/solicitud'); //model_solicitud_solicitud

			if (isset($this->request->get['hwpacking'])) {
				$hwpacking = $this->request->get['hwpacking'];
			} else {
				$hwpacking = '';
			}
			if (isset($this->request->get['hwartcod'])) {
				$hwartcod = $this->request->get['hwartcod'];
			} else {
				$hwpacking = '';
			}        

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
							'hwpacking'	=> strtoupper($hwpacking),
							'hwartcod' 	=> strtoupper($hwartcod),
							'start'		=> 0,
							'limit'     => $limit
			);

			//print_r($filter_data);

			$results = $this->model_solicitud_solicitud->autoCompletePack($filter_data);

			foreach ($results as $result) {
				$resultado[]=array(
					'hwpacking'=>$result['HWPACKING']
				);
			}				
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	public function llenaArt() {
		$json = array();
		
	   if (isset($this->request->get['hwartcod'])) {
										
		 $this->load->model('solicitud/solicitud');

		// $hwartcod = $this->request->get['hwartcod'];
		if (isset($this->request->get['hwartcod'])) {
			$hwartcod = $this->request->get['hwartcod'];
		} else {
			$hwartcod = '';
		}
		if (isset($this->request->get['serie'])) {
			$serie = $this->request->get['serie'];
		} else {
			$serie = '';
		}
		if (isset($this->request->get['subcuenta'])) {
			$subcuenta = $this->request->get['subcuenta'];
		} else {
			$subcuenta = '';
		}
		
		 $filter_data = array(
			'hwartcod' => strtoupper($hwartcod),
			'serie' => strtoupper($serie),
			'subcuenta' => strtoupper($subcuenta),
			'start'        => 0,
			'limit'        => 5
		 );

 		$results = $this->model_solicitud_solicitud->autoCompleteArticulos($filter_data);

		foreach ($results as $result) {
			$resultado[]=array(
				'artcod' => $result['HWARTCOD'],
				'artdes' => $result['HWARTDESC']
			);
		}
								
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	public function llenaSitio() {
		$json = array();
		
	   if (isset($this->request->get['hwsitio'])) {
										
		 $this->load->model('solicitud/solicitud');

		 $hwsitio = $this->request->get['hwsitio'];
		
		 $filter_data = array(
			'hwsitio' => strtoupper($hwsitio),
			'start'        => 0,
			'limit'        => 5
		 );

 		$results = $this->model_solicitud_solicitud->autoCompleteSitio($filter_data);

		foreach ($results as $result) {
			$resultado[]=array(
				'sitid' => $result['SITID'],
				'sitnom' => $result['SITNOM']
			);
		}
								
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	public function llenaSubcuenta() {
		$json = array();
		
	   	if (isset($this->request->get['subcuenta'])) {
										
			$this->load->model('solicitud/solicitud');

		 	$subcuenta = $this->request->get['subcuenta'];
		
		 	$filter_data = array(
				'subcuenta' => strtoupper($subcuenta),
				'start'        => 0,
				'limit'        => 5
			);

 			$results = $this->model_solicitud_solicitud->autoCompleteSubCuenta($filter_data);

			foreach ($results as $result) {
				$resultado[]=array(
					'tigosubcta_cade' => $result['TIGOSUBCTA_CODE'],
					'tigosubcta_descrip' => $result['TIGOSUBCTA_DESCRIP']
				);
			}
								
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	public function llenaSerie() {
		$json = array();
		
	   	if (isset($this->request->get['serie'])) {
										
			$this->load->model('solicitud/solicitud');

			if (isset($this->request->get['serie'])) {
				$serie = $this->request->get['serie'];
			} else {
				$serie = '';
			}
			if (isset($this->request->get['subcuenta'])) {
				$subcuenta = $this->request->get['subcuenta'];
			} else {
				$subcuenta = '';
			}
		
		 	$filter_data = array(
				'serie' => strtoupper($serie),
				'subcuenta' => strtoupper($subcuenta),
				'start'        => 0,
				'limit'        => 5
			);

 			$results = $this->model_solicitud_solicitud->autoSerie($filter_data);

			foreach ($results as $result) {
				$resultado[]=array(
					'hwserie' => $result['HWSERIE']
				);
			}
								
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	public function autocomplete() {
		$json = array();

		//if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
		if (($this->request->server['REQUEST_METHOD'] == 'POST'))
		{
			$this->load->model('solicitud/solicitud'); //model_solicitud_solicitud

			if (isset($this->request->post['hwartcod'])) {
				$hwartcod = $this->request->post['hwartcod'];
			} else {
				$hwartcod = '';
			}      

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 5;
			}

			$filter_data = array(
							'hwartcod'	=> strtoupper($hwartcod),
							'start'		=> 0,
							'limit'     => $limit
			);

			$results = $this->model_solicitud_solicitud->autoCompleteArticulos($filter_data);

			foreach ($results as $result) {
				$resultado[]=array(
					'artcod'=>$result['HWARTCOD'],
					'artdes'=>$result['HWARTDESC'],
				);
			}				
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
	}

	protected function validateForm() {
		/*
		if (!$this->user->hasPermission('modify', 'tracking/solicitud')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ((utf8_strlen($this->request->post['autonombre']) <= 0)) {
            $this->error['error_solicita'] = 'Debe ingresar un usuario valido!';
	    }
        else {
			
		}
		*/
       return false;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'tracking/solicitud')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $user_id) {
			if ($this->user->getId() == $user_id) {
				$this->error['warning'] = $this->language->get('error_account');
			}
		}

		return !$this->error;
	}
}