<?php
class ModelVasBroadband extends Model {

function conectar($cod_ambiente) {
	/*
	  1: PRODUCCION
	  2: DESARROLLO
	*/
	if ($cod_ambiente==1) {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);
    } 
	else {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_DESA
							  , DB_USERNAME_DESA
							  , DB_PASSWORD_DESA
							  , DB_SERVICE_NAME_DESA
							  , DB_PORT_DESA);	
    }
	return $db;
}

function encontroPaquete($parametros,$cod_paquete) {

$db = $this->conectar($parametros['filter_cod_ambiente']);		

$sqltext = "select to_char(fecha_compra,'dd-mm-yyyy hh24:mi:ss')fecha_compra,cod_paquete,servicioallot,message_seq
              from broadband.venta_paquete
			 where cod_paquete   = '".$cod_paquete."'
			   and fecha_compra >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')
               and numero_celular = '".$parametros['filter_NNNN']."'";	

 $query = $db->query($sqltext);
 
 if ($query->num_rows>0) {
 $info = array(
      'fecha_compra'   => $query->row['FECHA_COMPRA'],
	  'cod_paquete'    => $query->row['COD_PAQUETE'],
	  'servicioallot'  => $query->row['SERVICIOALLOT'],
	  'message_seq'    => $query->row['MESSAGE_SEQ']
 );
 }
 else
 {
  $info=null;	 
 }
 return $info;
}

function asignoPaquete($parametros) {

$db = $this->conectar($parametros['filter_cod_ambiente']);		

$sqltext = "select to_char(fecha_compra,'dd-mm-yyyy hh24:mi:ss')fecha_compra,cod_paquete,servicioallot,message_seq
              from broadband.venta_paquete
			 where fecha_compra >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')
               and numero_celular = '".$parametros['filter_NNNN']."'";	

 $query = $db->query($sqltext);
 
 if ($query->num_rows>0) {
 $info = array(
      'fecha_compra'   => $query->row['FECHA_COMPRA'],
	  'cod_paquete'    => $query->row['COD_PAQUETE'],
	  'servicioallot'  => $query->row['SERVICIOALLOT'],
	  'message_seq'    => $query->row['MESSAGE_SEQ']
 );
 }
 else
 {
  $info=null;	 
 }
 return $info;
}

function tienePaqueteEncolado($parametros) {
$db = $this->conectar($parametros['filter_cod_ambiente']);		

$sqltext = "select count(1) n
              from broadband.venta_paquete
			 where fecha_asignacion is null
			   and expirado = 'N'
               and numero_celular = '".$parametros['filter_NNNN']."'";	

 $query = $db->query($sqltext);

 return $query->row['N']; 
}

function tienePaqueteActivo($parametros) {
$db = $this->conectar($parametros['filter_cod_ambiente']);		

$sqltext = "select count(1) n
              from broadband.venta_paquete
			 where fecha_asignacion is not null
			   and expirado = 'N'
               and numero_celular = '".$parametros['filter_NNNN']."'";	

 $query = $db->query($sqltext);

 return $query->row['N']; 
}

