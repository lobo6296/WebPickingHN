<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
 
  Metodos implementados:
    QueryCustomerInfoForEPin
	
  Cambio: 172.22.161.55
          172.22.161.97
*/
define("CBS_IP","172.22.161.97");

class ModelSoaOcgservices extends Model {

public function getResponse($soap,$action,$url) {
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($soap),
    'SOAPAction: '.$action
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $soap);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if (($result = curl_exec($ch)) === FALSE) {
    die('cURL error: '.curl_error($ch)."<br />\n");
  } else {

  }
  curl_close($ch);
  
  return $result;
}

public function getQueryCustomerInfoForEPin($data = array()) {
$QueryCustomerInfoForEPin = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:bus="http://www.huawei.com/bme/ocginterface/cbs/businessmgrmsg" 
				  xmlns:com="http://www.huawei.com/bme/ocginterface/common" 
				  xmlns:bus1="http://www.huawei.com/bme/ocginterface/cbs/businessmgr">
   <soapenv:Header/>
   <soapenv:Body>
      <bus:QueryCustomerInfoForEPinRequestMsg>
         <RequestHeader>
            <com:Version>1</com:Version>
            <com:BusinessCode>1</com:BusinessCode>
            <com:MessageSeq>FECS11g88edzsf43</com:MessageSeq>
            <com:OwnershipInfo>
               <com:BEID>101</com:BEID>
               <com:BRID>101</com:BRID>
            </com:OwnershipInfo>
            <com:AccessSecurity>
               <com:LoginSystemCode>1001</com:LoginSystemCode>
               <com:Password>nbxjJ6IL2q1VQda9kzcslg==</com:Password>
               <com:RemoteIP>172.22.161.97</com:RemoteIP>
            </com:AccessSecurity>
            <com:OperatorInfo>
               <com:OperatorID>101</com:OperatorID>
               <com:ChannelID>1</com:ChannelID>
            </com:OperatorInfo>
            <com:AccessMode>3</com:AccessMode>
            <com:MsgLanguageCode>2019</com:MsgLanguageCode>
            <com:TimeFormat>
               <com:TimeType>1</com:TimeType>
               <com:TimeZoneID>1</com:TimeZoneID>
            </com:TimeFormat>
         </RequestHeader>
         <QueryCustomerInfoForEPinRequest>
            <bus1:SubAccessCode>
               <bus1:PrimaryIdentity>46561212</bus1:PrimaryIdentity>
               <bus1:SubscriberKey>1</bus1:SubscriberKey>
            </bus1:SubAccessCode>
            <bus1:QueryMethod>0</bus1:QueryMethod>
         </QueryCustomerInfoForEPinRequest>
      </bus:QueryCustomerInfoForEPinRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
 return $results;
}
}