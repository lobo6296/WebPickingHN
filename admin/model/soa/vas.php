<?php
class ModelSoaVas extends Model {
	
/* Metodos Implementados
   ---------------------
   1) getEndPoint
   2) XMLtoArray
   3) concatCodeArea($numero_celular) 
   4) AcquireProduct($parametros)
   5) GetAvailableProducts($parametros)
   6) VASTrixRecharge($parametros)
   7) Accreditation($parametros)
   8) ValidateCondition($parametros)
   9) borrarBilleteras($data = array())
*/

function getEndPoint($cod_ambiente) {
   $query = $this->mysql->query("select url
                                   from endpoint_ambiente
								  where activo = 'S'
								    and cod_ambiente =".$cod_ambiente);
	return $query->row['url'];			
}

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

function concatCodeArea($numero_celular) {
	return  strlen($numero_celular)==8 ? '502'.$numero_celular : $numero_celular;
}

function AcquireProduct($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$AcquireProductws = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                  xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
				  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\" 
				  xmlns:sms1=\"https://wap.tigo.com.gt/ws/SMSServiceDataObjects\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
   <sms:AcquireProductRequestParms>
         <sms:phonenumber>".$this->concatCodeArea($parametros['filter_numero_celular'])."</sms:phonenumber>
         <sms:productid>filter_cod_producto</sms:productid>
         <sms:interfaceid>filter_interface</sms:interfaceid>
		 <sms:transactionid>0</sms:transactionid>
   </sms:AcquireProductRequestParms>   
   </soapenv:Body>
   </soapenv:Envelope>";

  foreach ($parametros as $key=>$parametro) {
  	$AcquireProductws = str_replace($key,$parametro,$AcquireProductws);
  } 
  
$url = $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$AcquireProductws = str_replace('[secuencia]', $secuencia, $AcquireProductws);

$result=$soa->getResponse($AcquireProductws,'',$url);



  /*
    Analizando respuesta: 
   */    
   $AcquireProduct = new SimpleXMLElement($result);
   $ns = $AcquireProduct->getNamespaces(true);
   $AcquireProduct->registerXPathNamespace('vas', $ns['vas']);
  
   $ResultCode    = $AcquireProduct->xpath('//vas:AcquireProductResponseParms/vas:response');
   $transactionid = $AcquireProduct->xpath('//vas:AcquireProductResponseParms/vas:transactionid');
   $ResultDesc    = $AcquireProduct->xpath('//vas:AcquireProductResponseParms/vas:message');
   
   $reglas = Array();
   foreach ($AcquireProduct->xpath('//vas:AcquireProductResponseParms/vas:subcondition') as $condicion) {
	   $cod_regla = $condicion->xpath('vas:id');
	   $resultado = $condicion->xpath('vas:result');
	   
	   $reglas[] = array(
	      'cod_regla' => (string)$cod_regla[0],
		  'resultado' => (string)$resultado[0]
	   );
   }

   $results = array();
  
	$results['response'] = array(
	          'response'      => (string)$ResultCode[0],
			  'transactionid' => (string)$transactionid[0],
			  'message'       => (string)$ResultDesc[0],
			  'reglas'        => $reglas
	);

 return	$results;
}

function GetAvailableProducts($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$availableProducts = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                  xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
				  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\" 
				  xmlns:sms1=\"https://wap.tigo.com.gt/ws/SMSServiceDataObjects\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <sms:GetAvailableProductsRequestParms>
         <sms:phonenumber>filter_NNNN</sms:phonenumber>
         <sms:productcategory>filter_cod_categoria</sms:productcategory>
         <sms:interfaceid>filter_interface</sms:interfaceid>
         <sms:transactionid>[secuencia]</sms:transactionid>
      </sms:GetAvailableProductsRequestParms>
   </soapenv:Body>
</soapenv:Envelope>";

  foreach ($parametros as $key=>$parametro) {
  	$availableProducts = str_replace($key,$parametro,$availableProducts);
  } 

$url = $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$availableProducts = str_replace('[secuencia]', $secuencia, $availableProducts);

$result=$soa->getResponse($availableProducts,'',$url);

if (empty($result)) {
  return -1;	
}

  /*
    Analizando respuesta: 
   */    
   $xml_availableProducts = new SimpleXMLElement($result);
  
   $ns = $xml_availableProducts->getNamespaces(true);
   $xml_availableProducts->registerXPathNamespace('vas', $ns['vas']);

   $arreglo = $this->XMLtoArray($result);
   
   foreach($arreglo['SOAP:ENVELOPE']['SOAP:BODY']['VAS:GETAVAILABLEPRODUCTSRESPONSEPARMS']['VAS:ARRAYOFPRODUCTS'] as $arregloproducto) {

   
          //print_r($arregloproducto);
		  //exit(0);
   
	      foreach($arregloproducto as $producto) {   

			 $productid   = $producto['PRODUCTID'];
			 $name        = $producto['NAME'];
			 $price       = $producto['PRODUCTPRICE'];
			 $description = $producto['DESCRIPTION'];	 
			 
             $para = Array();
			 
			 foreach ($producto['VAS:ARRAYOFPARAMETERS'] as $arregloparameters) {	 
				 foreach ($arregloparameters['PARAMETER'] as $parameter) {
					 $para[$parameter['PARAMETERNAME']]= $parameter['PARAMETERVALUE'];
				 }
			 }			 
			 if (!isset($para['Valor prestamo'])) {
			  $vigencia = 'Vigencia '.$para['Duracion']." ".$para['Tipo de duracion'];
			 }
			 else {
			       $vigencia = 'Valor: Q'.$para['Valor prestamo'].", Comision: Q".$para['Valor comision'].", Categoria: ".$para['Categoria Producto'];
			 }
			
			 foreach ($producto['VAS:ARRAYOFPRODUCTCATEGORIES'] as $arregloprodcat) {
				 
				 foreach ($arregloprodcat['PRODUCTCATEGORY'] as $productcategory) { 
					 $category_name=str_pad($productcategory['PRODUCTCATEGORY'],2, "0", STR_PAD_LEFT)."-".(string)$productcategory['CATEGORYNAME'];
					 $category_name=str_replace(" ", "_",$category_name);
					 $category_name=str_replace("+", "_Mas",$category_name);
					 
					 $categoriasprod[] = array (
					   'productcategory'     => $productcategory['PRODUCTCATEGORY'],
					   'categoryname'        => $category_name,
					   'categorydescription' => $productcategory['CATEGORYDESCRIPTION'],
					   'categorylevel'       => $productcategory['CATEGORYLEVEL']
					 );
					 
					 if (!in_array($category_name,$categorias)) {
					   $categorias[] = $category_name;
					 }
				 }
				 
			 }			
			
            $productos[] = Array(
			  'productid'   => $productid,
			  'name'        => $name,
			  'price'       => $price,
			  'description' => $description,
			  'vigencia'    => $vigencia,
			  'categorias'  => $categoriasprod
			);

			foreach ($producto['VAS:ARRAYOFRESOURCES'] as $arregloresources) {
				 
				 foreach ($arregloresources['RESOURCE'] as $resource) {
					// echo $resource['DESCRIPTION']." ".$resource['UNITS']."-".$resource['AMOUNT']."<br>";
				 }
				 
			 }
			 unset($categoriasprod);
		  }  
   }
   
   $resultCode    = $xml_availableProducts->xpath('//vas:GetAvailableProductsResponseParms/vas:response');
   $message       = $xml_availableProducts->xpath('//vas:GetAvailableProductsResponseParms/vas:message');
   $transactionid = $xml_availableProducts->xpath('//vas:GetAvailableProductsResponseParms/vas:transactionid');
   $arreglo       = $xml_availableProducts->xpath('//vas:GetAvailableProductsResponseParms//vas:arrayofproducts');
   
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$resultCode[0],
			  'resultdesc' => (string)$message[0],
			  'transactionid' => (string)$transactionid[0]
	);

   $util->aasort($productos,'price');
   sort($categorias);
   
   $results['response']['productos'] = $productos;
   $results['response']['categorias'] = $categorias;
 return	$results;
}

function VASTrixRecharge($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$vastrix_parte1 = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                  xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
				  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\" 
				  xmlns:sms1=\"https://wap.tigo.com.gt/ws/SMSServiceDataObjects\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <sms:VASTrixRechargeRequestParms>
         <sms:phonenumber>".$this->concatCodeArea($parametros['filter_numero_celular'])."</sms:phonenumber>
         <sms:typeofrecharge>filter_typeofrecharge</sms:typeofrecharge>
         <sms:amount>filter_amount</sms:amount>
         <sms:interfaceid>67</sms:interfaceid>
         <sms:transactionid>[secuencia]</sms:transactionid>
         <sms:arrayofparameters>
         <sms1:Parameter>
               <sms1:parametername>online</sms1:parametername>
               <sms1:parametervalue>false</sms1:parametervalue>
         </sms1:Parameter>         
         </sms:arrayofparameters>		 
		 ";

  foreach ($parametros as $key=>$parametro) {
  	$vastrix_parte1 = str_replace($key,$parametro,$vastrix_parte1);
  } 
  
$vastrix_parte2 = "</sms:VASTrixRechargeRequestParms>
   </soapenv:Body>
</soapenv:Envelope>";

$vastrix = $vastrix_parte1.$vastrix_parte2;

$url = $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$vastrix = str_replace('[secuencia]', $secuencia, $vastrix);

$result=$soa->getResponse($vastrix,'',$url);

  /*
    Analizando respuesta: 
   */    
   $xml_VASTrix = new SimpleXMLElement($result);
   $ns = $xml_VASTrix->getNamespaces(true);
   $xml_VASTrix->registerXPathNamespace('vas', $ns['vas']);
  
   $ResultCode = $xml_VASTrix->xpath('//vas:VASTrixRechargeResponseParms/vas:response');
   $ResultDesc = $xml_VASTrix->xpath('//vas:VASTrixRechargeResponseParms/vas:transactionid');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

 return	$results;
}

function Accreditation($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

$Accreditation_parte1 = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                  xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
				  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\" 
				  xmlns:sms1=\"https://wap.tigo.com.gt/ws/SMSServiceDataObjects\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>				  
   <soapenv:Body>
      <sms:AccreditationRequestParms>
         <sms:phonenumber>filter_NNNN</sms:phonenumber>
         <sms:accreditationcode>filter_cod_acreditacion</sms:accreditationcode>
         <sms:interfaceid>67</sms:interfaceid>";

$excluir = array('filter_bmploginsystemcode'
                ,'filter_bmppassword'
				,'filter_ip');		 
		 
  foreach ($parametros as $key=>$parametro) {
  	$Accreditation_parte1 = str_replace($key,$parametro,$Accreditation_parte1);
  } 

if ($parametros) {
  foreach ($parametros as $key=>$parametro) {
	if (!in_array($key,$excluir)) {
	$param=$param."<sms1:Parameter><sms1:parametername>".$key."</sms1:parametername><sms1:parametervalue>".$parametro."</sms1:parametervalue></sms1:Parameter>";
    }	
  }
$Accreditation_parte2=$Accreditation_parte2."<sms:arrayofparameters>".$param."</sms:arrayofparameters>";  
}

$Accreditation_parte3 = "<sms:transactionid>[secuencia]</sms:transactionid>
                         </sms:AccreditationRequestParms>
                         </soapenv:Body>
                         </soapenv:Envelope>";

$Accreditation = $Accreditation_parte1.$Accreditation_parte2.$Accreditation_parte3;

$url       =  $this->getEndPoint($parametros['filter_cod_ambiente']);
$secuencia = "Testing".$util->getTime();
$secuencia = str_replace(' ','T',$secuencia); 
  
$Accreditation = str_replace('[secuencia]', $secuencia, $Accreditation);

$result=$soa->getResponse($Accreditation,'',$url);
  
  /*
    Analizando respuesta: 
   */    
   $huboError=0;
     try {
	   $xml_Accreditation = new SimpleXMLElement($result);
    } catch (Exception $e) {
	 $huboError=1;
     $results['response'] = array(
	          'resultcode' => '-1',
			  'resultdesc' => $e->getMessage()
	 );	 
	 echo "Hubo error:"; 
	 echo '<pre>'. htmlentities($Accreditation). '</pre>';
	 echo '<pre>'. htmlentities($result). '</pre>';
    }
   if ($huboError==0) {
   
   $ns = $xml_Accreditation->getNamespaces(true);
   $xml_Accreditation->registerXPathNamespace('vas', $ns['vas']);
  
   $ResultCode = $xml_Accreditation->xpath('//vas:AccreditationResponseParms/vas:response');
   $ResultDesc = $xml_Accreditation->xpath('//vas:AccreditationResponseParms/vas:transactionid');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (string)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);
   }
 return	$results;
}