function getInfoPaqueteApp($parametros) {
$db = $this->conectar($parametros['filter_cod_ambiente']);		

$query = $db->query("select cu.id_pass
                           ,cu.cod_tipo_pass
						   ,tp.nombre_tipo_pass
						   ,cu.cod_interfaz
						   ,cu.cobro
						   ,to_char(cu.fecha_compra,'dd-mm-yyyy hh24:mi:ss') fecha_compra
						   ,to_char(cu.fecha_asignacion,'dd-mm-yyyy hh24:mi:ss') fecha_asignacion
						   ,to_char(cu.fecha_expiracion,'dd-mm-yyyy hh24:mi:ss') fecha_expiracion
						   ,round(cu.fecha_expiracion-cu.fecha_compra) dias
						   ,decode(sign(fecha_expiracion-sysdate),-1,'S','N') Expirado
                       from broadband.CU_APP_PASS cu 
                           ,broadband.cs_tipo_pass tp
 WHERE cu.NUMERO_cELULAR='".$parametros['filter_NNNN']."'
   and tp.cod_tipo_pass = cu.cod_tipo_pass
   order by id_pass desc");

 foreach ($query->rows as $row) {  
 $info[] = array(
	     'id_pass'          => $row['ID_PASS'],
		 'cod_tipo_pass'    => $row['COD_TIPO_PASS'],
		 'nombre_tipo_pass' => $row['NOMBRE_TIPO_PASS'],
		 'cod_interfaz'     => $row['COD_INTERFAZ'],
		 'cobro'            => $row['COBRO'],
		 'fecha_compra'     => $row['FECHA_COMPRA'],
		 'fecha_asignacion' => $row['FECHA_ASIGNACION'],
		 'fecha_expiracion' => $row['FECHA_EXPIRACION'],
		 'dias'             => $row['DIAS'],
         'expirado'         => $row['EXPIRADO']		 
		);
 }
 return $info;		
}   

function getInfoPaquete($parametros,$cod_paquete) {
$db = $this->conectar($parametros['filter_cod_ambiente']);		

 $query = $db->query("select cod_paquete,nombre_paquete,palabra_paquete,saldoarestar precio,paquete_allot
              from broadband.config_paquete
			 where cod_paquete = '".$cod_paquete."'");
 
 $query2 = $db->query("select cod_mensaje,cod_interfaz
                        from mensaje_sms m
                       where m.numero_corto   = '404'
                         and m.id_mensaje     = 0
                         and m.cod_aplicacion = '".$cod_paquete."'
                         and m.id_aplicacion  = 0
                         and m.cod_interfaz  in (".$parametros['filter_interface'].")");

 foreach ($query2->rows as $mensaje) {
	 $mensajes[] = array(
	    'cod_mensaje' => $mensaje['COD_MENSAJE'],
		'interfaceid' => $mensaje['COD_INTERFAZ']
		);
 }

 $info = array (
	    'cod_paquete'     => $query->row['COD_PAQUETE'],
		'nombre_paquete'  => $query->row['NOMBRE_PAQUETE'],
		'palabra_paquete' => $query->row['PALABRA_PAQUETE'],
		'precio'          => $query->row['PRECIO'],
		'paquete_allot'   => $query->row['PAQUETE_ALLOT'],
		'mensajes_exito' => $mensajes
	);
	return $info;
}

function expirarPaquetes($parametros) {

$db = $this->conectar($parametros['filter_cod_ambiente']);		

$sqltext = "update broadband.venta_paquete
               set expirado = 'S'
			      ,fecha_expira = sysdate
 			 where expirado = 'N'
			   and (numero_celular = '502".$parametros['filter_numero_celular']."' or 
			        numero_celular = '".$parametros['filter_numero_celular']."');";	

 $query = $db->query("begin ".$sqltext." commit; end;");
 return 0;
}


function getPaquetesNavegacion($data = array()) {

$db = $this->conectar($data['filter_cod_ambiente']);		

$sqltext = "select id_paquete
                  ,cp.cod_paquete
				  ,cp.nombre_paquete
				  ,cp.capacidad_mb
                  ,fecha_compra fecha_compro
      ,to_char(vp.fecha_compra,'dd-mm-yyyy hh24:mi:ss') fecha_compra
	  ,to_char(vp.fecha_expira,'dd-mm-yyyy hh24:mi:ss') fecha_expira
	  ,expirado
	  ,servicioallot
	  ,interfaceid
	  ,message_seq
	  ,round(vp.fecha_expira-vp.fecha_compra) dias
	  ,quota
	  ,to_char(vp.fecha_asignacion,'dd-mm-yyyy hh24:mi:ss') fecha_asignacion
	  ,to_char(vp.fecha_expira_encola,'dd-mm-yyyy hh24:mi:ss') fecha_expira_encola
	  ,to_char(vp.fecha_expira_original,'dd-mm-yyyy hh24:mi:ss') fecha_expira_original
  from broadband.venta_paquete vp
      ,broadband.config_paquete cp
 where cp.cod_paquete = vp.cod_paquete
   and numero_celular = '502".$data['filter_numero_celular']."'
order by 5 desc ";	

  if (!empty($data['filter_numero_celular'])){	 
   $query = $db->query($sqltext);
   return $query->rows;
  }
  else 
  {
   return null;	  
  }
}

function getPaquetesNavegacionBB($parametros) {

$db = $this->conectar($data['filter_cod_ambiente']);		

$sqltext = "select vp.id_paquete
      ,cp.cod_paquete
      ,cp.nombre_paquete
      ,null capacidad_mb
      ,vp.fecha_compra fecha_compro
      ,to_char(vp.fecha_compra,'dd-mm-yyyy hh24:mi:ss') fecha_compra
      ,to_char(vp.fecha_expira,'dd-mm-yyyy hh24:mi:ss') fecha_expira
      ,vp.expirado
      ,null servicioallot
      ,null interfaceid
      ,idccws||'-'||cod_orden_activacion message_seg
      ,round(vp.fecha_expira-vp.fecha_compra) dias
  from broadband.config_paquete_bb cp
      ,broadband.blackberry_pp vp
where vp.cod_paquete = cp.cod_paquete
  and vp.numero_celular = '".$parametros['filter_NNNN']."'
 order by vp.fecha_compra desc";	
  
   $query = $db->query($sqltext);
   return $query->rows;
}


function cargarVentaPaquete($data = array()) {
   	$db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);	 	
   $json = array();	
   $sqltext = "select id_transaction
                     ,idccws
					 ,numero_celular
					 ,cod_paquete
					 ,fecha_compra
					 ,fecha_expira
					 ,servicioallot
					 ,cobro
					 ,message_seq
                 from broadband.venta_paquete v
                where (numero_celular = '".$data['filter_numero_celular']."' or numero_celular = '502".$data['filter_numero_celular']."')
                  and fecha_compra >= to_date('".$data['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')          
                order by fecha_compra";

   //$query = $this->db->query($sqltext);
     $query = $db->query($sqltext);
   
		$json = array(
		   'id_transaccion' => $query->row['ID_TRANSACTION'],
		   'id_ccws'        => $query->row['IDCCWS'],
		   'numero_celular' => $query->row['NUMERO_CELULAR'],
		   'cod_paquete'    => $query->row['COD_PAQUETE'],		   
		   'fecha_compra'   => $query->row['FECHA_COMPRA'],
		   'fecha_expira'   => $query->row['FECHA_EXPIRA'],
		   'servicioallot'  => $query->row['SERVICIOALLOT'],
		   'cobro'          => $query->row['COBRO'],
		   'message_seq'    => $query->row['MESSAGE_SEQ'],
		);

  return json_encode($json);	
}

}
?>