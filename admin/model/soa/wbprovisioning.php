<?php
class ModelSoaWbprovisioning extends Model {

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

public function concatCodeArea($numero_celular) {
	return  strlen($numero_celular)==8 ? '502'.$numero_celular : $numero_celular;
}
function expirar($data = array()) {
	print_r($data);
	
$AlterProfileMSR = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:wbp="https://wap.tigo.com.gt/ws/WBProvisioningServices">
   <soapenv:Header/>
   <soapenv:Body>
      <wbp:AlterProfileMSRInputParameters>
         <phonenumber>[numero_celular]</phonenumber>
         <profile>Expirar</profile>
         <expirationdate>[fecha_expiracion]</expirationdate>
         <transactionid>1</transactionid>
      </wbp:AlterProfileMSRInputParameters>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
date_default_timezone_set('America/Guatemala');
$date1 = (String)date('Y-m-d H:i:s', time());
$date2 = (String)date('Y-m-d H:i:s',time() + (1 * 24 * 60 * 60));
$date1 = str_replace(' ','T',$date1);
$date2 = str_replace(' ','T',$date2);

$AlterProfileMSR = str_replace('[numero_celular]',$data['[numero_celular]'],$AlterProfileMSR);
$AlterProfileMSR = str_replace('[fecha_expiracion]',$date1,$AlterProfileMSR);

  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($AlterProfileMSR),
    'SOAPAction: '
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://172.22.120.11/WBProvisioningServices');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $AlterProfileMSR);
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
  $resultado=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:ALTERPROFILEMSROUTPUTPARAMETERS']['result'];
 
  return $resultado;
}

function GetUserByNumberMSR($data = array()) {

$GetUserByNumberMSR = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                                         xmlns:wbp=\"https://wap.tigo.com.gt/ws/WBProvisioningServices\">
   <soapenv:Header/>
   <soapenv:Body>
      <wbp:GetUserByNumberMSRInputParameters>
         <phonenumber>".$this->concatCodeArea($data['filter_numero_celular'])."</phonenumber>
      </wbp:GetUserByNumberMSRInputParameters>
   </soapenv:Body>
</soapenv:Envelope>";

  $soa = New Soa();
  $url       = 'http://172.22.120.11/WBProvisioningServices';
  $secuencia = "Testing".date("YmdHis");
  $GetUserByNumberMSR = str_replace('[secuencia]', $secuencia, $GetUserByNumberMSR);
   
  foreach ($data as $key=>$parametro) {
  	//$GetUserByNumberMSR = str_replace($key,$parametro,$GetUserByNumberMSR);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($GetUserByNumberMSR,'',$url);
  
   /*
    Analizando respuesta: 
   */      
  $arreglo=$this->XMLtoArray($result);
  //print_r($arreglo);
   
  $dbprofile=$arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['DBPROFILE'];
    
   if (!empty($dbprofile)) {

	$results['response'] = array(
	          'resultcode' => 0,
			  'resultdesc' => ''
	);
	
	//$dbprofile          = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['DBPROFILE'];
    $expirationdate     = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['EXPIRATIONDATE'];
    $currentquotatext   = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['CURRENTQUOTATEXT'];
    $allowedquotatext   = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['ALLOWEDQUOTATEXT'];
	$currentactivequota = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['CURRENTACTIVEQUOTA'];
    $entitlements       = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['ENTITLEMENTS'];
    $custom3            = $arreglo['SOAP:ENVELOPE']['SOAP:BODY']['WB:GETUSERBYNUMBERMSROUTPUTPARAMETERS']['CUSTOM3'];
	
	$infoUser = array (
	    'dbprofile'          => $dbprofile,
		'expirationdate'     => date($this->language->get('datetime_format'), strtotime($expirationdate)),
		'currentquotatext'   => $currentquotatext,
		'allowedquotatext'   => $allowedquotatext,
		'currentactivequota' => $currentactivequota,
		'entitlements'       => $entitlements,
		'custom3'            => date($this->language->get('datetime_format'), strtotime($custom3))
	);
 
    $results['response']['msrinfo']=$infoUser;
   
   } else {
	       $results = null;
   }

  return $results;
}

}
?>