<?php
class ModelSolicitudSolicitud extends Model {

function getValue($valor){
	$retorno = 9998;
	if (!empty($valor)) {$retorno=$valor;}
	return $retorno;
}

function write_log($pfuncion,$sql,$respuesta,$error){
	
	// Constants
    $url = "https://lahacienda-25d57.firebaseio.com/webpicking.json";
	
    
	$fechaCarga	= $this->getFecHora();
	$Fec		= $this->getFec();
	
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) 
    			? $_SERVER['HTTP_X_FORWARDED_FOR'] 
				: $_SERVER['REMOTE_ADDR'];

	$Path = dirname( __FILE__ ); 	

	$datosCarga[] = array(
		'Ruta'			=> $Path,
		'Funcion'	 	=> $pfuncion,
		'Ip'			=> $ip,
		'Ip2' 			=> $_SERVER['REMOTE_ADDR'],
		'fechaHora'		=> $fechaCarga,
		'login'			=> $user_id = $this->session->data['ausrid'],
		'sql'			=> $sql,
		'respuesta'		=> $respuesta,
		'error'			=> $error
	) ;

	$nomArchivo	.= $Fec. "_Manual.json";
	$contenido = "";
	$contenido .= json_encode($datosCarga)."\n";	
	
	$url = "https://lahacienda-25d57.firebaseio.com/" . $nomArchivo;

	$ch = curl_init();
	Curl_setopt($ch,CURLOPT_URL,$url);
	Curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	Curl_setopt($ch,CURLOPT_POST,1);
	Curl_setopt($ch,CURLOPT_POSTFIELDS,$contenido);
	Curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/plain'));
	
	$response = curl_exec( $ch);
	
	if( curl_errno($ch)){
		echo 'Error: '.curl_errno($ch);
	}else{
		//echo "Ya inserto";
	} 
	
	$fp = fopen(__DIR__."/../monitoreo/".$nomArchivo, "a");

	fputs($fp, $contenido);
	 
	fclose($fp);
}

function getFecHora(){
	
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	$sql = "SELECT
			TO_CHAR(SYSDATE,'ddmmyyyy_HH24miss') AS FECHAHORA
			FROM DUAL";
	$query = $db->query($sql);
	$FecHors = $query->row['FECHAHORA'];
	return $FecHors;
}

function getFec(){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	$sql = "SELECT
			TO_CHAR(SYSDATE,'ddmmyyyy') AS FECHAHORA
			FROM DUAL";
	$query = $db->query($sql);
	$FecHors = $query->row['FECHAHORA'];
	return $FecHors;
}

function getValidaMdr($sol,$hwmr){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	
	$sql = "SELECT HWMR 
	          FROM TIGO.MRHW
			 where TIPCODE = " . $tipcode . "
			   AND TRIM(HWMRNO) = '".$sol."'
			   and hwmr        != '".$hwmr."'";

	$query = $db->query($sql);

	$hwmr = null;

	if($query->rows){
		$hwmr = $query->row['HWMR'];
	} else {
		$hwmr = 9999999999;
	}

	$error = $query->errormsg['message'];
	$this->write_log("getValidaMdr",$sql,$query,$error);

	return $hwmr;
}

function getCorrLinea($mdr){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	$sql = "SELECT (COUNT(*) + 1) AS CONTADOR FROM TIGO.DETMRHW WHERE TIPCODE = " . $tipcode . " AND HWMR = '" . $mdr . "'";
	$query = $db->query($sql);
	$contador = $query->row['CONTADOR'];
	
	$error = $query->errormsg['message'];
	$this->write_log("getCorrLinea",$sql,$query,$error);

	return $contador;
}

function getSitio($sitio){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	
	$sql = "SELECT SITID FROM TIGO.SITIOS WHERE SITNOM = '" . $sitio . "'";
	$query = $db->query($sql);
	
	$sitid=null;
	
	if ($query->rows) {
	  $sitid = $query->row['SITID'];	
	}
 	else {
		$sitid = 9999;
	}
	
	$error = $query->errormsg['message'];
	$this->write_log("getSitio",$sql,$query,$error);
	
	return $sitid;
}

function getVehiculos(){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];

	$sql = "SELECT
    			VEHI_CODIGO
    			,VEHI_NOMBRE
			FROM TIGO.VEHICULOS
			ORDER BY VEHI_CODIGO ASC";

	$query = $db->query($sql);

	$error = $query->errormsg['message'];
	$cosa = $query->rows;



	foreach ( $cosa as $valor) {
		//$dato => $valor['VEHI_CODIGO'];
		$respuesta[] = array (
			'nombre'   =>  $valor['VEHI_NOMBRE'],
			'valor'    =>  $valor['VEHI_CODIGO']
		);
	}

	$this->write_log("getVehiculos",$sql,$query,$error);
	return $respuesta;
}