function ValidateCondition($parametros) {
$soa  = New Soa();
$util = New Util();
$username   = 'JRIVERA';
$password   = htmlspecialchars('j$t56e&%', ENT_QUOTES);	

if ($parametros['filter_cod_condicion']==0) {
	$results['response'] = array(
	          'resultcode' => '0',
			  'resultdesc' => 'Success'
	);
	goto salir;
}
	
$validate01 = "
<soapenv:Envelope xmlns:oas=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" 
                  xmlns:sms=\"https://wap.tigo.com.gt/ws/SMSService\" 
				  xmlns:sms1=\"https://wap.tigo.com.gt/ws/SMSServiceDataObjects\" 
				  xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\">
   <soapenv:Header>".$soa->getAuthHeader($username,$password)."
   </soapenv:Header>					  
   <soapenv:Body>
      <sms:ValidateConditionRequestParms>
         <sms:phonenumber>".$util->concatCodeArea($parametros['filter_numero_celular'])."</sms:phonenumber>
         <sms:conditionid>filter_cod_condicion</sms:conditionid>\n";

$validate02 = "<sms:transactionid>[secuencia]</sms:transactionid>
      </sms:ValidateConditionRequestParms>
   </soapenv:Body>
</soapenv:Envelope>";

$validate = trim($validate01).trim($validate02);

if (isset($parametros['parametros'])) {
  $params = explode(',', $parametros['parametros']); 

  $arrayparam = "<sms:arrayofparameters>";
  foreach ($params as $param) {
         $info = explode('=',$param);
		 $arrayparam .= "<sms1:Parameter>";
		 $arrayparam .= "<sms1:parametername>".$info[0]."</sms1:parametername>".
		           "<sms1:parametervalue>".$info[1]."</sms1:parametervalue>";
		 $arrayparam .= "</sms1:Parameter>";		   
  }
  $arrayparam .= "</sms:arrayofparameters>";
  $validate = $validate01.$arrayparam.trim($validate02);  
}
$validate = trim($validate);
$secuencia = "Testing".date("YmdHis");
$validate = str_replace('[secuencia]', $secuencia, $validate);

foreach ($parametros as $key=>$parametro) {
	if (!is_array($parametro)){
  	$validate = str_replace($key,$parametro,$validate);
	}
}


  if (isset($parametros['filter_cod_ambiente'])) {
  $url = $this->getEndPoint($parametros['filter_cod_ambiente']);
  }

  $huboError=0;
  $results = array();
  try {
	   $result=$soa->getResponse($validate,'',$url);
	   $resultado = $this->XMLtoArray($result);
	   
       //$xml_validate = new SimpleXMLElement($result);	   
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
	   $ResultCode = $resultado['SOAP:ENVELOPE']['SOAP:BODY']['VAS:VALIDATECONDITIONRESPONSEPARMS']['VAS:RESPONSE'];
       $ResultDesc = $resultado['SOAP:ENVELOPE']['SOAP:BODY']['VAS:VALIDATECONDITIONRESPONSEPARMS']['VAS:TRANSACTIONID'];

       //$xml_validate = new SimpleXMLElement($result);
       //$ns = $xml_validate->getNamespaces(true);
       //$xml_validate->registerXPathNamespace('vas', $ns['vas']);
       //$ResultCode = $xml_validate->xpath('//vas:ValidateConditionResponseParms/vas:response');
       //$ResultDesc = $xml_validate->xpath('//vas:ValidateConditionResponseParms/vas:transactionid');
  
	$results['response'] = array(
	          'resultcode' => $ResultCode,
			  'resultdesc' => $ResultDesc
	);
   }
   salir:
   return	$results;
}

