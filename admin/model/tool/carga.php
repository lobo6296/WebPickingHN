<?php
static $registry = null;


function error_handler_for_export_import($errno, $errstr, $errfile, $errline) {
	global $registry;

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$errors = "Notice";
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$errors = "Warning";
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$errors = "Fatal Error";
			break;
		default:
			$errors = "Unknown";
			break;
	}
	$config = $registry->get('config');
	$url = $registry->get('url');
	$request = $registry->get('request');
	$session = $registry->get('session');
	$log = $registry->get('log');
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	if (($errors=='Warning') || ($errors=='Unknown')) {
		return true;
	}

	if (($errors != "Fatal Error") && isset($request->get['route']) && ($request->get['route']!='tool/export_import/download'))  {
		if ($config->get('config_error_display')) {
			echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		}
	} else {
		$session->data['export_import_error'] = array( 'errstr'=>$errstr, 'errno'=>$errno, 'errfile'=>$errfile, 'errline'=>$errline );
		$token = $request->get['token'];
		$link = $url->link( 'tool/export_import', 'token='.$token, 'SSL' );
		header('Status: ' . 302);
		header('Location: ' . str_replace(array('&amp;', "\n", "\r"), array('&', '', ''), $link));
		exit();
	}

	return true;
}

function fatal_error_shutdown_handler_for_export_import() {
	$last_error = error_get_last();
	if ($last_error['type'] === E_ERROR) {
		// fatal error
		error_handler_for_export_import(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
	}
}

class ModelToolCarga extends Model {
	//this function is used to validate that all the fields that I am going to insert into 
	//the database are not empty
	
	function getValue($valor){
		$retorno = "null";
		if (!empty($valor)) {$retorno=$valor;}
		return $retorno;
	}

	function eliminar_tildes($cadena){
 
		//Codificamos la cadena en formato utf8 en caso de que nos de errores
		$cadena = utf8_encode($cadena);
	 
		//Ahora reemplazamos las letras
		$cadena = str_replace(
			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
			$cadena
		);
	 
		$cadena = str_replace(
			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
			$cadena );
	 
		$cadena = str_replace(
			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
			$cadena );
	 
		$cadena = str_replace(
			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
			$cadena );
	 
		$cadena = str_replace(
			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
			$cadena );
	 
		$cadena = str_replace(
			array('ñ', 'Ñ', 'ç', 'Ç'),
			array('n', 'N', 'c', 'C'),
			$cadena
		);
	 
		return $cadena;
	}

	function getFecHora(){
		$db = $this->conectar(2);
		$tipcode = $this->session->data['tipinv'];
		$sql = "SELECT
				TO_CHAR(SYSDATE,'ddmmyyyy_HH24miss') AS FECHAHORA
				FROM DUAL";
		$query = $db->query($sql);
		$FecHors = $query->row['FECHAHORA'];
		return $FecHors;
	}

	function getFec(){
		$db = $this->conectar(2);
		$tipcode = $this->session->data['tipinv'];
		$sql = "SELECT
				TO_CHAR(SYSDATE,'ddmmyyyy') AS FECHAHORA
				FROM DUAL";
		$query = $db->query($sql);
		$FecHors = $query->row['FECHAHORA'];
		return $FecHors;
	}

	function getCuenta($cuenta){
		$db = $this->conectar(2);
		$tipcode = $this->session->data['tipinv'];
		$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.TIPTRAE WHERE TIPDESCE = '" . $cuenta . "'";
		$query = $db->query($sql);
		$corr = $query->row['CONTADOR'];
		if ($corr >= 1){
			$sql2 = "SELECT tipcode AS TIPO FROM TIGO.TIPTRAE WHERE TIPDESCE = '" . $cuenta . "'";
			$querys = $db->query($sql2);
			$corr = $querys->row['TIPO'];
		}
		else{
			$corr = 9999;
		}
		if ($corr >= 0 && $tipcode <> $corr){
			$corr = 0;
		}
		return $corr;
	}

	function getMRHW($mrhw){
		$db = $this->conectar(2);
		$tipcode = $this->session->data['tipinv'];
		$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.MRHW WHERE TIPCODE = " . $tipcode . " AND HWMRNO = '" . $mrhw . "'";
		$query = $db->query($sql);
		$cont = $query->row['CONTADOR'];
		if ($cont == 0){
			$cont = $mrhw;
		}
		else{
			$cont = 9999;
		}
		
		return $cont;
	}

	function getSitios($sitio){
		$db = $this->conectar(2);
		$sql = "SELECT COUNT(*) AS SITIO FROM TIGO.SITIOS WHERE SITNOM = '" . $sitio . "'";
		$query = $db->query($sql);
		$rst = $query->row['SITIO'];
		if ($rst >= 1){
			$sql2 = "SELECT SITID FROM TIGO.SITIOS WHERE SITNOM = '" . $sitio . "'";
			$query = $db->query($sql2);
			$rst = $query->row['SITID'];
		}
		else{
			$rst = 9999;
		}
		return $rst;
	}

	function getAutorizado($autorizado){
		$db = $this->conectar(2);
		$tipcode = $this->session->data['tipinv'];
		$sql = "SELECT COUNT(*) AS CUENTA FROM TIGO.AUTORIZADOS WHERE TIPCODE = " . $tipcode . " AND AUTNOMBRE = UPPER('" . $autorizado . "')";
		$query = $db->query($sql);
		$rs = $query->row['CUENTA'];
		if ($rs >= 1){
			$sql2 = "SELECT AUTCOD FROM TIGO.AUTORIZADOS WHERE TIPCODE = " . $tipcode . " AND AUTNOMBRE = '" . $autorizado . "'";
			$query = $db->query($sql2);
			$rs = $query->row['AUTCOD'];
		}
		else{
			$rs = 9999;
		}
		
		return $rs;
	}

	public function setFecha($fechaV){
		$unixdate = ($fechaV - 25569) * 86400;
        $fecPruebas = gmdate("YmdHis", $unixdate);

		$rst = "to_date('".$fecPruebas."','yyyymmddhh24miss')";
		return $rst;
	}

	public function getCorrelativo(){
		$tipdesce = $this->session->data['tipdesce'];
		$db = $this->conectar(2);
	
		$sql = "SELECT NVL(CORNUM,0) + 1 AS CORR 
				FROM TIGO.CORRELATIVOS 
				WHERE CORDESC = '" . $tipdesce . "' ";
	
		$query = $db->query($sql);
	
		$corr = $query->row['CORR'];
	
		$sql = "UPDATE TIGO.CORRELATIVOS SET CORNUM = ". $corr ." WHERE CORDESC = '" . $tipdesce . "'";
		$query = $db->query($sql);
	
		return $corr;
	}

	function getTipoSolicitud($solicitud){
		$rs = 0;
		$Tipsolicitud = $this->eliminar_tildes($solicitud);

		if($Tipsolicitud == 'Entrega en Sitio'){
			$rs = 1;
		} elseif($Tipsolicitud == 'Prestamo'){
			$rs = 2;
		} elseif($Tipsolicitud == 'Reemplazo'){
			$rs = 3;
		}
		return $rs;
	}

	function getEntregaxLinea($dato){
		$retorno = $correlativo;
		if (!empty($dato)) {
			$retorno=$dato;
		}
		return $retorno;
	}

	function getPacking($pack){
		$db = $this->conectar(2);
		$rs = 9998;
		$tipcode = $this->session->data['tipinv'];
		if ($pack <> "null"){
			$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.INGRESOHW WHERE TIPCODE = " . $tipcode . " AND HWPACKING = UPPER('" . $pack . "')";
			$query = $db->query($sql);
			$rs = $query->row['CONTADOR'];
			if ($rs == 0){
				$rs = 9998;
			}else {
				$rs = $pack;
			}
		}
		return $rs;
	}

	function getCaja($ppack,$pcaja){
		$db = $this->conectar(2);
		$rs = 9998;
		$tipcode = $this->session->data['tipinv'];
		if ($pcaja <> "null"){
			if ($ppack <> 9998){
				$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.DETINGHW  WHERE HWPACKING = '" . $ppack . "' AND HWCAJA = " . $pcaja . "";
				$query = $db->query($sql);
				$rs = $query->row['CONTADOR'];
				if ($rs == 0){
					$rs = 9998;
				}else {
					$rs = $pcaja;
				}
			}
		}
		return $rs;
	}

	function getArticulo($ppack,$pcaja,$partcod){
		$db = $this->conectar(2);
		$rs = 0;
		$tipcode = $this->session->data['tipinv'];
		if ($ppack <> 9998){
			if ($pcaja <> 9998){
				$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.DETINGHW  WHERE HWPACKING = '" . $ppack . "' AND HWCAJA = " . $pcaja ." and HWARTCOD = '" . $partcod . "'";
				$query = $db->query($sql);
				$rs = $query->row['CONTADOR'];
				if ($rs == 0){
					$rs = 9998;
				}else {
					$rs = $partcod;
				}
			}else {
				$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.DETINGHW  WHERE HWPACKING = '" . $ppack . "' AND HWARTCOD = '" . $partcod . "'";
				$query = $db->query($sql);
				$rs = $query->row['CONTADOR'];
				if ($rs == 0){
					$rs = 9998;
				}else {
					$rs = $partcod;
				}
			}
		}else{
			$sql = "SELECT COUNT(*) AS CONTADOR FROM TIGO.DETINGHW  WHERE 1=1 AND HWARTCOD = '" . $partcod . "'";
				$query = $db->query($sql);
				$rs = $query->row['CONTADOR'];
				if ($rs == 0){
					$rs = 9998;
				}else {
					$rs = $partcod;
				}
		}
		return $rs;
	}

	function getEstado($pestado){
		$rs = 99;
		if ($pestado == 'Buen estado'){
			$rs = 0;
		}elseif ($pestado == 'Mal estado'){
			$rs = 1;
		}
		return $rs;
	}

	function putReserva($ppacking,$pcaja,$plinea,$partcod,$pcantDes){
		$db = $this->conectar(2);
		$sql = "UPDATE TIGO.DETINGHW
  			SET HWRESERVADO = HWRESERVADO + ".$pcantDes."
  		WHERE HWPACKING = '".$ppacking."' 
			AND HWCAJA = " .$pcaja . " 
			AND HWLINEA = ".$plinea."
			AND HWARTCOD = '".$partcod."'";
		//echo $sql . "\n";
		//echo "<br>";
		$query = $db->query($sql);
	}

	function getCantidad($detalle,$data){
		$contLinea = 0;
		$db = $this->conectar(2);

		$ppack = $data['packingList'];
		$pcaja = $data['caja'];
		$pcodArticulo = $data['codArticulo'];
		$pestado = $data['estado'];
		$pcantidad = $data['cantidad'];

		$rs = 0;
		$pcantSol 		= $pcantidad;
		$pcantDes 		= 0;
		$pcantPen 		= 0;
		$vpacking 		= "null";
		$vcaja			= 0;
		$vlinea			= 0;
		$vreserva		= 0;
		$vexistencia	= 0;
		$vmax			= 0;
		$vartcod		= "null";
		$pcantPen 		= $pcantSol;

		//echo "1\n";

		$tipcode = $this->session->data['tipinv'];
			if($pestado == 0){
			//	echo "2\n";
				
				while ($pcantSol <> $pcantDes){
			//		echo "3\n";
					$sqle = "SELECT COUNT(*) AS EXISTENCIA 
						FROM TIGO.DETINGHW  
							WHERE 1=1 AND (HWRECBUEN - HWDESPBUEN - HWRESERVADO) > 0";
							if ($ppack <> 9998){
								$sqle .= " AND HWPACKING = '".$ppack."' ";
							}
							if ($pcaja <> 9998){
								$sqle .= " AND HWCAJA = ".$pcaja." ";
							} 
							$sqle .= " AND HWARTCOD = '".$pcodArticulo."' ";
					
					$querye = $db->query($sqle);
					$vexistencia = $querye->row['EXISTENCIA'];

					if ($vexistencia > 0){
						$contLinea ++;
			//			echo "4 \n";
						$vmax = 0;
						$disponible = 0;
						//Encuentro el disponible maximo
						$sqlm = "SELECT NVL(MAX(HWRECBUEN - HWDESPBUEN - HWRESERVADO),0) AS MAXIMO 
						FROM TIGO.DETINGHW  
							WHERE 1=1 AND (HWRECBUEN - HWDESPBUEN - HWRESERVADO) > 0";
							if ($ppack <> 9998){
								$sqlm .= " AND HWPACKING = '".$ppack."' ";
							}
							if ($pcaja <> 9998){
								$sqlm .= " AND HWCAJA = ".$pcaja." ";
							} 
							$sqlm .= " AND HWARTCOD = '".$pcodArticulo."' ";

						$querym = $db->query($sqlm);
						$vmax 	= $querym->row['MAXIMO'];
						$sql = "SELECT HWPACKING,HWCAJA,HWLINEA,HWARTCOD,HWRESERVADO,(HWRECBUEN - HWDESPBUEN - HWRESERVADO) AS DISPONIBLE 
						FROM TIGO.DETINGHW  
							WHERE 1=1 AND (HWRECBUEN - HWDESPBUEN - HWRESERVADO) = " . $vmax . " ";
							if ($ppack <> 9998){
								$sql .= " AND HWPACKING = '".$ppack."' ";
							}
							if ($pcaja <> 9998){
								$sql .= " AND HWCAJA = ".$pcaja." ";
							} 
							$sql .= " AND HWARTCOD = '".$pcodArticulo."' ";

						$query = $db->query($sql);

						$vpacking 	= $query->row['HWPACKING'];
						$vcaja		= $query->row['HWCAJA'];
						$vlinea		= $query->row['HWLINEA'];
						$vartcod	= $query->row['HWARTCOD'];
						$vreserva	= $query->row['HWRESERVADO'];
						$disponible = $query->row['DISPONIBLE'];
						
						//$coso = "disponible = ". $disponible . " - " . $pcantSol . "\n";
						//echo $coso ;

						if ($disponible >= $pcantSol && $pcantSol > 0){
							//echo "6 \n";
							//$pcantSol 	= 0;
							$pcantDes	= $pcantSol;
							$pcantPen	= 0;
							$this->putReserva($vpacking,$vcaja,$vlinea,$vartcod,$pcantDes);
							$detalle[] = array(
								'lineaDoc' 		=> $contLinea,
								'packingList' 	=> trim($vpacking),
								'caja' 			=> trim($vcaja),
								'codArticulo' 	=> $data['codArticulo'],
								'noSerie' 		=> $data['noSerie'],
								'cantidadS' 	=> trim($pcantidad),
								'cantidadDes' 	=> trim($pcantDes),
								'uniMed' 		=> $data['uniMed'],
								'estado' 		=> $data['estado'],
								'subcuenta' 	=> $data['subcuenta'],
								'linea'			=> trim($vlinea),
						   ) ;
						   
						}else{
							//echo "7 \n";

							if ($pcantSol > $disponible){
								//echo "8 \n";
								//echo "sol = " . $pcantSol . " Dis= " . $disponible." \n";
								$pcantDes	= $disponible;
								$pcantSol	= $pcantSol - $disponible;
								$pcantPen	= $pcantSol;
								//echo "9 \n";
								//echo "sol = " . $pcantSol . " Dis= " . $disponible." \n";
							} else{
								//echo "10 \n";
								$pcantDes = $pcantSol;
								$pcantSol	= 0;
							}

							$detalle[] = array(
								'lineaDoc' 		=> $data['lineaDoc'],
								'packingList' 	=> trim($vpacking),
								'caja' 			=> trim($vcaja),
								'codArticulo' 	=> $data['codArticulo'],
								'noSerie' 		=> $data['noSerie'],
								'cantidadS' 	=> trim($pcantidad),
								'cantidadDes' 	=> trim($pcantDes),
								'uniMed' 		=> $data['uniMed'],
								'estado' 		=> $data['estado'],
								'subcuenta' 	=> $data['subcuenta'],
								'linea'			=> trim($vlinea),
						   ) ;

							$this->putReserva($vpacking,$vcaja,$vlinea,$vartcod,$pcantDes);
						}
						
					}else{
						//echo "88\n";
						$pcantDes	= $pcantSol;
					}
				}
				
			}elseif ($pestado == 1){
				//echo "9\n";
				$sql = "SELECT (HWRECMAL - HWDESPMAL - HWRESERVMAL) AS DISPONIBLE FROM TIGO.DETINGHW  WHERE HWPACKING = '".$ppack."' AND HWCAJA = ".$pcaja." and HWARTCOD = '".$pcodArticulo."'";
				$query = $db->query($sql);
				$disponible = $query->row['DISPONIBLE'];
				if($disponible >= $pcantidad){
					$rs = $pcantidad;
				} else {
					$rs = 9998;
				}
			}

		return $detalle;
	}
	//-------------------------------------------------------------------------------------------------------------------------------------

	public function cargarExcel($inputFilName,$fileType,$name){

		require_once('/var/www/html/sis/webpicking/system/PHPExcel/Classes/PHPExcel.php' );
    	spl_autoload_unregister(array('YiiBase', 'autoload'));
		require_once('/var/www/html/sis/webpicking/system/PHPExcel/Classes/PHPExcel.php' );
		set_time_limit(1800);
	
		//Function to read the excel
		$objReader = PHPExcel_IOFactory::createReader($fileType);
    	$objReader->setReadDataOnly(true);
    	$objPHPExcel = $objReader->load($inputFilName);
		$objWorksheet = $objPHPExcel->getActiveSheet();

		//last row used
		$highestRow = $objWorksheet->getHighestRow();
		//last column used
    	$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

		$fechaCarga	= $this->getFecHora();
		$Fec		= $this->getFec();
		$nomArchivo	.= $Fec. ".json";
		
		//cabecera
		$correlativo	= $this->getCorrelativo();
		$autcod 		= $this->getAutorizado($this->getValue($objWorksheet->getCellByColumnAndRow(4, 4)->getValue()));
		$cuenta			= $this->getCuenta($this->getValue($objWorksheet->getCellByColumnAndRow(2, 2)->getValue()));
		$mr				= $this->getMRHW($this->getValue($objWorksheet->getCellByColumnAndRow(0, 4)->getValue()));
		
		$sitioEntrega	= $this->getSitios($this->getValue($objWorksheet->getCellByColumnAndRow(2, 4)->getValue()));
		
		$fecSolicitud	= $this->setFecha($objWorksheet->getCellByColumnAndRow(1, 4)->getValue());
		$fecEntrega		= $this->setFecha($objWorksheet->getCellByColumnAndRow(3, 4)->getValue());
		
		$hwtiposol		= $this->getTipoSolicitud($objWorksheet->getCellByColumnAndRow(5, 4)->getValue());
		$estado			= '0';
		$viasol			= 'W';
		$digitador		= $this->session->data['nomusu'];
		$entregado		= '0';
		//fin cabecera
    
		//cargo la cabecera en un array
		$cabecera[] = array (
		   'correlativo'  => trim($correlativo),
		   'autcod'       => trim($autcod),
		   'cuenta'       => trim($cuenta),
		   'mr'           => trim($mr),
		   'sitiEntrega'  => trim($sitioEntrega),
		   'fecSolicitud' => trim($fecSolicitud),
		   'fecEntrega'   => trim($fecEntrega),
		   'hwtiposol'    => trim($hwtiposol),
		   'estado'       => trim($estado),
		   'viasol'       => trim($viasol),
		   'digitador'    => trim($digitador),
		   'entregado'    => trim($entregado),  
		); 

		//detalle
		for ($row = 7; $row <= $highestRow; ++$row){
			$lineaDoc		= $this->getValue($objWorksheet->getCellByColumnAndRow(0, $row)->getValue());
			$packingList	= $this->getPacking($this->getValue($objWorksheet->getCellByColumnAndRow(2, $row)->getValue()));
			$caja			= $this->getCaja($packingList,$this->getValue($objWorksheet->getCellByColumnAndRow(3, $row)->getValue()));
			$codArticulo	= $this->getArticulo($packingList,$caja,$this->getValue($objWorksheet->getCellByColumnAndRow(4, $row)->getValue()));
			$noSerie		= "null";
			$uniMed			= "null";
			$estado			= $this->getEstado($this->getValue($objWorksheet->getCellByColumnAndRow(8, $row)->getValue()));
			$subcuenta		= "null";
			$cantidad		= $this->getValue($objWorksheet->getCellByColumnAndRow(6, $row)->getValue());
			
				$datos = array(
					'lineaDoc'		=> Trim($lineaDoc),
					'packingList'	=> Trim($packingList),
					'caja'        	=> Trim($caja),
					'codArticulo'	=> Trim($codArticulo),
					'noSerie'		=> Trim($noSerie),
					'uniMed'		=> Trim($uniMed),
					'estado'		=> Trim($estado),
					'subcuenta'		=> Trim($subcuenta),
					'cantidad'		=> Trim($cantidad),
				);

			$detalle = $this->getCantidad($detalle,$datos);
		}
		//fin detalle

		//ingreso la cabecera y el detalle a un solo array
        $archivo = array (
			'cabecera' => $cabecera,
			'detalle'  => $detalle
		);

		//print_r($archivo);

		$contError = 0;
		//valido la cabecera
        foreach($archivo['cabecera'] as $cab) {
			if ($cab['autcod']==9999) {
				$contError = $contError + 1;
               	$respuesta[] = array (
				  	'err_code' => 2001,
				  	'err_msg'  => 'La persona que solicita no existe para la cuenta seleccionada' 
			   	);
			} elseif ($cab['cuenta']==9999) {
				$contError = $contError + 1;
				$respuesta[] = array (
				   'err_code' => 2001,
				   'err_msg'  => 'La cuenta no existe dentro del sistema' 
				);
			}elseif ($cab['mr']==9999) {
				$contError = $contError + 1;
				$respuesta[] = array (
				   'err_code' => 2001,
				   'err_msg'  => 'El numero de MR ya existe y no es permitido duplicar' 
				);
			}elseif ($cab['sitiEntrega']==9999) {
				$contError = $contError + 1;
				$respuesta[] = array (
				   'err_code' => 2001,
				   'err_msg'  => 'El Sitio de Entrega General no existe en el sistema' 
				);
			}elseif ($cab['hwtiposol']==9999) {
				$contError = $contError + 1;
				$respuesta[] = array (
				   'err_code' => 2001,
				   'err_msg'  => 'Ingreso un tipo de solicitud no valido' 
				);
			}

		}

		//valido el detalle
        foreach($archivo['detalle'] as $det) {
			$art = $det['autcod'];
			$pac = $det['autcod'];
			$caj = $det['autcod'];

			if ($det['autcod']==9999) {
				$contError = $contError + 1;
               	$respuesta[] = array (
				  	'err_code' => 2001,
				  	'err_msg'  => 'La persona que solicita no existe para la cuenta seleccionada',
			   	);
			}
		}

		$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) 
    			? $_SERVER['HTTP_X_FORWARDED_FOR'] 
				: $_SERVER['REMOTE_ADDR'];

		$archivoFinal = array (
			'cabecera' => $cabecera,
			'detalle'  => $detalle,
		);

		if($contError == 0){
			$respuesta[] = array (
				'err_code' => 2000,
				'err_msg'  => 'Documento Cargado con Exito' 
			);
			$db = $this->conectar(2);
			//inserto la cabecera
			foreach($archivoFinal['cabecera'] as $cab) {
				$sqltxt = "INSERT INTO TIGO.MRHW (
					HWMR, 
					TIPCODE,
					HWFECHASOL, 
					AUTCOD, 
					HWVIASOL, 
					SITID, 
					HWFECHAENTREGA,
					HWFDIGITASOL,
					HWDIGITASOL, 
					HWENTREGADO, 
					HWMRNO, 
					HWTIPOSOL, 
					MRHW_ESTADO,
					MRHW_USUSOL
					) 
					VALUES (
					'".(int)$cab['correlativo'] ."',
					'". $cab['cuenta'] ."',
					".$cab['fecSolicitud'].",
					'" . (int)$cab['autcod'] ."',
					'".$cab['viasol']."',
					'".$cab['sitiEntrega']."',
					".$cab['fecEntrega'].",
					SYSDATE,
					'". $cab['digitador'] ."',
					'0',
					'" .$cab['mr']. "',
					'".(int)$cab['hwtiposol']."',
					'0',
					'". $cab['digitador'] ."')";

					$query = $db->query($sqltxt);
			}

			//inserto la detalle
			$vcontLinea = 0;
			foreach($archivoFinal['detalle'] as $det) {
				if ($det['estado'] == 0){
					$estd = 'B';
				}else{
					$estd = 'M';
				}

				
				$vcontLinea ++;

				$sqltxt="INSERT INTO TIGO.DETMRHW (HWMR, TIPCODE, HWLINSOL, HWPACKING, HWCAJA, HWSOLCANT, HWLINEA, HWSOLEST, HWSOLAENT, HWMRDETSITID) 
							values(".trim($correlativo).","
									.trim($cuenta).","
									.$vcontLinea.","
									."'".$det['packingList']."',"
									.$det['caja'].","
									.$det['cantidadS'].","
									.$det['linea'].","
									."'".$estd."',"
									.$det['cantidadDes'].","
									.trim($sitioEntrega).")";
				$query = $db->query($sqltxt);
			}
		

		}

		$datosCarga[] = array(
			'NombreArchivo' => $name,
			'Ip'			=> $ip,
			'Ip2' 			=> $_SERVER['REMOTE_ADDR'],
			'fechaHora'		=> $fechaCarga,
			'login'			=> $user_id = $this->session->data['ausrid'],
			'archivoFinal'	=> $archivoFinal,
			'respuesta'		=> $respuesta,
		) ;

		//----------------------------------
		$contenido = "";
		$contenido .= json_encode($datosCarga)."\n";
		$fp = fopen(__DIR__."/logs/".$nomArchivo, "a");

		fputs($fp, $contenido);
		 
		fclose($fp);

		print_r($datosCarga);
		exit(0);
		return $respuesta;
	}
}