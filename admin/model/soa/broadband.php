<?php
class ModelSoaBroadband extends Model {

function XMLtoArray($XML)
{
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $XML, $vals);
    xml_parser_free($xml_parser);
    
    $_tmp='';
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_level!=1 && $x_type == 'close') {
            if (isset($multi_key[$x_tag][$x_level]))
                $multi_key[$x_tag][$x_level]=1;
            else
                $multi_key[$x_tag][$x_level]=0;
        }
        if ($x_level!=1 && $x_type == 'complete') {
            if ($_tmp==$x_tag)
                $multi_key[$x_tag][$x_level]=1;
            $_tmp=$x_tag;
        }
    }
    
    foreach ($vals as $xml_elem) {
        $x_tag=$xml_elem['tag'];
        $x_level=$xml_elem['level'];
        $x_type=$xml_elem['type'];
        if ($x_type == 'open')
            $level[$x_level] = $x_tag;
        $start_level = 1;
        $php_stmt = '$xml_array';
        if ($x_type=='close' && $x_level!=1)
            $multi_key[$x_tag][$x_level]++;
        while ($start_level < $x_level) {
            $php_stmt .= '[$level['.$start_level.']]';
            if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level])
                $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
            $start_level++;
        }
        $add='';
        if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
            if (!isset($multi_key2[$x_tag][$x_level]))
                $multi_key2[$x_tag][$x_level]=0;
            else
                $multi_key2[$x_tag][$x_level]++;
            $add='['.$multi_key2[$x_tag][$x_level].']';
        }
        if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes', $xml_elem)) {
            if ($x_type == 'open')
                $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
            else
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
            eval($php_stmt_main);
        }
        if (array_key_exists('attributes', $xml_elem)) {
            if (isset($xml_elem['value'])) {
                $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
                eval($php_stmt_main);
            }
            foreach ($xml_elem['attributes'] as $key=>$value) {
                $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[$key] = $value;';
                eval($php_stmt_att);
            }
        }
    }
    return $xml_array;
}
public function quitarCodeArea($numero_celular) {
	return  strlen($numero_celular)==11 ? substr($numero_celular,3,8) : $numero_celular;
}

function getEndPoint($ambiente) {
	$url="";
	
	//http://172.22.116.77/ws/BroadbandServices
	
	switch ($ambiente) {
		case 1:        
	           $url = "http://wap.tigo.com.gt/ws/BroadbandServices";	   
		break;
		
		case 2: //$url = "http://172.22.116.77/ws/BroadbandServices";
		case 3:
		case 4:
		       $url = "http://172.22.52.224/ws/BroadbandServices";
	    break;
	}

	return $url;			
}

function getFecha($cadena) {
 $fecha = substr($cadena,0,strrpos($cadena, "."));
 $fecha = str_replace("T"," ",$fecha);
 $date = date_create_from_format('Y-m-d H:i:s', $fecha); 	
return date_format($date,'d-m-Y H:i:s');
}

function concatCodeArea($numero_celular) {
	return  strlen($numero_celular)==8 ? '502'.$numero_celular : $numero_celular;
}

function AddInternetPlan($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$internet = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                  xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
				  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <sms:AddInternetPlanRequestParms>
         <sms:phonenumber>".$this->concatCodeArea($parametros['filter_numero_celular'])."</sms:phonenumber>
         <sms:packageid>".$parametros['packageid']."</sms:packageid>
         <sms:interfaceid>1</sms:interfaceid>
         <sms:transactionid>[secuencia]</sms:transactionid>
      </sms:AddInternetPlanRequestParms>
    </soapenv:Body>
    </soapenv:Envelope>";

$url = $this->getEndPoint($parametros['cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$internet = str_replace('[secuencia]', $secuencia, $internet);

$result=$soa->getResponse($internet,'',$url);

  /*
    Analizando respuesta: 
   */    
   $xml_internet = new SimpleXMLElement($result);
   $ns = $xml_internet->getNamespaces(true);
   $xml_internet->registerXPathNamespace('vas', $ns['vas']);
  
   $ResultCode    = $xml_internet->xpath('//vas:AddInternetPlanResponseParms/vas:response');
   $TransactionId = $xml_internet->xpath('//vas:AddInternetPlanResponseParms/vas:transactionid');
   $ResultDesc    = $xml_internet->xpath('//vas:AddInternetPlanResponseParms/vas:message');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0],
			  'transactionid' => (string)$TransactionId[0]
	);

 return	$results;
}

