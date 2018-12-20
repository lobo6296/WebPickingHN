<?php
class Soa {
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
  
  Metodos implementados:
    getResponse     - Pendiente
*/
public function getResponse($soap,$action,$url) {
  $headers = array(
    'Content-Type: text/xml;charset=UTF-8',
    'SOAPAction: "'.$action.'"',	
    'Content-Length: '.strlen($soap)
  );
  
  /*
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
  curl_setopt($ch, CURLOPT_TIMEOUT,        10); 
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_COOKIEJAR,'/tmp/cookieFileName');
  */
 
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $soap);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
  
  $result = curl_exec($ch);

  if ($result === false||empty($result)) {
      //$err = 'Curl error: ' . curl_error($ch);
	  return null;
	 curl_close($ch);
  } 
  curl_close($ch);
  return $result;
}

public function getAuthHeader($username,$password) {
$password = 'j$t56e&amp;%';
date_default_timezone_set('America/Guatemala');
$tm_created = gmdate('Y-m-d\TH:i:s\Z');
$tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180); 
$simple_nonce = mt_rand();
$encoded_nonce = base64_encode($simple_nonce);
$passdigest = base64_encode(sha1($simple_nonce . $tm_created .  $password, true));

/*
$header='<wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
	         <wsu:Timestamp xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
	         <wsse:UsernameToken>
	         <wsse:Username>'.$username.'</wsse:Username>
	         <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest">'.$passdigest.'</wsse:Password>
	         <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$encoded_nonce.'</wsse:Nonce>
	         <wsu:Created>'.$tm_created.'</wsu:Created>
	         </wsse:UsernameToken>
	         </wsse:Security>'; 
*/			 
$header='<wsse:Security soapenv:mustUnderstand="1" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
         <wsse:UsernameToken>
            <wsse:Username>JRIVERA</wsse:Username>
            <wsse:Password Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">j$t56e&amp;%</wsse:Password>
            <wsse:Nonce EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary">'.$encoded_nonce.'</wsse:Nonce>
            <wsu:Created>'.$tm_created.'</wsu:Created>
         </wsse:UsernameToken>
      </wsse:Security>';
			 
return $header;
}

}