function getEmpresa(){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];

	$sql = "SELECT
    			Empid
    			,Tipcode
				,Empnombre
				,Emprtn
			FROM TIGO.Empresaretira
			ORDER BY Empid ASC";

	$query = $db->query($sql);
	$cosa = $query->rows;

	foreach ( $cosa as $valor) {
		//$dato => $valor['VEHI_CODIGO'];
		$respuesta[] = array (
			'nombre'   =>  $valor['EMPNOMBRE'],
			'valor'    =>  $valor['EMPID']
		);
	}

	$error = $query->errormsg['message'];
	$this->write_log("getEmpresa",$sql,$query,$error);

	return $respuesta;

}

function getPercod(){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	$sql = "SELECT (count(*) + 1) as contador FROM tigo.personaretira";
	$query = $db->query($sql);
	$contador = $query->row['CONTADOR'];
	
	$error = $query->errormsg['message'];
	$this->write_log("getPercod",$sql,$query,$error);

	return $contador;	
}

function ActualizaRtnEmpresa($datos){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];

	$sql = "Update tigo.EMPRESARETIRA set EMPRTN = '" .$datos['rtnemp']."' Where  empid = " .$datos['empresa'];
	$query = $db->query($sql);
	
	$error = $query->errormsg['message'];

	$this->write_log("ActualizaRtnEmpresa",$sql,$query,$error);

	return $datos['rtnemp'];
}

function getRtn($datos){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];

	$sql = "SELECT
    			PERCOD
			FROM tigo.personaretira
    		WHERE 1=1
				AND empid = " . $datos['empresa'] . "
				AND tipcode = " . $tipcode ."
				AND PERRTN = '".$datos['rtn']."'";
	$query = $db->query($sql);

	$percod = null;

	if ($query->rows) {
		$percod = $query->row['PERCOD'];	
	}
	else {
		$percod = $this->getPercod();
		$sqla = "INSERT INTO TIGO.PERSONARETIRA (PERCOD, EMPID, TIPCODE, PERNOMBRE, PERRTN) 
			VALUES (".$percod.", '".$datos['empresa']."', '".$tipcode."', '".$datos['perrecibe']."', '".$datos['rtn']."')";
		$query = $db->query($sqla);
		$error = $query->errormsg['message'];
		$this->write_log("getRtn",$sqla,$query,$error);
	}

	$error = $query->errormsg['message'];
	$this->write_log("getRtn_2",$sql,$query,$error);

	return $percod;
}

function getAutorizado($autorizado){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	
	$sql = "SELECT AUTCOD FROM TIGO.AUTORIZADOS WHERE TIPCODE = " . $tipcode . " AND AUTNOMBRE = '" . $autorizado . "'";
	$query = $db->query($sql);
	
	$autcod=null;
	
	if ($query->rows) {
	  $autcod = $query->row['AUTCOD'];	
	}
 	else {
		$autcod = 9999;
	}

	$error = $query->errormsg['message'];
	$this->write_log("getAutorizado",$sql,$query,$error);
	
	return $autcod;
}

function getSubCuenta($sub){
	$db = $this->conectar($this->session->data['conexion']);
	$tipcode = $this->session->data['tipinv'];
	
	$sql = "SELECT TIGOSUBCTA_CODE FROM TIGO.TIGO_SUBCUENTA WHERE TIPCODE = " . $tipcode . " AND TIGOSUBCTA_DESCRIP = '" . $sub . "'";

	$query = $db->query($sql);
	
	$subCuenta=null;
	
	if ($query->rows) {
	  $subCuenta = $query->row['TIGOSUBCTA_CODE'];	
	}
 	else {
		$subCuenta = 9999;
	}

	$error = $query->errormsg['message'];
	$this->write_log("getSubCuenta",$sql,$query,$error);
	
	return $subCuenta;
}