function BroadbandPackage($parametros) {
$soa  = New Soa();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$internet = "<soapenv:Envelope xmlns:bro=\"https://wap.tigo.com.gt/ws/BroadbandServices\"
                               xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <bro:BroadbandPackageRequest>
         <phonenumber>filter_NNNN</phonenumber>
         <message>filter_message</message>
         <interfaceid>66</interfaceid>
         <transactionid>[secuencia]</transactionid>
      </bro:BroadbandPackageRequest>
    </soapenv:Body>
    </soapenv:Envelope>";


$util = New Util();


$url = $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$internet = str_replace('[secuencia]', $secuencia, $internet);
   
foreach ($parametros as $key=>$parametro) {
  	$internet = str_replace($key,$parametro,$internet);
} 

  $huboError=0;
  $results = array();
  try {
	   $result=$soa->getResponse($internet,'',$url);
  } catch (Exception $e) {
	 $huboError=1;
     $results['response'] = array(
	          'resultcode' => '-1',
			  'resultdesc' => $e->getMessage()
	 );	 
  }

  /*
    Analizando respuesta: 
   */    
   if ($huboError==0) {   

     try {
	   $xml_internet = new SimpleXMLElement($result);
    } catch (Exception $e) {
	 $huboError=1;
     $results['response'] = array(
	          'resultcode' => '-1',
			  'resultdesc' => $e->getMessage()
	 );	 
	 echo "Hubo error:"; 
	 echo '<pre>', htmlentities($internet), '</pre>';
	 print_r($parametros);
	 print_r($results);
	 exit(0);
    }

   if ($huboError==0) {
   
   $ns = $xml_internet->getNamespaces(true);
   $xml_internet->registerXPathNamespace('broad', $ns['broad']);
     
   $arreglo = $this->XMLtoArray($result);

   $faultcode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['FAULTCODE'];
   $faultstring=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['FAULTSTRING'];
   $errorcode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['DETAIL']['ERRORCODE'];
   $errordescription=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['DETAIL']['ERRORDESCRIPTION'];
   
   $results['response'] = array(
      'faultcode' => (string)$faultcode,
	  'faultstring' => (string)$faultstring,
	  'resultcode' => (string)$errorcode,
	  'resultdesc' => (string)$errordescription
   );

   if ($faultcode=="") {	   
	 $ResultCode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGERESPONSE']['RESPONSE'];  
     $message   =$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGERESPONSE']['MESSAGE'];
	   
	$results['response'] = array(
	         'resultcode' => (string)$ResultCode,
			 'resultdesc' => (string)$message,
			 'transactionid' => ""
	);
   }
   }
  
   }

 return	$results;
}