function borrarBilleteras($parametros) {
	
$Accreditation = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:oas="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:sms="https://wap.tigo.com.gt/ws/SMSService" xmlns:sms1="https://wap.tigo.com.gt/ws/SMSServiceDataObjects">
   <soapenv:Header>
      <wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
         <wsse:UsernameToken wsu:Id="UsernameToken-C5BB6D8EE532314AAF1474524697567105">
            <wsse:Username>JRIVERA</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">3G24XBoluP/om5obcDF5AH3rZbs=</wsse:Password>
            <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">Oj38R4Utl2WOZyXZrcCwJQ==</wsse:Nonce>
            <wsu:Created>2016-09-22T06:11:37.567Z</wsu:Created>
         </wsse:UsernameToken>
      </wsse:Security>
   </soapenv:Header>
   <soapenv:Body>
      <sms:AccreditationRequestParms>
         <sms:phonenumber>[numero_celular]</sms:phonenumber>
         <sms:accreditationcode>1357</sms:accreditationcode>
         <sms:interfaceid>1</sms:interfaceid>
         <sms:transactionid>1</sms:transactionid>
      </sms:AccreditationRequestParms>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

$Accreditation = str_replace('[numero_celular]',$this->concatCodeArea($parametros['[numero_celular]']),$Accreditation);

 $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($Accreditation),
    'SOAPAction: '.''
  );
  $url =  $this->getEndPoint($parametros['filter_cod_ambiente']);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $Accreditation);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if (($result = curl_exec($ch)) === FALSE) {
    die('cURL error: '.curl_error($ch)."<br />\n");
  }
  curl_close($ch);
  
  
  $arreglo = array();
  $arreglo = $this->XMLtoArray($result);

  $respuesta=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['VAS:ACCREDITATIONRESPONSEPARMS']['VAS:RESPONSE'];
  
 return	$respuesta;
}

}
 
 /*
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<soap:Fault>
<faultcode>soap:Client</faultcode>
<faultstring>Malformed XML, unable to read</faultstring>      
<detail>
<ErrorCode>-1</ErrorCode>
<ErrorDescription>Malformed XML, unable to read</ErrorDescription>
</detail>
</soap:Fault>
</soap:Body>
</soap:Envelope>
*/

?>