function autoCompleteSolicitudes($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "
	SELECT HWMRNO
        ,HWFECHASOL
        ,HWFECHAENTREGA
        ,HWENTREGADO
        ,MRHW_ESTADO
    FROM ( 
      SELECT HWMRNO
            ,TO_CHAR(HWFECHASOL,'DD/MM/YYYY HH24:MI:SS') AS HWFECHASOL
            ,TO_CHAR(HWFECHAENTREGA,'DD/MM/YYYY HH24:MI:SS') AS HWFECHAENTREGA
            ,HWENTREGADO
            ,MRHW_ESTADO
            ,ROW_NUMBER() over (order by HWMRNO) R
		FROM TIGO.MRHW Where 1=1 and TIPCODE = " . $tipcode ;
		if (isset($data['filter_mdr'])) {
			$sql .= " AND HWMRNO LIKE '" . $data['filter_mdr'] . "%' ";
		  }
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						

	$query = $db->query($sql); 

	$error = $query->errormsg['message'];
	$this->write_log("autoCompleteSolicitudes",$sql,$query,$error);
	
	return $query->rows;
}

function autoCompleteCant($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);
	$subcuenta = $this->getSubCuenta($data['subcuenta']);
	$ppack = $this->getValue($data['hwpacking']);
	$pcaja = $this->getValue($data['hwcaja']);
	$pcodArticulo = $this->getValue($data['hwartcod']);
	$estd = $this->getValue($data['hwsolest']);

	if ($estd == 'B'){
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
				$error = $querym->errormsg['message'];
				$this->write_log("autoCompleteCant",$sqlm,$querym,$error);
				$vmax 	= $querym->row['MAXIMO'];

		$sql = " SELECT HWPACKING,
		HWCAJA,
		HWLINEA,
		HWARTCOD,
		HWRESERVADO,
		HWDESPBUEN,
		HWRECBUEN,
		(HWRECBUEN - HWDESPBUEN - HWRESERVADO) AS DISPONIBLE
		FROM ( 
		SELECT  HWPACKING,
					HWCAJA,
					HWLINEA,
					HWARTCOD,
					HWRESERVADO,
					HWDESPBUEN,
					HWRECBUEN,
					(HWRECBUEN - HWDESPBUEN - HWRESERVADO) AS DISPONIBLE
				,ROW_NUMBER() over (order by HWARTCOD) R
			FROM TIGO.DETINGHW Where 1=1
			AND (HWRECBUEN - HWDESPBUEN - HWRESERVADO) >= 1 ";
			if ($data['subcuenta'] <> '') {
				$sql .= " AND TIGOSUBCTA_CODE = '" . $subcuenta . "' ";
			}
			if ($ppack <> 9998) {
				$sql .= " AND HWPACKING = '" . $data['hwpacking'] . "' ";
			}
			if (isset($data['hwartcod'])) {
				$sql .= " AND HWARTCOD = '" . $data['hwartcod'] . "' ";
			}
			if ($pcaja <> 9998) {
				$sql .= " AND HWCAJA = '" . $data['hwcaja'] . "' ";
			}
			
		$sql .= ")
		where R between ".$data['start']. " and " .$data['limit']. "
		";				
	} else {
		$sqlm = "SELECT NVL(MAX(HWRECMAL - HWDESPMAL - HWRESERVMAL),0) AS MAXIMO 
				FROM TIGO.DETINGHW  
					WHERE 1=1 AND (HWRECMAL - HWDESPMAL - HWRESERVMAL) > 0";
					if ($ppack <> 9998){
						$sqlm .= " AND HWPACKING = '".$ppack."' ";
					}
					if ($pcaja <> 9998){
						$sqlm .= " AND HWCAJA = ".$pcaja." ";
					} 
					$sqlm .= " AND HWARTCOD = '".$pcodArticulo."' ";

				$querym = $db->query($sqlm);
				$error = $querym->errormsg['message'];
				$this->write_log("autoCompleteCant_2",$sqlm,$querym,$error);
				$vmax 	= $querym->row['MAXIMO'];

		$sql = " SELECT HWPACKING,
		HWCAJA,
		HWLINEA,
		HWARTCOD,
		HWRESERVMAL,
		HWDESPMAL,
		HWRECMAL,
		(HWRECMAL - HWDESPMAL - HWRESERVMAL) AS DISPONIBLE
		FROM ( 
		SELECT  HWPACKING,
					HWCAJA,
					HWLINEA,
					HWARTCOD,
					HWRESERVMAL,
					HWDESPMAL,
					HWRECMAL,
					(HWRECMAL - HWDESPMAL - HWRESERVMAL) AS DISPONIBLE
				,ROW_NUMBER() over (order by HWARTCOD) R
			FROM TIGO.DETINGHW Where 1=1
			AND (HWRECMAL - HWDESPMAL - HWRESERVMAL) >= 1 ";
			if ($ppack <> 9998) {
				$sql .= " AND HWPACKING = '" . $data['hwpacking'] . "' ";
			}
			if ($data['subcuenta'] <> '') {
				$sql .= " AND TIGOSUBCTA_CODE = '" . $subcuenta . "' ";
			}
			if (isset($data['hwartcod'])) {
				$sql .= " AND HWARTCOD = '" . $data['hwartcod'] . "' ";
			}
			if ($pcaja <> 9998) {
				$sql .= " AND HWCAJA = '" . $data['hwcaja'] . "' ";
			}
			
		$sql .= ")
		where R between ".$data['start']. " and " .$data['limit']. "
		";				
	}
			
	$query = $db->query($sql);
	
	$error = $query->errormsg['message'];
	$this->write_log("autoCompleteCant_3",$sql,$query,$error);

	return $query->rows;
}

function autoCompletePack($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$ppack = $this->getValue($data['hwpacking']);
	//$pcaja = $this->getValue($data['hwcaja']);
	$pcodArticulo = $this->getValue($data['hwartcod']);

	$sql = " SELECT TRIM(HWPACKING) AS HWPACKING,
	HWCAJA,
	HWLINEA,
	HWARTCOD,
	HWRESERVADO
    FROM ( 
      SELECT  TRIM(HWPACKING) as HWPACKING,
				HWCAJA,
				HWLINEA,
				HWARTCOD,
				HWRESERVADO
            ,ROW_NUMBER() over (order by HWARTCOD) R
		FROM TIGO.DETINGHW Where 1=1
		AND (HWRECBUEN - HWDESPBUEN - HWRESERVADO) > 0 " ;
		if ($ppack <> 9998) {
			$sql .= " AND HWPACKING LIKE '" . $data['hwpacking'] . "%' ";
		  }
		if ($pcodArticulo <> 9998) {
			$sql .= " AND HWARTCOD = '" . $data['hwartcod'] . "' ";
		  }
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						

	$query = $db->query($sql); 
	$error = $query->errormsg['message'];
	$this->write_log("autoCompletePack",$sql,$query,$error);
	return $query->rows;
}

function autoCompleteArticulos($data){
	$tipcode = $this->session->data['tipinv'];
	$subcuenta = $this->getSubCuenta($data['subcuenta']);
	$db = $this->conectar($this->session->data['conexion']);
	

	$sql = " SELECT TRIM(HWARTCOD) AS HWARTCOD
        ,TRIM(HWARTDESC) AS HWARTDESC
    FROM ( 
      SELECT TRIM(A.HWARTCOD) AS HWARTCOD
            ,TRIM(A.HWARTDESC) AS HWARTDESC
            ,ROW_NUMBER() over (order by A.HWARTCOD) R
		FROM TIGO.CATALOGOHW A, TIGO.DETINGHW B, TIGO.INGRESOHW C
		 Where 1=1 
		 AND A.HWARTCOD = B.HWARTCOD
    	AND B.HWPACKING = C.HWPACKING
    	AND C.TIPCODE = " . $tipcode ;
		if ($data['hwartcod'] <> '') {
			$sql .= " AND A.HWARTCOD LIKE '" . $data['hwartcod'] . "%' ";
		}
		if ($data['serie'] <> '') {
			$sql .= " AND B.HWSERIE = '" . $data['serie'] . "' ";
		}
		if ($data['subcuenta'] <> '') {
			$sql .= " AND B.TIGOSUBCTA_CODE = '" . $subcuenta . "' ";
		}
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						
	
	//echo $sql;
	$query = $db->query($sql);
	
	$error = $query->errormsg['message'];
	$this->write_log("autoCompleteArticulos",$sql,$query,$error);

	return $query->rows;
}

function autoCompleteSitio($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = " SELECT  SITID,TRIM(SITNOM) AS SITNOM
    FROM ( 
      SELECT  SITID,TRIM(SITNOM) AS SITNOM
            ,ROW_NUMBER() over (order by SITNOM) R
		FROM TIGO.SITIOS Where 1=1 " ;
		if (isset($data['hwsitio'])) {
			$sql .= " AND SITNOM LIKE '" . $data['hwsitio'] . "%' ";
		  }
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						

	$query = $db->query($sql); 
	$error = $query->errormsg['message'];
	$this->write_log("autoCompleteSitio",$sql,$query,$error);
	return $query->rows;
}

function autoCompleteSubCuenta($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = " SELECT DISTINCT TIGOSUBCTA_CODE,TRIM(TIGOSUBCTA_DESCRIP) AS TIGOSUBCTA_DESCRIP
    FROM ( 
      SELECT  A.TIGOSUBCTA_CODE,TRIM(A.TIGOSUBCTA_DESCRIP) AS TIGOSUBCTA_DESCRIP
            ,ROW_NUMBER() over (order by TIGOSUBCTA_DESCRIP) R
		FROM TIGO.TIGO_SUBCUENTA A,TIGO.DETINGHW B Where 1=1 
		 AND A.TIGOSUBCTA_CODE = B.TIGOSUBCTA_CODE
		AND A.TIPCODE = " . $tipcode;
		if (isset($data['subcuenta'])) {
			$sql .= " AND A.TIGOSUBCTA_DESCRIP LIKE '" . $data['subcuenta'] . "%' ";
		  }
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						
	//echo $sql;
	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("autoCompleteSubCuenta",$sql,$query,$error); 
	return $query->rows;
}

function autoSerie($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = " SELECT DISTINCT HWSERIE
    FROM ( 
      SELECT  A.HWSERIE 
            ,ROW_NUMBER() over (order by A.HWSERIE) R
		FROM  TIGO.DETINGHW A, TIGO.INGRESOHW B, TIGO.TIGO_SUBCUENTA C
		 Where 1=1 AND A.HWPACKING = B.HWPACKING 
		 AND C.TIGOSUBCTA_CODE = A.TIGOSUBCTA_CODE
		 AND B.TIPCODE = " . $tipcode;
		if (isset($data['serie'])) {
			$sql .= " AND A.HWSERIE LIKE '" . $data['serie'] . "%' ";
		}
		if ($data['subcuenta'] <> '') {
			$sql .= " AND C.TIGOSUBCTA_DESCRIP = '" . $data['subcuenta'] . "' ";
		}
	$sql .= ")
       where R between ".$data['start']. " and " .$data['limit']. "
	";						

	$query = $db->query($sql); 

	$error = $query->errormsg['message'];
	$this->write_log("autoSerie",$sql,$query,$error);

	return $query->rows;
}

public function addMdr($data) {
	$tipcode 	= $this->session->data['tipinv'];
	$tipdesce 	= $this->session->data['tipdesce'];
	$autcod		= $this->getAutorizado($data['autonombre']);
	$db = $this->conectar($this->session->data['conexion']);

	$busPersona = array(
		'empresa' 	=> $data['empresa'],
		'perrecibe' => $data['perrecibe'],
		'rtn' 		=> $data['rtn']
	);

	$registraRTNempresa = array(
		'empresa' 	=> $data['empresa'],
		'rtnemp'	=> $data['rtnemp']
	);

	$rtn = $this->getRtn($busPersona);
	$rtnemp = $this->ActualizaRtnEmpresa($registraRTNempresa);

	$sqlm = "SELECT * FROM TIGO.MRHW WHERE TIPCODE = ".$tipcode." AND HWMR = " . $data['hwmr'];
	$query = $db->query($sqlm);

	$error = $query->errormsg['message'];
	$this->write_log("addMdr",$sqlm,$query,$error);

	if($query->rows){
		$sql = "UPDATE TIGO.MRHW SET 
			HWFECHASOL = SYSDATE, 
			AUTCOD = ". $autcod .", 
			HWVIASOL = '".$data['hwviasol']."', 
			SITID = 1, 
			HWFECHAENTREGA = to_date('".$data['hwfechaentrega']."','dd/mm/yyyy')".",
			HWFDIGITASOL = SYSDATE,
			HWDIGITASOL = '". $tipdesce ."', 
			HWENTREGADO = 0, 
			HWMRNO = '" .$data['hwmrno'] . "', 
			HWTIPOSOL = '".(int)$data['hwtipsol']."', 
			MRHW_ESTADO = 0,
			MRHW_USUSOL = '". $tipdesce ."' 
			EMPID = '". $data['empresa'] ."' 
			PERCOD = '". $rtn ."' 
			VEHI_CODIGO = '". $data['vehiculo'] ."' 
			VEHI_PLACA = '". $data['placa'] ."' 
		WHERE HWMR = ". $data['hwmr']." AND TIPCODE = " .$tipcode;
	} else {
		$sql = "INSERT INTO TIGO.MRHW (
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
			HWTIPOSOL, 
			MRHW_ESTADO,
			MRHW_USUSOL,
			EMPID,
			PERCOD,
			VEHI_CODIGO,
			VEHI_PLACA
			) 
			VALUES (
			'".(int)$data['hwmr'] ."',
			'". $tipcode ."',
			SYSDATE,
			'" . $autcod ."',
			'W',
			'1',
			to_date('".$data['hwfechaentrega']."','dd-mm-yyyy HH24:MI')".",
			SYSDATE,
			Trim('". $tipdesce ."'),
			'0',
			'".(int)$data['hwtipsol']."',
			'0',
			Trim('". $tipdesce ."'),
			'" .$data['empresa'] . "',
			'" .$rtn. "',
			'" .$data['vehiculo'] . "',
			'" .$data['placa'] . "')";
	}
	//echo $sql;
	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("addMdr_2",$sql,$query,$error);
	$this->session->data['operacion']='edit';
	$this->session->data['hwmr'] = $data['hwmr'];
}

public function FinalizaMdr($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "UPDATE tigo.mrhw SET mrhw_estado = 1 WHERE hwmr = " . $data;
	$query = $db->query($sql);

	$error = $query->errormsg['message'];
	$this->write_log("FinalizaMdr",$sql,$query,$error);
}

public function putDetMdr($data){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$hwmr = $data['hwmr'];
	$lineadet = $data['lineadet'];
	$std = $data['hwsolest'];

	$sqlb = "";
	$sqlb = "SELECT HWPACKING,HWCAJA,HWLINEA,HWSOLAENT FROM TIGO.DETMRHW WHERE HWMR = ".$hwmr." AND TIPCODE = ".$tipcode." AND HWLINSOL = " .$lineadet;
	$query = $db->query($sqlb);

	$error = $query->errormsg['message'];
	$this->write_log("putDetMdr",$sqlb,$query,$error);

	if($query->rows){
		$hwpacking = $query->row['HWPACKING'];
		$hwcaja    = $query->row['HWCAJA'];
		$hwlinea   = $query->row['HWLINEA'];
		$hwsolaent = $query->row['HWSOLAENT'];
	}

	$sqlm = "";
	$sqlm = "DELETE FROM TIGO.DETMRHW WHERE TIPCODE = ".$tipcode." AND HWMR = '" . $data['hwmr'] . "' AND HWLINSOL = '" . $data['lineadet'] . "'";

	$query = $db->query($sqlm);

	$error = $query->errormsg['message'];
	$this->write_log("putDetMdr_2",$sqlm,$query,$error);

	$sql = "";
    if ($hwsolaent) {
		if($std == 'B'){
			$sql = "UPDATE TIGO.DETINGHW SET HWRESERVADO = HWRESERVADO - " . $hwsolaent . " WHERE HWPACKING = '".$hwpacking."' AND HWCAJA = '" . $hwcaja . "' AND HWLINEA = '" . $hwlinea."'";
		} else {
			$sql = "UPDATE TIGO.DETINGHW SET HWRESERVMAL = HWRESERVMAL - " . $hwsolaent . " WHERE HWPACKING = '".$hwpacking."' AND HWCAJA = '" . $hwcaja . "' AND HWLINEA = '" . $hwlinea."'";
		}
	}

	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("putDetMdr_3",$sql,$query,$error);

}

public function postDetMdr($data){
	$tipcode 	= $this->session->data['tipinv'];
	$tipdesce 	= $this->session->data['tipdesce'];
	$corr		= $this->getCorrLinea($data['hwmr']);
	$sitnom		= $this->getSitio($data['sitnom']);
	$std 		= $data['hwsolest'];
	$db = $this->conectar($this->session->data['conexion']);
	
	$sql = "INSERT INTO TIGO.DETMRHW (HWMR, 
		TIPCODE, 
		HWLINSOL, 
		HWPACKING, 
		HWCAJA, 
		HWSOLCANT, 
		HWLINEA, 
		HWSOLEST, 
		HWSOLAENT, 
		HWMRDETSITID) 
	VALUES (".(int)$data['hwmr'] .",
		 ". $tipcode .", 
		 ".(int)$corr .", 
		 '" .$data['hwpacking'] . "',
		 ".(int)$data['hwcaja'] .",
		 ".(int)$data['hwsolcant'] .",
		 ".(int)$data['hwlinea'] .",
		 '".$data['hwsolest'] ."',
		 '".(int)$data['hwsolaent'] ."',
		 ".$sitnom .")";

	$query = $db->query($sql);

	$error = $query->errormsg['message'];
	$this->write_log("postDetMdr",$sql,$query,$error);

	$sql = "";
	if($std == 'B'){
		$sql = "UPDATE TIGO.DETINGHW SET HWRESERVADO = HWRESERVADO + " . $data['hwsolaent'] . " WHERE HWPACKING = '".$data['hwpacking']."' AND HWCAJA = " . $data['hwcaja'] . " AND HWLINEA = " . $data['hwlinea'];
	} else {
		$sql = "UPDATE TIGO.DETINGHW SET HWRESERVMAL = HWRESERVMAL + " . $data['hwsolaent'] . " WHERE HWPACKING = '".$data['hwpacking']."' AND HWCAJA = " . $data['hwcaja'] . " AND HWLINEA = " . $data['hwlinea'];
	}

	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("postDetMdr_2",$sql,$query,$error);
}

public function editMdr($data) {
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "UPDATE TIGO.MRHW 
	           SET	AUTCOD = " . (int)$data['autcod'] ."
				,HWMRNO = '" .$data['hwmrno'] . "'
				,HWFECHAENTREGA = " ."to_date('".$data['hwfechaentrega']."','dd/mm/yyyy')"."
				,HWVIASOL = '".$data['hwviasol']."'
				,HWTIPOSOL = ".(int)$data['hwtipsol']."
			WHERE HWMR = ".(int)$data['hwmr'] ."
			 AND TIPCODE = ". $tipcode;

	$query = $db->query($sql);

	$error = $query->errormsg['message'];
	$this->write_log("editMdr",$sql,$query,$error);
}

public function getCorrelativo(){

	$tipdesce = $this->session->data['tipdesce'];

	$db = $this->conectar($this->session->data['conexion']);

	$sql = "SELECT NVL(CORNUM,0) + 1 AS CORR 
			FROM TIGO.CORRELATIVOS 
			WHERE CORDESC = '" . $tipdesce . "' ";

	$query = $db->query($sql);

	$corr = $query->row['CORR'];

	$sql = "UPDATE TIGO.CORRELATIVOS SET CORNUM = ". $corr ." WHERE CORDESC = '" . $tipdesce . "'";
	$query = $db->query($sql);

	$error = $query->errormsg['message'];
	$this->write_log("getCorrelativo",$sql,$query,$error);

	return $corr;
}

public function getAutcodUsername($autcod){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);
	$sql = "";
	$sql = "SELECT AUTCOD
			,TIPCODE
			,AUTNOMBRE 
			FROM TIGO.AUTORIZADOS 
			WHERE 1=1 
				AND TIPCODE = " . $tipcode . "
				and AUTCOD = " .(int)$autcod;
	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("getAutcodUsername",$sql,$query,$error);
}

public function getMdr($hwmr) {
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);
	$sql = "";
	$sql = "SELECT A.HWMR
		,A.TIPCODE
		,A.AUTCOD
		,B.AUTNOMBRE
		,A.HWMRNO
		,A.HWFECHAENTREGA
		,A.HWFDIGITASOL
		,A.HWVIASOL
		,A.HWTIPOSOL
		,A.MRHW_ESTADO 
		,A.MRHW_USUSOL
		,A.EMPID
		,A.PERCOD
		,A.VEHI_CODIGO
		,A.VEHI_PLACA
		,C.PERNOMBRE
    	,C.PERRTN
		,D.EMPRTN
		FROM TIGO.MRHW A
			INNER JOIN TIGO.AUTORIZADOS B ON A.AUTCOD = B.AUTCOD AND A.TIPCODE = B.TIPCODE
			INNER JOIN TIGO.PERSONARETIRA C ON A.PERCOD = C.PERCOD
			INNER JOIN TIGO.EMPRESARETIRA D ON A.EMPID = D.EMPID
		WHERE 1=1 
		AND A.TIPCODE = ". $tipcode . "
		AND A.HWMR = " . (int)$hwmr ;

	$query = $db->query($sql);
	//echo "\n";
	//echo $sql;
	//echo "\n";
	$error = $query->errormsg['message'];
	$this->write_log("getMdr",$sql,$query,$error);
	return $query->row;
}

public function getDetMdr($hwmr){
	$tipcode = $this->session->data['tipinv'];
	$db = $this->conectar($this->session->data['conexion']);
	$sql = "";
	$sql = "SELECT D.HWARTCOD
	              ,B.hwlinsol
	              ,E.HWARTDESC,B.HWLINEA,B.HWPACKING,B.HWCAJA,B.HWSOLCANT,B.HWSOLEST,B.HWSOLAENT,C.SITNOM
				FROM TIGO.MRHW A 
					INNER JOIN TIGO.DETMRHW B ON A.HWMR = B.HWMR AND A.TIPCODE = B.TIPCODE
					INNER JOIN TIGO.SITIOS C ON B.HWMRDETSITID = C.SITID
					INNER JOIN TIGO.DETINGHW D ON B.HWPACKING = D.HWPACKING AND B.HWCAJA = D.HWCAJA AND B.HWLINEA = D.HWLINEA
					INNER JOIN TIGO.CATALOGOHW E ON D.HWARTCOD = E.HWARTCOD 
				WHERE 1=1
					AND A.HWMR = " . (int)$hwmr . "
					AND A.TIPCODE = ". $tipcode . "
				ORDER BY B.hwlinsol";

	$query = $db->query($sql);
	$error = $query->errormsg['message'];
	$this->write_log("getDetMdr",$sql,$query,$error);
	return $query->rows;
}

public function getCuenta($data) {
	$db = $this->conectar($this->session->data['conexion']);

	$sql = "SELECT A.TIPINV,A.NOMUSU,B.TIPDESCE, A.TIPINV || ' - ' || B.TIPDESCE AS CUENTA 
				FROM TIGO.USUARIOSWEB A 
				INNER JOIN TIGO.TIPTRAE B ON A.TIPINV = B.TIPCODE 
				WHERE A.CODUSUW = '".$data['user_id']."'";

	$query = $db->query($sql);

    $respuesta = array (
	   'tipinv'   =>  $query->row['TIPINV'],
	   'tipdesce' =>  $query->row['TIPDESCE'],
	   'cuenta'	  =>  $query->row['CUENTA'],
	   'nomusu'	  =>  $query->row['NOMUSU']
	);    

	$error = $query->errormsg['message'];
	$this->write_log("getCuenta",$sql,$query,$error);

	return $respuesta;
}

function obtenerSolicitudes($data) {
	$tipcode = $this->session->data['tipinv'];
   	$db = $this->conectar($this->session->data['conexion']);
	//query que muestra como paginar a nivel de oracle						  
    $sql = "	
    SELECT HWMR,HWMRNO
        ,HWFECHASOL
		,HWFECHAENTREGA
		,decode(FENTREGA,'00010101000000',null,FENTREGA) FENTREGA
        ,HWENTREGADO
        ,MRHW_ESTADO
		,status
    FROM ( 
      SELECT HWMR,HWMRNO
            ,TO_CHAR(HWFECHASOL,'DD/MM/YYYY HH24:MI:SS') AS HWFECHASOL
			,TO_CHAR(HWFECHAENTREGA,'DD/MM/YYYY HH24:MI:SS') AS HWFECHAENTREGA
			,TO_CHAR(HWFECHAENTREGA, 'YYYYMMDDHH24') || '0000' AS FENTREGA
            ,HWENTREGADO
            ,MRHW_ESTADO
			,getestadomr(HWFECHAENTREGA,HWENTREGADO) status
			,ROW_NUMBER() over (";
			$sort_data = array(
				'HWMR',
				'HWFECHASOL',
				'HWFECHAENTREGA'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
						$sql .= " ORDER BY " . $data['sort'];
			} else {
						$sql .= " ORDER BY HWMR";
			}
	
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
						$sql .= " DESC";
			} else {
						$sql .= " ASC";
			}
		
			$sql .= ") R
			FROM TIGO.MRHW WHERE 1=1 and TIPCODE = ". $tipcode;
			//Agrega filtro de busqueda por MDR
			if (isset($data['filter_cormdr'])) {
				$sql .= " AND HWMR LIKE '" . $data['filter_cormdr'] . "%' ";
			}
			//Agrega filtro de busqueda por No. MDR
			if (isset($data['filter_mdr'])) {
				$sql .= " AND HWMRNO LIKE '" . $data['filter_mdr'] . "%' ";
			}
			//Agrega filtro del estado
			if (isset($data['filter_estado'])){
				$sql .= " AND MRHW_ESTADO = " . $data['filter_estado'];
			} 
			//Agrega filtro de rango de fechas . $this->db->escape($data['filter_date_end']) . 
			if (isset($data['filter_starttime'])){
				$sql .= " AND TRUNC(HWFECHASOL) >= to_date('" . $data['filter_starttime']. "','dd-mm-yyyy')";
			}
			if (isset($data['filter_endtime'])){
				$sql .= " AND TRUNC(HWFECHASOL) <= to_date('" . $data['filter_endtime']. "','dd-mm-yyyy')";
			} 
			
			if (isset($data['filter_status'])){

				if ($data['filter_status']==1) {$valor='Ingresado';}
				elseif ($data['filter_status']==2) {$valor='Finalizado';}
                elseif ($data['filter_status']==3) {$valor='En Proceso';}
				elseif ($data['filter_status']==4) {$valor='Por vencer';}
				elseif ($data['filter_status']==5) {$valor='Demorado';}
				else {
				 $valor=null;	
				}	
                if ($valor) { 
				  $sql .= " AND getestadomr(HWFECHAENTREGA,HWENTREGADO) = '".$valor."'";
				}
			}
			
			$sql .= " )
	   	WHERE 1=1  ";
	   
	   if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
							$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
							$data['limit'] = 20;
			}

			$sql .= " AND R BETWEEN " . ((int)$data['start'] +1). " AND " .( (int)$data['limit'] + (int)$data['start']);
		}

	$query = $db->query($sql);
	
	
	$error = $query->errormsg['message'];
	$this->write_log("obtenerSolicitudes",$sql,$query,$error);
	$this->session->data['hwmr'] = 0;
	return $query->rows;	
}

function totalSolicitudes($data) {
	$tipcode = $this->session->data['tipinv'];
    $db = $this->conectar($this->session->data['conexion']);
                           
 $sql = "	
     SELECT 
        Count(*) As TOTAL
	 FROM TIGO.MRHW
	 WHERE 1=1 
	 AND TIPCODE = " . $tipcode;
	if (isset($data['filter_mdr'])) {
		$sql .= " AND HWMRNO LIKE '" . $data['filter_mdr'] . "%' ";
	  }
	  //Agrega filtro de rango de fechas . $this->db->escape($data['filter_date_end']) . 
	if (isset($data['filter_starttime'])){
		$sql .= " AND TRUNC(HWFECHASOL) >= to_date('" . $data['filter_starttime']. "','dd-mm-yyyy')";
	}
	
				//Agrega filtro del status
			if (isset($data['filter_status'])){

				if ($data['filter_status']==1) {$valor='Ingresado';}
				elseif ($data['filter_status']==2) {$valor='Finalizado';}
                elseif ($data['filter_status']==3) {$valor='En Proceso';}
				elseif ($data['filter_status']==4) {$valor='Por vencer';}
				elseif ($data['filter_status']==5) {$valor='Demorado';}
				else {
				 $valor=null;	
				}	
                if ($valor) { 
				  $sql .= " AND getestadomr(HWFECHAENTREGA,HWENTREGADO) = '".$valor."'";
				}
			} 
			
	
	if (isset($data['filter_endtime'])){
		$sql .= " AND TRUNC(HWFECHASOL) <= to_date('" . $data['filter_endtime']. "','dd-mm-yyyy')";
	} 
	$sql .= " ";

	 $query = $db->query($sql);
	 $error = $query->errormsg['message'];
	$this->write_log("totalSolicitudes",$sql,$query,$error);

     return $query->row['TOTAL'];
     
}

}