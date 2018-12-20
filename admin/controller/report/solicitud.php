<?php
class ControllerReportSolicitud extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('report/solicitud');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('solicitud/solicitud');

		$this->getList();
	}

	public function getDiasBetween($fechai,$fechaf) {
		date_default_timezone_set('America/Guatemala');                
					$fecha_antes   = strtotime($fechai);
		$fecha_despues = strtotime($fechaf);             
					$datediff  = $fecha_despues-$fecha_antes; 
					return floor($datediff / (60 * 60 * 24));                
	}

	protected function getList() {
		if (isset($this->request->get['filter_estado'])) {
			$filter_estado = $this->request->get['filter_estado'];
		} else {
			$filter_estado = 0;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}		
		
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

		if (isset($this->request->get['filter_starttime'])) {
			$filter_starttime = $this->request->get['filter_starttime'];
		} else {
			$filter_starttime = null;
		}

		if (isset($this->request->get['filter_endtime'])) {
			$filter_endtime = $this->request->get['filter_endtime'];
		} else {
			$filter_endtime = null;
        }
        
        //siempre va de cajon 
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

		if (isset($this->request->get['filter_estado'])) {
			$url .= '&filter_estado=' . $this->request->get['filter_estado'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}		
		
		if (isset($this->request->get['filter_mdr'])) {
			$url .= '&filter_mdr=' . $this->request->get['filter_mdr'];
		}
		
		if (isset($this->request->get['filter_cormdr'])) {
			$url .= '&filter_cormdr=' . $this->request->get['filter_cormdr'];
		}

		if (isset($this->request->get['filter_starttime'])) {
			$url .= '&filter_starttime=' . $this->request->get['filter_starttime'];
		}

		if (isset($this->request->get['filter_endtime'])) {
			$url .= '&filter_endtime=' . $this->request->get['filter_endtime'];
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
        /*
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);
        */
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/solicitud', 'token=' . $this->session->data['token'] . $url, true)
		);
		
		$data['solicitudes'] = array();

		$filter_data = array(
            'filter_estado'             => $filter_estado, 
			'filter_status'             => $filter_status,
			'filter_cormdr'             => $filter_cormdr, 
			'filter_mdr'                => $filter_mdr,
			'filter_starttime'          => $filter_starttime,
			'filter_endtime'            => $filter_endtime,
			'sort'                      => $sort,
			'order'                     => $order,
			'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                     => $this->config->get('config_limit_admin')
		);
		
		$data['status'] = array (0 => 'Iniciada',
		                         1 => 'Finalizada'		
		);
		
		$data['estado'] = array (1 => 'Ingresado',
		                         2 => 'Finalizado',
                                 3 => 'En Proceso',
                                 4 => 'Por vencer',
								 5 => 'Demorado'
		);
			
		$TotalSolicitudes  = $this->model_solicitud_solicitud->totalSolicitudes($filter_data);	
			
		$results = $this->model_solicitud_solicitud->obtenerSolicitudes($filter_data);

		$util = New Util();

        foreach ($results as $solicitud) {
            $estado    = $solicitud['STATUS'];
			$entregado = $solicitud['HWENTREGADO'];

			$est = $solicitud['MRHW_ESTADO'];
			$descEstado = "";
			if($est == 0){
				$descEstado = "INICIADA";
			 }
			 else {
				$descEstado = "FINALIZADA";
			 }


			$fentrega  = $solicitud['FENTREGA'];
			
			$tstamp    = $util->getDateHour();
			$tstamp   .= ":00:00";

			date_default_timezone_set('America/Guatemala');                
			$f    = strtotime($fentrega);
			$fr   = strtotime($tstamp);

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

			$solicitudes[] = array (
				'hwmr'   		  => $solicitud['HWMR'],
				'hwmrno' 		  => $solicitud['HWMRNO'],
				'hwfechasol'  	  => $solicitud['HWFECHASOL'],
                'hwfechaentrega'  => $solicitud['HWFECHAENTREGA'],
                'hwentregado'     => $solicitud['HWENTREGADO'],
				'mrhw_estado'     => $descEstado,
				'estado'          => $estado,
				'color'		      => $bgcolor
			);
		}

		$data['solicitudes'] = $solicitudes;

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_loading'] = $this->language->get('text_loading');

 
		$data['column_md']         = $this->language->get('column_md');
		$data['column_mdr']         = $this->language->get('column_mdr');
        $data['column_fsolicitud']  = $this->language->get('column_fsolicitud');
        $data['column_fentrega']     = $this->language->get('column_fentrega');
        $data['column_entregado']     = $this->language->get('column_entregado');
        $data['column_estado'] = $this->language->get('column_estado');
        $data['column_starttime']       = $this->language->get('column_starttime');
        $data['column_endtime']         = $this->language->get('column_endtime');	

		$data['entry_cormdr']          = $this->language->get('entry_cormdr');
		$data['entry_mdr']             = $this->language->get('entry_mdr');
		$data['entry_estado']          = $this->language->get('entry_estado');
		$data['entry_status']          = $this->language->get('entry_status');
		$data['entry_starttime']       = $this->language->get('entry_starttime');
		$data['entry_endtime']         = $this->language->get('entry_endtime');
		$data['button_filter']         = $this->language->get('button_filter');
		$data['button_cancel']         = $this->language->get('button_cancel');
		//Sirver para sincronizar la sesion siempre va.

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

		if (isset($this->request->get['filter_estado'])) {
			$url .= '&filter_estado=' . $this->request->get['filter_estado'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}	
		
		if (isset($this->request->get['filter_cormdr'])) {
			$url .= '&filter_cormdr=' . $this->request->get['filter_cormdr'];
		}	
		
		if (isset($this->request->get['filter_mdr'])) {
			$url .= '&filter_mdr=' . $this->request->get['filter_mdr'];
		}		
		
		if (isset($this->request->get['filter_starttime'])) {
			$url .= '&filter_starttime=' . $this->request->get['filter_starttime'];
		}

		if (isset($this->request->get['filter_endtime'])) {
			$url .= '&filter_endtime=' . $this->request->get['filter_endtime'];
		}

		$data['sort_hwmr'] = $this->url->link('report/solicitud', 'token=' . $this->session->data['token'] . '&sort=hwmr' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}	

		$pagination = new Pagination();
		$pagination->total = $TotalSolicitudes;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/solicitud', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($TotalSolicitudes) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($TotalSolicitudes - $this->config->get('config_limit_admin'))) ? $TotalSolicitudes : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $TotalSolicitudes, ceil($TotalSolicitudes / $this->config->get('config_limit_admin')));

		$data['filter_estado'] 		= $filter_estado;
		$data['filter_status'] 	= $filter_status;
		$data['filter_cormdr']		= $filter_cormdr;
		$data['filter_mdr']			= $filter_mdr;
		$data['filter_starttime']   = $filter_starttime;
		$data['filter_endtime']     = $filter_endtime;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['cancel'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'] . $url, true);
	
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/solicitud', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_mdr']))
		 {
										
						$this->load->model('solicitud/solicitud');

						if (isset($this->request->get['filter_mdr'])) {
										$filter_mdr = $this->request->get['filter_mdr'];
						} else {
										$filter_mdr = '';
						}      

						if (isset($this->request->get['limit'])) {
										$limit = $this->request->get['limit'];
						} else {
										$limit = 5;
						}

						$filter_data = array(
										'filter_mdr' => $filter_mdr,
										'start'        => 0,
										'limit'        => $limit
						);

						$results = $this->model_solicitud_solicitud->autoCompleteSolicitudes($filter_data);

						foreach ($results as $result) {
										$resultado[]=array(
											'mdr'=>$result['HWMRNO']
										);
										}

										
						}
		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($resultado));
}


	}
