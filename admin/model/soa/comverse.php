<?php
class ModelSoaComverse extends Model {

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

function getFecha($cadena) {
 $fecha = substr($cadena,0,strrpos($cadena, "."));
 $fecha = str_replace("T"," ",$fecha);
 $date = date_create_from_format('Y-m-d H:i:s', $fecha); 	
return date_format($date,'d-m-Y H:i:s');
}

function getBilleteras($data = array()) {
$Subscriber = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/07/utility" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	<soap:Header>
		<wsse:Security mustUnderstand="1">
			<wsse:UsernameToken>
				<wsse:Username>VAS</wsse:Username>
				<wsse:Password>SAV321</wsse:Password>
			</wsse:UsernameToken>
		</wsse:Security>
	</soap:Header>
	<soap:Body>
		<RetrieveSubscriberWithIdentityWithHistoryForMultipleIdentities xmlns="http://comverse-in.com/prepaid/ccws">
			<subscriberID>[numero_celular]</subscriberID>
			<informationToRetrieve>1921</informationToRetrieve>
			<startDate>2016-07-16T00:00:00</startDate>
			<endDate>2016-07-16T23:59:59</endDate>
		</RetrieveSubscriberWithIdentityWithHistoryForMultipleIdentities>
	</soap:Body>
</soap:Envelope>
EOD;
date_default_timezone_set('America/Guatemala');
$date1 = (String)date('Y-m-d H:i:s', time());
$date2 = (String)date('Y-m-d H:i:s',time() + (1 * 24 * 60 * 60));
$date1 = str_replace(' ','T',$date1);
$date2 = str_replace(' ','T',$date2);

$Subscriber = str_replace('[numero_celular]',$this->quitarCodeArea($data['[numero_celular]']),$Subscriber);
$Subscriber = str_replace('[fechaInicio]',$date1,$Subscriber);
$Subscriber = str_replace('[fechaFin]',$date2,$Subscriber);

  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($Subscriber),
    'SOAPAction: '.'http://comverse-in.com/prepaid/ccws/RetrieveSubscriberWithIdentityWithHistoryForMultipleIdentities'
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'http://172.17.211.91/CCWS/ccws.asmx');
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $Subscriber);
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

     foreach ( $arreglo['SOAP:ENVELOPE']
                  ['SOAP:BODY']
                  ['RETRIEVESUBSCRIBERWITHIDENTITYWITHHISTORYFORMULTIPLEIDENTITIESRESPONSE']
                  ['RETRIEVESUBSCRIBERWITHIDENTITYWITHHISTORYFORMULTIPLEIDENTITIESRESULT']
				  ['SUBSCRIBERDATA']['BALANCES'] as $Balances) {
				    
			        foreach($Balances['BALANCE'] as $Balance) {
						$valor_billetera=0;
						if (is_array($Balance['BALANCE'])) {
						  foreach ($Balance['BALANCE'] as $valor){$valor_billetera=$valor; }
						} else {
						  $valor_billetera=$Balance['BALANCE'];	
						}
						
                    if ($valor_billetera!=0) { 
					$billeteras[] = array(
				               'billetera'    => $Balance['BALANCENAME'],
				               'fecha_expira' => $this->getFecha($Balance['ACCOUNTEXPIRATION']),
							   'valor'        => $valor_billetera
					);	
					}
					}
				  }
				  
 return $billeteras;
}

}
?>