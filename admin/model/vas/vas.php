<?php
/*
Metodos Implementados:

function conectar($cod_ambiente);
function getListVastrix($parametros);
function getEndpoints($parametros);
function getInfoNumeroIngresado($valor);
function getMensajesExitoProducto($parametros,$cod_producto);
function msgExitoSuscripcion($parametros,$numero_corto,$cod_servicio);
function getMensajesRecibidos($parametros);
function estaSuscrito($parametros,$numero_corto,$cod_servicio);
function getInfoProducto($parametros,$pcod_producto);
function getInfoAccreditation($parametros = array());
function getModelo($parametros);
function getPrecioKit($parametros,$modelo);
function enAmbienteDesarrollo($parametros);
function getCondicionAcreditacion($parametros = array());
function getNombreCondicion($parametros);
function getWhitelist($parametros);
function getDetalleCondicion($parametros);
function getAllDetalleCondicion($parametros);
function getVASTRIX($parametros = array());
function encontroNotificacion($parametros,$numero_corto,$mensaje);
function guardarRespuesta($parametros);
function getSuscripciones($parametros = array());
function getMensajeTransaccion($parametros = array(),$idtransaccion);
function getLogSuscripcion($parametros = array());
function cargarPromociones($parametros = array());
function cargarSuscripciones($parametros = array());
function cargarDetalleRespuesta($parametros = array());
function getDependencias($cod_condicion);
function getAllDetalleAcreditacion($acreditacion);
function getTotalPromociones($parametros);
function getPromocionesHoy($parametros = array());
function getPromocionesAdquiridas($parametros = array());
function getMensajes($parametros = array());
function getDetalleRespuesta($parametros = array());
function getPrestamosPendientes($parametros = array());
function getAccreditation($parametros);
*/
class ModelVasVas extends Model {

function conectar($cod_ambiente) {
	/*
	  1: PRODUCCION
	  2: DESARROLLO
	*/
	switch ($cod_ambiente) {
		case 1:
   	           $db = new DB('oracle'
	                          , DB_HOSTNAME_PROD
							  , DB_USERNAME_PROD
							  , DB_PASSWORD_PROD
							  , DB_DATABASE_PROD
							  , DB_PORT_PROD);		
		break;
		case 2:
		case 3:
		case 4:
   	           $db = new DB('oracle'
	                          , DB_HOSTNAME_DESA
							  , DB_USERNAME_DESA
							  , DB_PASSWORD_DESA
							  , DB_SERVICE_NAME_DESA
							  , DB_PORT_DESA);	
		break;
 }
	return $db;
}

function getListVastrix($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select cod_accion
                              ,tipo_pin
							  ,crv.cod_condicion condicion_vastrix
							  ,crv.cod_acreditacion
							  ,a.cod_condicion condicion_acreditacion
							  ,descripcion
  from recargas.calendario_recarga_vastrix crv
      ,acreditacion a
 where a.cod_acreditacion = crv.cod_acreditacion
   and crv.monto = ".$parametros['filter_amount']."
   and crv.activo = 'S'
   and crv.tipo = '".$parametros['filter_typeofrecharge']."'
   and sysdate between crv.fecha_inicio 
   and crv.fecha_fin
 order by crv.prioridad desc");
 return $query->rows;	
}

function getEndpoints($parametros) {
   $query = $this->mysql->query("select cod_ambiente,url,text_description
                                   from endpoint_ambiente
								  where activo = 'S'
								  order by cod_ambiente");
	return $query->rows;		
}

function getInfoNumeroIngresado($valor) {
   $db = $this->conectar(1);
   $query = $db->query("select s.iccid,s.imsi,g.msisdn
                          from statistics.simcard s
                              ,gsm_subscriber g
                         where (s.iccid = '".$valor."' or s.imsi  = '".$valor."' or g.msisdn='".$valor."' or g.msisdn='502".$valor."') 
                           and g.imsi = s.imsi");
	$info = array (
	  'iccid'  => $query->row['ICCID'],
	  'imsi'   => $query->row['IMSI'],
	  'msisdn' => $query->row['MSISDN']
	);					  				   
	return $info;
}

function getMensajesExitoProducto($parametros,$cod_producto) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select m.cod_mensaje
                          from mensaje_sms m
                              ,producto_acreditacion pa
                         where m.numero_corto = 'Tigo'
                           and m.cod_aplicacion = pa.cod_acreditacion
                           and pa.cod_producto = '".$cod_producto."'
                           and m.id_aplicacion = 0
                           and m.cod_interfaz  in (1,3)");
	return $query->rows;	
}

function msgExitoSuscripcion($parametros,$numero_corto,$cod_servicio) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   $encontro=0;
   $query = $db->query("select m.cod_mensaje
                          from mensaje_sms m
                         where m.numero_corto   = '".$numero_corto."'
                           and m.cod_aplicacion = '".$cod_servicio."'
                           and m.id_aplicacion  = 0
                           and m.cod_interfaz  in (".$parametros['filter_interface'].")");
	if ($query->num_rows>0) {
		$encontro=1;
	}
 return $encontro;
}

function getMensajesRecibidos($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
	
   $sql = 	"select cod_mensaje
               from wap.detalle_respuesta
              where fecha_hora>=to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')
			    and numero_celular = '".$parametros['filter_NNNN']."'
		      order by fecha_hora";
   $query = $db->query($sql);

   foreach ($query->rows as $mensaje) {
		$mensajes[] = $mensaje['COD_MENSAJE'];
	};
			
   return $mensajes;			  
}

function estaSuscrito($parametros,$numero_corto,$cod_servicio) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   $suscrito = 0;
   $query = $db->query("select *
                          from suscripcion
                         where numero_corto = '".$numero_corto."'
						   and cod_servicio = '".$cod_servicio."'
						   and numero_celular = '".$parametros['filter_NNNN']."'
						   and fecha_agregado >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')");
    
	if ($query->num_rows>0) {
	  $suscrito = 1;	
	}						   	   
 return $suscrito;	
}

function getInfoProducto($parametros,$pcod_producto) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   $cod_producto    = $pcod_producto;
   $numero_producto = null;
   $cod_servicio    = null;

   $query = $db->query("    
  select cs.numero_corto,cs.cod_servicio,cs.precio,cs.cod_acreditacion,getTagInfo(valor_billetera,'productid') cod_producto
  from config_suscripcion cs
      ,acreditacion_regla ar
 where ar.cod_acreditacion = cs.cod_acreditacion
   and ar.nombre_comando   = 'AcquireProductRequestParms'
   and (numero_corto,cod_servicio) in (
select getTagInfo(valor_billetera,'shortnumber') numero_corto
      ,getTagInfo(valor_billetera,'subscriptionid') cod_servicio
  from acreditacion_regla a,
       producto_acreditacion pa
 where pa.cod_producto    =  '".$cod_producto."'
   and a.cod_acreditacion = pa.cod_acreditacion   
   and getTagInfo(valor_billetera,'operationtype')='1'   
   )");   
   
   if ($query->num_rows>0) {
	   $numero_corto = $query->row['NUMERO_CORTO'];
	   $cod_servicio = $query->row['COD_SERVICIO'];
	   $cod_producto = $query->row['COD_PRODUCTO'];
   }
      
   $query = $db->query("select distinct cod_producto,cp.cod_paquete,cp.palabra_paquete,cp.saldoarestar precio
  from acreditacion_regla a,
       producto_acreditacion pa,
       broadband.config_paquete cp
 where pa.cod_producto    =  '".$cod_producto."'
   and a.cod_acreditacion = pa.cod_acreditacion
   and cp.palabra_paquete = getTagInfo(valor_billetera,'message')||getTagInfo(valor_billetera,'profile')||getTagInfo(valor_billetera,'packageid')");
		
   $query2 = $db->query("select cod_mensaje,m.cod_interfaz,pa.cod_interfaz cod_interfaz_producto
  from producto_acreditacion pa
      ,mensaje_sms m
 where pa.cod_producto  = '".$cod_producto."'
   and m.numero_corto   = 'Tigo'
   and m.id_mensaje     = 0
   and m.cod_aplicacion = pa.cod_acreditacion
   and m.id_aplicacion  = 0
   and m.cod_interfaz   in (".$parametros['filter_interface'].")
   order by cod_mensaje");

    foreach ($query2->rows as $mensaje) {
		$mensajes[] = array ('cod_mensaje' => $mensaje['COD_MENSAJE'],
		                     'interfaceid' => $mensaje['COD_INTERFAZ'],
							 'interfaceid_producto'=> $mensaje['COD_INTERFAZ_PRODUCTO']
							);
	};
			
	$info = array (
	    'cod_producto' => $query->row['COD_PRODUCTO'],
		'numero_corto' => $numero_corto,
		'cod_servicio' => $cod_servicio,
		'cod_paquete'  => $query->row['COD_PAQUETE'],
		'precio'       => $query->row['PRECIO'],
		'mensajes_exito' => $mensajes
	);
	return $info;	
}

function getInfoAccreditation($parametros = array()) {

   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select cod_acreditacion,nombre_acreditacion,cod_condicion,texto_comentario
                                  from acreditacion
                                 where activo = 'S'
								   and sysdate between fecha_inicia and fecha_fin
                                   and cod_acreditacion = ".(int)$parametros['[acreditacion]']);
	return $query->rows;	
}

function getModelo($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select modelo
                          from statistics.modelo_prepago
                         where telefono = '".$parametros['filter_numero_celular']."'");
	return $query->row['MODELO'];
}

function getPrecioKit($parametros,$modelo) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   $query = $db->query("select monto from smartphone_kit where modelo = '".$modelo."'");
	return $query->row['MONTO'];
}

function enAmbienteDesarrollo($parametros) {
  $db = $this->conectar(1);
  $query = $db->query("select count(1) n from cu_whitelist_smpp where (numero_celular = '".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')");
  return $query->row['N'];
}

function getCondicionAcreditacion($parametros = array()) {

   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select cod_condicion from acreditacion where cod_acreditacion = ".(int)$parametros['[acreditacion]']);
   return $query->row['COD_CONDICION'];
}

function getNombreCondicion($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select nombre_condicion
                          from acreditacion_condicion
                         where cod_condicion = ".(int)$parametros['filter_cod_condicion']);
						 
   return $query->row['NOMBRE_CONDICION'];
}

function getWhitelist($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select p.cod_promocion,p.nombre_promocion
                          from whitelist w
                              ,promocion p
                         where (numero_celular =  '".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')
						   and p.cod_promocion = w.cod_promocion
						  order by p.cod_promocion");					 
 return $query->rows;
}

function getDetalleCondicion($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);	
	
   $query = $db->query("select cod_condicion,cod_det_condicion,descripcion_condicion,valor_condicion,operador_logico,tipo_condicion,activa
                          from acreditacion_condicion_det
                         where cod_condicion = ".(int)$parametros['filter_cod_condicion']." 
                         order by cod_det_condicion");
   return $query->rows;
}

function getAllDetalleCondicion($parametros) {
	
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
$sqltext  = "select 0 nivel,d.cod_det_condicion,d.cod_condicion,d.cod_det_condicion correlativo
       ,d.valor_condicion,d.tipo_condicion,d.conector_logico,d.operador_logico,d.descripcion_condicion,activa
  from acreditacion_condicion_det d
where cod_condicion = ".$parametros['filter_cod_condicion']."
  and tipo_condicion != 14
union
 select v.nivel,v.cod_det_condicion,d.cod_condicion,d.cod_det_condicion,lpad(' ',2*(nivel-1))||d.valor_condicion,d.tipo_condicion,d.conector_logico,d.operador_logico
      ,d.descripcion_condicion,d.activa
  from (
select distinct level nivel,cod_det_condicion,activa,to_number(child) cod_condicion
  from (
  select null parent, cod_det_condicion,activa,to_char(cod_condicion) child
   from acreditacion_condicion_det
  where tipo_condicion = 14
    and cod_condicion = ".$parametros['filter_cod_condicion']."
   union
  select to_char(cod_condicion),cod_det_condicion,activa,valor_condicion
    from acreditacion_condicion_det
   where tipo_condicion = 14  
  )
  where level>1
  start with parent is null
  connect by prior child = parent) v
  ,acreditacion_condicion_det d
  where d.cod_condicion = v.cod_condicion
  order by 2";
  $query = $db->query($sqltext);
  return $query->rows;
}

function getVASTRIX($parametros = array()) {
/*
   $query = $this->db->query("select id_transaccion,telefono,'Electronica' tipov,to_char(fecha_hora,'dd-mm-yyyy hh24:mi:ss') fecha_hora,tipo,tipo_recarga,monto,sellerid,id_externo
                                  from recargas.recarga_vastrix
                                 where fecha_hora >= to_date('02/10/2016','dd/mm/yyyy')
                                   and (telefono = '".$parametros['filter_numero_celular']."' or telefono = '502".$parametros['filter_numero_celular']."')
                                 order by fecha_hora");
								 */
	$querytxt = "select id_transaccion
      ,telefono
      ,'Electronica' tipov
      ,to_char(fecha_hora,'dd-mm-yyyy hh24:mi:ss') fecha_hora
      ,tipo
      ,tipo_recarga
      ,monto
      ,sellerid
      ,id_externo
      ,to_char(null) estado
  from recargas.recarga_vastrix
 where fecha_hora >= to_date('02/10/2016','dd/mm/yyyy')
      and (telefono = '".$parametros['filter_numero_celular']."' or telefono = '502".$parametros['filter_numero_celular']."')
           union             
select  pvu.idtrans
       ,pvu.numero_celular
       ,'Fisica' tipov
       ,to_char(pvu.fecha_utilizado,'dd-mm-yyyy hh24:mi:ss') fecha
       ,tv.tipo
       ,pv.tipo_pin
       ,tv.monto
       ,null sellerid
       ,codigo_impreso id_externo
       ,estado
  from  recargas.pin_vastrix_usado pvu
       ,recargas.pin_vastrix pv
       ,recargas.tipo_pin_vastrix tv
 where pv.cod_pin = pvu.cod_pin
   and tv.tipo_pin = pv.tipo_pin   
   and pvu.fecha_utilizado >= to_date('02/10/2016','dd/mm/yyyy')
   and (pvu.numero_celular = '".$parametros['filter_numero_celular']."' or pvu.numero_celular = '502".$parametros['filter_numero_celular']."')";						 
   
   $query = $this->db->query($querytxt);
   
   return $query->rows;
}

function encontroNotificacion($parametros,$numero_corto,$mensaje) {
$db = $this->conectar($parametros['filter_cod_ambiente']);

 $sqltext ="select count(1) n
                        from detalle_respuesta
                       where (numero_celular = '".$parametros['filter_numero_celular']."' 
					       or numero_celular = '502".$parametros['filter_numero_celular']."')
						 and numero_corto = '".$numero_corto."'
                         and mensaje      = '".$mensaje."'						 
						 and fecha_hora >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')";
   
 $query = $db->query($sqltext);
 return $query->row['N'];		
}

function guardarRespuesta($parametros) {
   $sql = 	"select to_char(fecha_hora,'yyyy-mm-dd hh24:mi:ss') fecha_hora
                   ,numero_corto
				   ,cod_mensaje
				   ,id_transaccion
				   ,mensaje
               from wap.detalle_respuesta
              where fecha_hora>=to_date('".$parametros['fechai']."','yyyy-mm-dd hh24:mi:ss')
			    and numero_celular = '".$parametros['numero_celular']."'
		      order by fecha_hora";

  $query = $this->desa->query($sql);
  
  //echo $sql."<br><br>";
  
  foreach ($query as $result) {			

      $sqltext = "begin testing.guardaRespuesta("
	                              ."'".$parametros['transactionid']."'"
								  .",".$result['ID_TRANSACCION']
                                  .",to_date('".$result['FECHA_HORA']."','yyyy-mm-dd hh24:mi:ss')"
								  .",'".$result['NUMERO_CORTO']."'"
								  .",'".$result['COD_MENSAJE']."'"
								  .",'".$result['MENSAJE']."');"
								  ." end;";
      $q = $this->test->query($sqltext);
  }	
   return 0;
}

function getSuscripciones($parametros = array()) {
$db = $this->conectar($parametros['filter_cod_ambiente']);
   
$suscripciones = array();
$log_suscripcion = array();

$sqltext = "select cs.numero_corto,cs.cod_servicio
      ,cs.nombre_plan
      ,cs.nombre_descriptivo
      ,cs.precio
	  ,s.numero_celular
  from suscripcion s
      ,config_suscripcion cs
 where cs.numero_corto = s.numero_corto
   and cs.cod_servicio = s.cod_servicio
   and (numero_celular = '".$parametros['filter_numero_celular']."' or
        numero_celular = '502".$parametros['filter_numero_celular']."')
   and activo = 'S'
   and sysdate between fecha_inicio and fecha_fin
   order by cs.numero_corto,cs.cod_servicio";

 
  if (!empty($parametros['filter_numero_celular'])) {
	 $query = $db->query($sqltext);
	 
     foreach ($query->rows as $result) {
		 
	 $sqllog = "select idtransaccion
	                  ,to_char(fecha_operacion,'dd-mm-yyyy hh24:mi:ss') fecha
					  ,operacion
					  ,cobro
					  ,cod_resultado
  from log_suscripcion
  where numero_celular = '".$result['NUMERO_CELULAR']."'
	and numero_corto   = '".$result['NUMERO_CORTO']."'
	and cod_servicio   = ".$result['COD_SERVICIO']."
   order by fecha";	 
		 
		 $qlog = $db->query($sqllog);
		 foreach ($qlog->rows as $reslog) {
			 $log_suscripcion[] = array (
			    'idtransaccion' => $reslog['IDTRANSACCION'],
				'fecha'         => $reslog['FECHA'],
				'operacion'     => $reslog['OPERACION'],
				'cobro'         => $reslog['COBRO'],
				'cod_resultado' => $reslog['COD_RESULTADO']
			 );
		 }
		 
        $suscripciones[] = array(
		    'numero_corto'       => $result['NUMERO_CORTO'],
			'cod_servicio'       => $result['COD_SERVICIO'],
			'nombre_plan'        => $result['NOMBRE_PLAN'],
			'nombre_descriptivo' => $result['NOMBRE_DESCRIPTIVO'],
			'precio'             => $result['PRECIO'],
			'log_suscripcion'    => $log_suscripcion
		);
		unset($log_suscripcion);
	 }	 
	 
     return $suscripciones;	 
  }
  else {
	  return null;
  }
}   

function getMensajeTransaccion($parametros = array(),$idtransaccion) {
$db = $this->conectar($parametros['filter_cod_ambiente']);

$sqltext="select numero_corto||'('||cod_mensaje||'):'||mensaje mensaje
  from detalle_respuesta
where (numero_celular = '".$parametros['filter_numero_celular']."' or
         numero_celular = '502".$parametros['filter_numero_celular']."')
  and id_transaccion = '".$idtransaccion."'
order by fecha_hora";

 $resultado =array();
 
  if (!empty($parametros['filter_numero_celular'])) {
	 $query = $db->query($sqltext);
	 
	 foreach ($query->rows as $result) {
		 $resultado[] = array (
		 'mensaje' => $result['MENSAJE']
		 );
	 }
	 
     return $resultado;	 
  }
  else {
	  return null;
  }
  
}

function getLogSuscripcion($parametros = array()) {
$db = $this->conectar($parametros['filter_cod_ambiente']);
   
$sqltext = "select idtransaccion
                  ,numero_corto
				  ,cod_servicio
                  ,to_char(fecha_operacion,'dd-mm-yyyy hh24:mi:ss') fecha
				  ,operacion
				  ,cobro
				  ,cod_resultado
  from log_suscripcion
  where (numero_celular = '".$parametros['filter_numero_celular']."' or
         numero_celular = '502".$parametros['filter_numero_celular']."')
   order by fecha";
 
  if (!empty($parametros['filter_numero_celular'])) {
	 $query = $db->query($sqltext);
     return $query->rows;	 
  }
  else {
	  return null;
  }
}   

function cargarPromociones($parametros = array()) {

   $db = $this->conectar($parametros['filter_cod_ambiente']);
	
   $json = array();	
   $sqltext = "select numero_celular
                     ,cod_promocion
					 ,to_char(fecha_hora,'dd-mm-yyyy hh24:mi:ss') fecha_hora
					 ,id_transaccion
					 ,cod_interfaz
                where (numero_celular = '".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')
                   and fecha_hora >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')";
   
   $query = $db->query($sqltext);
   if ($query->num_rows>0) {
   foreach ($query->rows as $result) {
		$json[] = array(
		   'numero_celular' => $result['NUMERO_CELULAR'],
		   'cod_promocion'  => $result['COD_PROMOCION'],
		   'fecha_hora'     => $result['FECHA_HORA'],		   
		   'id_transaccion' => $result['ID_TRANSACCION'],
		   'cod_interfaz'   => $result['COD_INTERFAZ']
		);
   }
   } else {
		$json = array(
		   'numero_celular' => null,
		   'cod_promocion'  => null,
		   'fecha_hora'     => null,		   
		   'id_transaccion' => null,
		   'cod_interfaz'   => null
		);	   
   }
  return json_encode($json);	  
}

function cargarSuscripciones($parametros = array()) {

   $db = $this->conectar($parametros['filter_cod_ambiente']);
	
   $json = array();	
   $sqltext = "select numero_celular
                     ,numero_corto
					 ,cod_servicio
					 ,to_char(fecha_agregado,'dd-mm-yyyy hh24:mi:ss') fecha_agregado
					 ,to_char(fecha_siguiente,'dd-mm-yyyy hh24:mi:ss') fecha_siguiente
					 ,to_char(ultima_fecha_exito,'dd-mm-yyyy hh24:mi:ss') ultima_fecha_exito
					 ,ultimo_resultado
                where (numero_celular = '".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')
                   and fecha_agregado >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss')";
   
   $query = $db->query($sqltext);
   if ($query->num_rows>0) {
   foreach ($query->rows as $result) {
		$json[] = array(
		   'numero_celular'     => $result['NUMERO_CELULAR'],
		   'numero_corto'       => $result['NUMERO_CORTO'],
		   'cod_servicio'       => $result['COD_SERVICIO'],
		   'fecha_agregado'     => $result['FECHA_AGREGADO'],
		   'fecha_siguiente'    => $result['FECHA_SIGUIENTE'],		   
		   'fecha_ultimo_exito' => $result['ULTIMA_FECHA_EXITO'],
		   'ultimo_resultado'   => $result['ULTIMO_RESULTADO']
		);
   }
   } else {
		$json = array(
		   'numero_celular'     => null,
		   'numero_corto'       => null,
		   'cod_servicio'       => null,
		   'fecha_agregado'     => null,
		   'fecha_siguiente'    => null,		   
		   'fecha_ultimo_exito' => null,
		   'ultimo_resultado'   => null);	   
   }
  return json_encode($json);	  
}

function cargarDetalleRespuesta($parametros = array()) {

   $db = $this->conectar($parametros['filter_cod_ambiente']);
	 	
   $json = array();	
   $sqltext = "select id_transaccion,numero_celular,numero_corto,cod_mensaje,mensaje,tipo_respuesta
                 from detalle_respuesta
                where (numero_celular = '".$parametros['filter_NNNN']."')
                   and fecha_hora >= to_date('".$parametros['filter_fecha_inicio']."','yyyy-mm-dd hh24:mi:ss') 
                order by fecha_hora";

   $query = $db->query($sqltext);
   
   if ($query->num_rows>0) {
   foreach ($query->rows as $result) {
		$json[] = array(
		   'id_transaccion'  => $result['ID_TRANSACCION'],
		   'numero_celular'  => $result['NUMERO_CELULAR'],
		   'numero_corto'    => $result['NUMERO_CORTO'],		   
		   'cod_mensaje'     => $result['COD_MENSAJE'],
		   'mensaje'         => $result['MENSAJE'],
		   'tipo_respuesta'  => $result['TIPO_RESPUESTA']
		);
   }
   } else {
		$json = array(
		   'id_transaccion'  => null,
		   'numero_celular'  => null,
		   'numero_corto'    => null,		   
		   'cod_mensaje'     => null,
		   'mensaje'         => null,
		   'tipo_respuesta'  => null
		);	   
   }
  return json_encode($json);	
}

function getDependencias($cod_condicion) {
$sqltext="select co.objeto,co.llave,oc.columns_selected,oc.left_side
  from condiciones_objeto co
      ,objeto_consulta oc
 where oc.objeto = co.objeto
   and co.objeto not in ('WAP.ACREDITACION_CONDICION_DET')
   and cod_condicion in (
select cod_condicion
  from dependencias
 where cod_condicion_hija ".$cod_condicion."
)
order by 2";
$query = $this->test->query($sqltext);

foreach ($query as $row) {
	$sqltext = "select ".$row['COLUMNS_SELECTED']
	          ."  from ".$row['OBJETO']
			  ." where '".$row['LEFT_SIDE']."'='".$row['LLAVE']."'";
	echo $sqltext."<br";
}
 return 0;
}

function getAllDetalleAcreditacion($acreditacion) {
$sqltext="select 0 nivel
       ,d.cod_regla
       ,d.cod_acreditacion
       ,a.nombre_acreditacion
       ,d.cod_condicion
       ,d.cod_regla correlativo
       ,d.descripcion_regla
       ,d.billetera_cos
       ,d.tipo_regla
       ,d.valor_billetera
       ,d.cod_condicion
 from acreditacion_regla d
     ,acreditacion a
where a.cod_acreditacion = d.cod_acreditacion
  and a.cod_acreditacion = ".$acreditacion."
  and tipo_regla != 30
  and d.cod_condicion > 0
union
 select v.nivel
       ,v.cod_regla
       ,d.cod_acreditacion
       ,a.nombre_acreditacion
       ,v.condicion       
       ,d.cod_regla
       ,d.descripcion_regla
       ,lpad(' ',2*(nivel-1))||d.billetera_cos
       ,d.tipo_regla
       ,d.valor_billetera
       ,d.cod_condicion
  from (
select distinct level nivel,cod_regla,to_number(child) cod_acreditacion,cod_condicion condicion
  from (
  select null parent, cod_regla,to_char(cod_acreditacion) child,cod_condicion
   from acreditacion_regla
  where tipo_regla = 30
    and cod_acreditacion = ".$acreditacion."
   union
  select to_char(cod_acreditacion),cod_regla,billetera_cos,cod_condicion
    from acreditacion_regla
   where tipo_regla = 30
  )
  where level>1
  start with parent is null
  connect by prior child = parent) v
  ,acreditacion_regla d
  ,acreditacion a
  where a.cod_acreditacion = d.cod_acreditacion
    and d.cod_acreditacion = v.cod_acreditacion
    and v.condicion > 0
  order by 2";	
  $query = $this->desa->query($sqltext);
  return $query->rows;	
}

function getTotalPromociones($parametros) {	
		$db = $this->conectar($parametros['filter_cod_ambiente']);
		
    	 $sql = "select count(*) total
                  from numero_promocion np
                      ,promocion p
                 where np.cod_promocion = p.cod_promocion
                   and fecha_hora >= trunc(sysdate-3)";		
		$sql .= " and (np.numero_celular = '" . $parametros['filter_numero_celular'] . "' or np.numero_celular = '502" . $parametros['filter_numero_celular']."')";		   
				   
		$query = $db->query($sql);
	}	
	
function getPromocionesHoy($parametros = array()) {
		$db = $this->conectar($parametros['filter_cod_ambiente']);
		
		$sql = "select p.cod_promocion,p.nombre_promocion,to_char(np.fecha_hora,'dd/mm/yyyy hh24:mi:ss') fecha_hora,p.monto,np.user_agent,np.id_transaccion
                  from numero_promocion np
                      ,promocion p
                 where np.cod_promocion = p.cod_promocion";
	if (empty($parametros['filter_numero_celular'])) {return null;}			   

		if (!empty($parametros['filter_numero_celular'])) {
			$sql .= " AND (np.numero_celular = '" . $parametros['filter_numero_celular'] . "' or np.numero_celular = '502" . $parametros['filter_numero_celular']."')";
		} else {
			$sql .= " AND np.numero_celular = '00000000'";
		}

		$sql .= " order by fecha_hora";
		 
		$query = $db->query($sql);

		return $query->rows;
	}

function getPromocionesAdquiridas($parametros = array()) {
   $query = $this->desa->query("select np.cod_promocion,nombre_promocion,to_char(fecha_hora,'dd/mm/yyyy hh24:mi:ss') fecha_hora
                                  from numero_promocion np
                                      ,promocion p
                                 where np.cod_promocion = p.cod_promocion
                                   and (np.numero_celular = '".$parametros['[numero_celular]']."' or np.numero_celular = '502".$parametros['[numero_celular]']."')
                                 order by fecha_hora");
   return $query->rows;
}

function getMensajes($parametros = array()) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
  
   $query = $db->query("select to_char(fecha_hora,'dd/mm/yyyy hh24:mi:ss')fecha_hora
                                       ,numero_corto||'('||cod_mensaje||'):'||mensaje mensaje
                                   from wap.detalle_respuesta
                                  where (numero_celular = '".$parametros['[numero_celular]']."' or numero_celular = '502".$parametros['[numero_celular]']."')
                                  order by fecha_hora");
   return $query->rows;
}

function getDetalleRespuesta($parametros = array()) {
	
  $db = $this->conectar($parametros['filter_cod_ambiente']);
   if (!empty($parametros['filter_numero_celular'])) {
   $query = $db->query("select to_char(fecha_hora,'dd-mm-yyyy hh24:mi:ss')fecha_hora 
                              ,pk_util.getCodigo(cod_mensaje) codigo
                                       ,numero_corto||'('||cod_mensaje||'):'||mensaje mensaje
                                   from detalle_respuesta
                                  where (numero_celular = '".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')
                                    and fecha_hora >= trunc(sysdate) 
								  order by fecha_hora desc");
   return $query->rows;
   }
   else {
	     return null;
   }
}
 
function getPrestamosPendientes($parametros = array()) {
  $db = $this->conectar($parametros['filter_cod_ambiente']);
	 
  $sqltext = "Select p.cod_promocion_lend 
      ,p.nombre_promocion
      ,to_char(fecha_otorgado,'dd-mm-yyyy hh24:mi:ss') fecha_otorgado
      ,monto_prestado
      ,monto_comision
      ,Nvl(Sum((Monto_Prestado+monto_comision)-(monto_cobrado+monto_comision_cobrado)),0) deuda
  From Lendme2 l
      ,promocion_lend p
 where p.cod_promocion_lend = l.cod_promocion_lend
   and (l.numero_Celular='".$parametros['filter_numero_celular']."' or numero_celular = '502".$parametros['filter_numero_celular']."')
   and (l.cobrado='N' or l.cobrado_comision='N') 
   and l.cod_promocion_lend not in (5,6,17,18,19,43,54,55,81,82,83,90,91,92,93,95,102,116,117,118)
  group by p.cod_promocion_lend
      ,p.nombre_promocion
      ,fecha_otorgado
      ,monto_prestado
      ,monto_comision
 order by 3 desc";	

     $query = $db->query($sqltext);
 
   return $query->rows;
}

function getAccreditation($parametros) {
   $db = $this->conectar($parametros['filter_cod_ambiente']);
   
   $query = $db->query("select cod_acreditacion,nombre_acreditacion,cod_condicion,texto_comentario
                                  from acreditacion
                                 where activo = 'S'
								   and sysdate between fecha_inicia and fecha_fin
                                   and cod_acreditacion = ".(int)$parametros['[acreditacion]']);
	return $query->rows;	
}

}
?>