function BroadbandPackageHS($parametros) {
$soa  = New Soa();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$internet = "<soapenv:Envelope xmlns:bro=\"https://wap.tigo.com.gt/ws/BroadbandServices\"
                               xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <bro:BroadbandPackageHSRequest>
         <phonenumber>filter_NNNN</phonenumber>
         <message>filter_message</message>
         <interfaceid>66</interfaceid>
         <transactionid>[secuencia]</transactionid>
      </bro:BroadbandPackageHSRequest>
    </soapenv:Body>
    </soapenv:Envelope>";


$util = New Util();


$url = $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$internet = str_replace('[secuencia]', $secuencia, $internet);
   
foreach ($parametros as $key=>$parametro) {
  	$internet = str_replace($key,$parametro,$internet);
} 

  $huboError=0;
  $results = array();
  try {
	   $result=$soa->getResponse($internet,'',$url);
  } catch (Exception $e) {
	 $huboError=1;
     $results['response'] = array(
	          'resultcode' => '-1',
			  'resultdesc' => $e->getMessage()
	 );	 
  }

  /*
    Analizando respuesta: 
   */    
   if ($huboError==0) {   

     try {
	   $xml_internet = new SimpleXMLElement($result);
    } catch (Exception $e) {
	 $huboError=1;
     $results['response'] = array(
	          'resultcode' => '-1',
			  'resultdesc' => $e->getMessage()
	 );	 
	 echo "Hubo error:"; 
	 echo '<pre>', htmlentities($internet), '</pre>';
	 print_r($parametros);
	 print_r($results);
	 exit(0);
    }

   if ($huboError==0) {
   
   $ns = $xml_internet->getNamespaces(true);
   $xml_internet->registerXPathNamespace('broad', $ns['broad']);
     
   $arreglo = $this->XMLtoArray($result);

   $faultcode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['FAULTCODE'];
   $faultstring=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['FAULTSTRING'];
   $errorcode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['DETAIL']['ERRORCODE'];
   $errordescription=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['SOAP:FAULT']['DETAIL']['ERRORDESCRIPTION'];
   
   $results['response'] = array(
      'faultcode' => (string)$faultcode,
	  'faultstring' => (string)$faultstring,
	  'resultcode' => (string)$errorcode,
	  'resultdesc' => (string)$errordescription
   );

   if ($faultcode=="") {	   
	 $ResultCode=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGEHSRESPONSE']['RESPONSE'];  
     $message   =$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGEHSRESPONSE']['MESSAGE'];
	   
	$results['response'] = array(
	         'resultcode' => (string)$ResultCode,
			 'resultdesc' => (string)$message,
			 'transactionid' => ""
	);
   }
   }
  
   }

 return	$results;
}



function obtenerPaquete($data = array()) {

$PackageQuery = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bro="https://wap.tigo.com.gt/ws/BroadbandServices">
   <soapenv:Header/>
   <soapenv:Body>
      <bro:BroadbandPackageQueryRequest>
         <phonenumber>[numero_celular]</phonenumber>
         <useragent>-</useragent>
         <interfaceid>3</interfaceid>
         <transactionid>1</transactionid>
      </bro:BroadbandPackageQueryRequest>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
date_default_timezone_set('America/Guatemala');
$date1 = (String)date('Y-m-d H:i:s', time());
$date2 = (String)date('Y-m-d H:i:s',time() + (1 * 24 * 60 * 60));
$date1 = str_replace(' ','T',$date1);
$date2 = str_replace(' ','T',$date2);

$PackageQuery = str_replace('[numero_celular]',$data['[numero_celular]'],$PackageQuery);
$PackageQuery = str_replace('[fechaInicio]',$date1,$PackageQuery);
$PackageQuery = str_replace('[fechaFin]',$date2,$PackageQuery);

  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($PackageQuery),
    'SOAPAction: '
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://wap.tigo.com.gt/ws/BroadbandServices');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $PackageQuery);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if (($result = curl_exec($ch)) === FALSE) {
    die('cURL error: '.curl_error($ch)."<br />\n");
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch); 
  
  $arreglo = array();
  $billeteras = array();
  $arreglo = $this->XMLtoArray($result);
  $paquete=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGEQUERYRESPONSE']['PACKAGE'];
  $profile=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['BROAD:BROADBANDPACKAGEQUERYRESPONSE']['PROFILE'];
  
  $resultado = array(
			'package' => $paquete,
			'profile' => $profile
		);
  
 return $resultado;
}
}
?>