<?php
class ControllerToolCarga extends Controller {
	private $error = array();

	public function index(){
		$this->load->language('tool/carga');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/carga');

		if ($this->request->server['REQUEST_METHOD'] == 'POST'){
			if($this->user->hasPermission('modify', 'tool/carga')){
				if (is_uploaded_file($this->request->files['import']['tmp_name'])){
					$inputFileName = $this->request->files['import']['tmp_name'];

					try{
						$name = $this->request->files['import']['name'];
				   		$part = explode(".",$name);
						$extension = strtolower($part[1]);
				   
				   		if ($extension=='zip') {
							$zip = new ZipArchive;
                     		$res = $zip->open($inputFileName);
				     		$path = '/tmp/imp';
  
                     		if ($res === TRUE) {
                       			$zip->extractTo($path);
                       			$zip->close();
					 
					   			$files = array_diff(scandir($path), array('.', '..'));
					 
					   			foreach ($files as $file) {
					     			$offer=$this->model_tool_cargacbs->cargarXML($path.'/'.$file);
						 			if ($offer) {
						 				$offers[] = array(
						    				'offerid' => $offer['offerid'],
											'name'    => $offer['name'],
						 				);
						 			}
						 			unlink($path.'/'.$file); 
					   			}
                    		} else {
                           		die('extraction error');
                          	}
				  		} elseif ($extension == 'xml') {
					     /*
                        $offers=$this->model_tool_cargacbs->cargarXML($inputFileName); 
						 if ($offer) {
						 $offers[] = array(
						    'offerid' => $offer['offerid'],
							'name'    => $offer['name'],
						 );
						 }	*/					
                  		}	elseif ($extension == 'xlsx') {
							$carga=$this->model_tool_carga->cargarExcel($inputFileName,'Excel2007',$name);  						  
                  		}elseif ($extension == 'xls') {
					      	$carga=$this->model_tool_carga->cargarExcel($inputFileName,'Excel5',$name);
                  		}					  
                  		else {
							$carga=null; 
                        	die('Archivo no reconocido');   
                  		}				  
                 		$data['errores'] =$carga;		  			   			   
                  	} catch(Exception $e) {
                   		die('Error cargando archivo "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                  	}
				} else {
					$content = false;
					echo "No encontro el archivo";
					exit(0);
				}
		  	}
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
        $data['text_cargaoffers'] = $this->language->get('text_cargaoffers');
		$data['entry_export'] = $this->language->get('entry_export');
		$data['entry_import'] = $this->language->get('entry_import');

		$data['button_export'] = $this->language->get('button_export');
		$data['button_import'] = $this->language->get('button_import');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/carga', 'token=' . $this->session->data['token'], true)
		);

		$data['restore'] = $this->url->link('tool/carga', 'token=' . $this->session->data['token'], true);
		
		$data['backup'] = $this->url->link('tool/carga/backup', 'token=' . $this->session->data['token'], true);

		$data['tables'] = $offers;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/carga', $data));
	}

	public function backup() {
		
	}
}
