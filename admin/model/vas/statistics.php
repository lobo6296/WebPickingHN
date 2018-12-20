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
  from broadband.venta_paquete vp
      ,broadband.config_paquete cp
      ,wap.acreditacion a
 where a.cod_acreditacion = cp.cod_acreditacion
   and cp.cod_paquete = vp.cod_paquete
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