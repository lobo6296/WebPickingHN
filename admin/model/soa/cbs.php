<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
  
  Cambio: 172.22.161.97
          172.22.161.97
*/
class ModelSoaCbs extends Model {
    
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
    echo "Success!<br/>\n";
  }
  curl_close($ch);
  
  return $result;
}
	
public function getQueryCostumerInfo($data = array()) {
	
  $queryCustomerInfo = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryCustomerInfoRequestMsg>
          <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>122</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>102</cbs:LoginSystemCode>
               <cbs:Password>xyYSFeOUi5DagegPuCQmUQ==</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>
         <QueryCustomerInfoRequest>
            <bcs:QueryObj>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>[filter_numero_celular]</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:QueryObj>
         </QueryCustomerInfoRequest>
      </bcs:QueryCustomerInfoRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
   if (!empty($data['filter_numero_celular'])) {
			$queryCustomerInfo = str_replace('[filter_numero_celular]', $data['filter_numero_celular'], $queryCustomerInfo);
   }
      /**************************************************************************************************************/
  $url = "http://172.22.161.97:8080/services/BcServices";
  $action='QueryCustomerInfo';
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($queryCustomerInfo),
    'SOAPAction: '.$action
  );
  $huboError=0;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $queryCustomerInfo);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

  if (($result = curl_exec($ch)) === FALSE) {
    //die('cURL error: '.curl_error($ch)."<br />\n");
	$huboError=1;
	$this->session->data['cbs_error']='Si';
	echo "Error (01)";
	exit(0);
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch);
   if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);
   $xml->registerXPathNamespace('bcs', $ns['bcs']);
   $xml->registerXPathNamespace('bcc', $ns['bcc']);
   //var_dump($xml);

   $Version=$xml->xpath('//cbs:Version');
   $ResultCode=$xml->xpath('//cbs:ResultCode');
   $ResultDesc=$xml->xpath('//cbs:ResultDesc');
   $SubscriberKey=$xml->xpath('//bcs:SubscriberKey');
   $UserCustomerKey=$xml->xpath('//bcc:UserCustomerKey');
   $SubIdentity=$xml->xpath('//bcc:SubIdentity//bcc:SubIdentity');
   $offeringID=$xml->xpath('//bcc:OfferingID'); 
   $Status=$xml->xpath('//bcc:Status'); 
   $ActivationTime=$xml->xpath('//bcs:ActivationTime');
   
   $offeringdesc = (string)$ResultDesc[0];
   //$offeringdesc = str_replace('[filter_numero_celular]','', $offeringdesc);
 
   
   if (!empty($data['filter_numero_celular'])) {
	   
				 /*******************************************/
             $sql = "select main_offer_name
                       from main_offer
                      where main_offer_id = ".(int)$offeringID[0];
	         /*******************************************/
	        $query = $this->mysql->query($sql);
            $main_offer_name = $query->row['main_offer_name'];	   
	   
	        $status = (string)$Status[0];

   $results[] = array(
				'version'         => (string)$Version[0],
				'resultcode'      => (string)$ResultCode[0],
				'resultdesc'      => $offeringdesc,
				'subscriberkey'   => (string)$SubscriberKey[0],
				'usercustomerkey' => (string)$UserCustomerKey[0],
				'subIdentity'     => (string)$SubIdentity[0],
				'offeringid'      => (int)$offeringID[0]."-".$main_offer_name,
				'status'          =>  $status."-".($status=="1" ? 'Idle' :  $status=="4" ? 'Suspend' : "Active"),
				'activationtime'  => (string)$ActivationTime[0]
			);
   }
   else {
   $results[] = array(
				'version'         => "",
				'resultcode'      => "",
				'resultdesc'      => "",
				'subscriberkey'   => "",
				'usercustomerkey' => "",
				'subIdentity'     => "",
				'offeringid'      => "",
				'status'          => ""
			);	
   }   
   }
   /*************************************************************************************************************/
   return $results;
}	

public function getSupplementaryOffering($data = array()) {
	
  $queryCustomerInfo = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bcs="http://www.huawei.com/bme/cbsinterface/bcservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:bcc="http://www.huawei.com/bme/cbsinterface/bccommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bcs:QueryCustomerInfoRequestMsg>
          <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>122</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>102</cbs:LoginSystemCode>
               <cbs:Password>xyYSFeOUi5DagegPuCQmUQ==</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>
         <QueryCustomerInfoRequest>
            <bcs:QueryObj>
               <bcs:SubAccessCode>
                  <bcc:PrimaryIdentity>[filter_numero_celular]</bcc:PrimaryIdentity>
               </bcs:SubAccessCode>
            </bcs:QueryObj>
         </QueryCustomerInfoRequest>
      </bcs:QueryCustomerInfoRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
   if (!empty($data['filter_numero_celular'])) {
			$queryCustomerInfo = str_replace('[filter_numero_celular]', $data['filter_numero_celular'], $queryCustomerInfo);
   }
      /**************************************************************************************************************/
  $url = "http://172.22.161.97:8080/services/BcServices";
  $action='QueryCustomerInfo';
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($queryCustomerInfo),
    'SOAPAction: '.$action
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $queryCustomerInfo);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $huboError=0;
  if (($result = curl_exec($ch)) === FALSE) {
	  $huboError=1;
	  $this->session->data['cbs_error']='Si';
	  	echo "Error (02)";
	exit(0);
    //die('cURL error: '.curl_error($ch)."<br />\n");
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch);
   if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);
   $xml->registerXPathNamespace('bcs', $ns['bcs']);
   $xml->registerXPathNamespace('bcc', $ns['bcc']);
   
   foreach ($xml->xpath('//bcs:SupplementaryOffering') as $SupplementaryOffering) {
	      $Status          = $SupplementaryOffering->xpath('bcc:Status');
		  $EffectiveTime   = $SupplementaryOffering->xpath('bcs:EffectiveTime');
		  $ExpirationTime  = $SupplementaryOffering->xpath('bcs:ExpirationTime');
		  
		  foreach ($SupplementaryOffering->xpath('bcc:OfferingKey') as $OfferingKey) {
		  $OfferingID = $OfferingKey->xpath('bcc:OfferingID');
	      $PurchaseSeq = $OfferingKey->xpath('bcc:PurchaseSeq');
		  }
		  
		  $sql = "select offer_name from offer o where offer_id = '".(string)$OfferingID[0]."'";
	      $query = $this->mysql->query($sql);
          $offer_name = $query->row['offer_name'];
		  
		  //date("YmdHis", strtotime((string)$EffectiveTime[0]));
		  
		  $results[] = array(
				'offeringid'     => (string)$OfferingID[0],
				'offeringdesc'   => $offer_name,
				'purchaseseq'    => (string)$PurchaseSeq[0],
				'effectivetime'  => date("d-m-Y H:i:s", strtotime((string)$EffectiveTime[0])),
				'expirationtime' => date("d-m-Y H:i:s", strtotime((string)$ExpirationTime[0])),
				'status'         => (string)$Status[0]=="1" ? 'Idle' : 
				                   ((string)$Status[0]=="4" ? 'Suspend' : "Active")
			);
			
   }
 
   /*
    foreach ($xml->xpath('//bcs:SupplementaryOffering//bcc:OfferingKey') as $SupplementaryOffering) {
	    $OfferingID = $SupplementaryOffering->xpath('bcc:OfferingID');
		$PurchaseSeq = $SupplementaryOffering->xpath('bcc:PurchaseSeq');
					 *******************************************
             $sql = " select offer_name
                        from offer o
                       where offer_id = '".(string)$OfferingID[0]."'";
	         *******************************************
	        $query = $this->mysql->query($sql);
            $offer_name = $query->row['offer_name'];	
		
		
       $results[] = array(
				'offeringid'    => (string)$OfferingID[0],
				'offeringdesc'  => $offer_name,
				'purchaseseq'  => (string)$PurchaseSeq[0]
			);	
   }
   */
   }	
   /*************************************************************************************************************/
   $this->rasort($results,'effectivetime');
   return $results;
}


public function getQueryRechargeLogResult($data = array()) {
$QueryRechargeLog = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:QueryRechargeLogRequestMsg>
    <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>102</cbs:LoginSystemCode>
               <cbs:Password>xyYSFeOUi5DagegPuCQmUQ==</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>  
         <QueryRechargeLogRequest>
            <ars:QueryObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>[filter_numero_celular]</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
            <ars:TotalRowNum>0</ars:TotalRowNum>
            <ars:BeginRowNum>0</ars:BeginRowNum>
            <ars:FetchRowNum>455</ars:FetchRowNum>
            <ars:StartTime>[filter_fecha_inicio]</ars:StartTime>
            <ars:EndTime>[filter_fecha_fin]</ars:EndTime>
         </QueryRechargeLogRequest>
      </ars:QueryRechargeLogRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

/********************************************************************************************************* 
	 Haciendo uso de una funcion para obtener la respuesta del WS.
	*********************************************************************************************************/ 
   $today = "VAS".date("YmdHis");
   $QueryRechargeLog = str_replace('[secuencia]', $today, $QueryRechargeLog);
  	
   if (!empty($data['filter_numero_celular'])) {
			$QueryRechargeLog = str_replace('[filter_numero_celular]', $data['filter_numero_celular'], $QueryRechargeLog);
   }

   if (!empty($data['filter_fecha_inicio'])) {
			$QueryRechargeLog = str_replace('[filter_fecha_inicio]', $data['filter_fecha_inicio'], $QueryRechargeLog);
   }
   
    if (!empty($data['filter_fecha_fin'])) {
			$QueryRechargeLog = str_replace('[filter_fecha_fin]', $data['filter_fecha_fin'], $QueryRechargeLog);
   }

   /**************************************************************************************************************/
  $url = 'http://172.22.161.97:8080/services/ArServices';
  $action='QueryRechargeLog';
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($queryBalance),
    'SOAPAction: '.$action
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $queryBalance);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $huboError=0;
  if (($result = curl_exec($ch)) === FALSE) {
	$huboError=1;  
	$this->session->data['cbs_error']='Si';
		echo "Error (03)";
	exit(0);
   // die('cURL error: '.curl_error($ch)."<br/>\n");
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch);
   if ($huboError==0) {
   /*************************************************************************************************************/
   $xml_queryBalance = new SimpleXMLElement($result);
   $nsqb = $xml_queryBalance->getNamespaces(true);
   $xml_queryBalance->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_queryBalance->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_queryBalance->registerXPathNamespace('cbs', $nsqb['cbs']);
   $results = array (); 
   foreach ($xml_queryBalance->xpath('//ars:BalanceResult') as $BalanceResult) {
	 $BalanceType     = $BalanceResult->xpath('arc:BalanceType');
	 $BalanceTypeName = $BalanceResult->xpath('arc:BalanceTypeName');
	 $TotalAmount     = $BalanceResult->xpath('arc:TotalAmount');
	 foreach ($BalanceResult->xpath('arc:BalanceDetail') as $BalanceDetail) {
		 $BalanceInstanceID = $BalanceDetail->xpath('arc:BalanceInstanceID');
		 $ExpireTime        = $BalanceDetail->xpath('arc:ExpireTime');
	 }
     /*******************************************/
     $sql = "select billetera
          from jrivera.billeteras_subaccounts
         where '".$BalanceType[0]."' like '%'||subaccount";
	 /*******************************************/
	 $query = $this->db->query($sql);
     $billetera = $query->row['BILLETERA'];
	 
	 $results[] = array($BalanceType[0],$TotalAmount[0],$BalanceInstanceID[0],$BalanceTypeName[0],$ExpireTime[0],$billetera);
    }
   }
    return $results;
	}

	
public function getBalanceResult($data = array()) {
		$queryBalance = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:QueryBalanceRequestMsg>
          <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[Secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>102</cbs:LoginSystemCode>
               <cbs:Password>xyYSFeOUi5DagegPuCQmUQ==</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>  
         <QueryBalanceRequest>
            <ars:QueryObj>
               <ars:SubAccessCode>
                  <arc:PrimaryIdentity>[filter_numero_celular]</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
         </QueryBalanceRequest>
      </ars:QueryBalanceRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

/********************************************************************************************************* 
	 Haciendo uso de una funcion para obtener la respuesta del WS.
	*********************************************************************************************************/ 
   $today = "VAS".date("YmdHis");
   $queryBalance = str_replace('[secuencia]', $today, $queryBalance);
   
	
   if (!empty($data['filter_numero_celular'])) {
			$queryBalance = str_replace('[filter_numero_celular]', $data['filter_numero_celular'], $queryBalance);
   }
   
   /**************************************************************************************************************/
  $url = 'http://172.22.161.97:8080/services/ArServices';
  $action='QueryBalance';
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($queryBalance),
    'SOAPAction: '.$action
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $queryBalance);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $huboError=0;
  if (($result = curl_exec($ch)) === FALSE) {
	$huboError=1;  
	$this->session->data['cbs_error']='Si';
		echo "Error (04)";
	exit(0);
   // die('cURL error: '.curl_error($ch)."<br/>\n");
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch);
   if ($huboError==0) {
   /*************************************************************************************************************/
   $xml_queryBalance = new SimpleXMLElement($result);
   $nsqb = $xml_queryBalance->getNamespaces(true);
   $xml_queryBalance->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_queryBalance->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_queryBalance->registerXPathNamespace('cbs', $nsqb['cbs']);
   $results = array (); 
   foreach ($xml_queryBalance->xpath('//ars:BalanceResult') as $BalanceResult) {
	 $BalanceType     = $BalanceResult->xpath('arc:BalanceType');
	 $BalanceTypeName = $BalanceResult->xpath('arc:BalanceTypeName');
	 $TotalAmount     = $BalanceResult->xpath('arc:TotalAmount');
	 foreach ($BalanceResult->xpath('arc:BalanceDetail') as $BalanceDetail) {
		 $BalanceInstanceID = $BalanceDetail->xpath('arc:BalanceInstanceID');
		 $ExpireTime        = $BalanceDetail->xpath('arc:ExpireTime');
	 }
     /*******************************************/
     $sql = "select billetera
          from jrivera.billeteras_subaccounts
         where '".$BalanceType[0]."' like '%'||subaccount";
	 /*******************************************/
	 $query = $this->db->query($sql);
     $billetera = $query->row['BILLETERA'];
	 
	 $results[] = array($BalanceType[0],$TotalAmount[0],$BalanceInstanceID[0],$BalanceTypeName[0],$ExpireTime[0],$billetera);
    }
   }
    return $results;
	}

	/*******************************************************************************/
public function getFreeUnits($data = array()) {
$queryFreeUnits = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:bbs="http://www.huawei.com/bme/cbsinterface/bbservices" xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" xmlns:bbc="http://www.huawei.com/bme/cbsinterface/bbcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <bbs:QueryFreeUnitRequestMsg>
        <RequestHeader>
            <cbs:Version>1</cbs:Version>
            <cbs:BusinessCode>1</cbs:BusinessCode>
            <cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
            <cbs:OwnershipInfo>
               <cbs:BEID>101</cbs:BEID>
               <cbs:BRID>101</cbs:BRID>
            </cbs:OwnershipInfo>
            <cbs:AccessSecurity>
               <cbs:LoginSystemCode>102</cbs:LoginSystemCode>
               <cbs:Password>xyYSFeOUi5DagegPuCQmUQ==</cbs:Password>
               <cbs:RemoteIP>172.22.161.97</cbs:RemoteIP>
            </cbs:AccessSecurity>
            <cbs:OperatorInfo>
               <cbs:OperatorID>101</cbs:OperatorID>
               <cbs:ChannelID>1</cbs:ChannelID>
            </cbs:OperatorInfo>
            <cbs:TimeFormat>
               <cbs:TimeType>1</cbs:TimeType>
               <cbs:TimeZoneID>1</cbs:TimeZoneID>
            </cbs:TimeFormat>
         </RequestHeader>  
         <QueryFreeUnitRequest>
            <bbs:QueryObj>
               <bbs:SubAccessCode>
                  <bbc:PrimaryIdentity>[filter_numero_celular]</bbc:PrimaryIdentity>
               </bbs:SubAccessCode>
            </bbs:QueryObj>
         </QueryFreeUnitRequest>
      </bbs:QueryFreeUnitRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;

/********************************************************************************************************* 
	 Haciendo uso de una funcion para obtener la respuesta del WS.
	*********************************************************************************************************/ 
   $today = "VAS".date("YmdHis");
   $queryFreeUnits = str_replace('[secuencia]', $today, $queryFreeUnits);
   
	
   if (!empty($data['filter_numero_celular'])) {
			$queryFreeUnits = str_replace('[filter_numero_celular]', $data['filter_numero_celular'], $queryFreeUnits);
   }
   
   /**************************************************************************************************************/
  $url = 'http://172.22.161.97:8080/services/BbServices';
  $action='QueryFreeUnit';
  $headers = array(
    'Content-Type: text/xml; charset=UTF-8',
    'Content-Length: '.strlen($queryFreeUnits),
    'SOAPAction: '.$action
  );
  
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $queryFreeUnits);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $huboError=0;
  if (($result = curl_exec($ch)) === FALSE) {
	$huboError=1;  
	$this->session->data['cbs_error']='Si';
		echo "Error (05)";
	exit(0);
   // die('cURL error: '.curl_error($ch)."<br/>\n");
  } else {
    //echo "Success!<br/>\n";
  }
  curl_close($ch);
 
   if ($huboError==0) {
   /*************************************************************************************************************/
   $xml_queryFreeUnits = new SimpleXMLElement($result);
   $nsqb = $xml_queryFreeUnits->getNamespaces(true);
   $xml_queryFreeUnits->registerXPathNamespace('bbs', $nsqb['bbs']);
   $xml_queryFreeUnits->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $results = array (); 
   
   
   foreach ($xml_queryFreeUnits->xpath('//QueryFreeUnitResult') as $QueryFreeUnitResult) {
	   
	   foreach ($QueryFreeUnitResult->xpath('bbs:FreeUnitItem') as $FreeUnitItem) {
	          $FreeUnitType = $FreeUnitItem->xpath('bbs:FreeUnitType');  
	          $FreeUnitTypeName = $FreeUnitItem->xpath('bbs:FreeUnitTypeName');
	          $MeasureUnitName  = $FreeUnitItem->xpath('bbs:MeasureUnitName');
	          $TotalUnusedAmount  = $FreeUnitItem->xpath('bbs:TotalUnusedAmount');
			  
		   	 foreach ($FreeUnitItem->xpath('bbs:FreeUnitItemDetail') as $FreeUnitDetail) {
		            $FreeUnitInstanceID = $FreeUnitDetail->xpath('bbs:FreeUnitInstanceID');
		            $ExpireTime         = $FreeUnitDetail->xpath('bbs:ExpireTime');
					$CurrentAmount      = $FreeUnitDetail->xpath('bbs:CurrentAmount');
					
			 /*******************************************/
             $sql = "select comverse_name
                       from accounts
                      where account_name = '".(string)$FreeUnitTypeName[0]."'";
	         /*******************************************/
	        $query = $this->mysql->query($sql);
            $billetera = $query->row['comverse_name'];	
					
			  $results[] = array((string)$FreeUnitType[0]
			                    ,(string)$TotalUnusedAmount[0]
								,(string)$FreeUnitInstanceID[0]
								,(string)$FreeUnitTypeName[0]
								,(string)$ExpireTime[0]
								,$billetera
								,(string)$CurrentAmount[0]);
	         }
		 
/*			  
			  $results[] = array((string)$FreeUnitType[0]
			                    ,(string)$TotalUnusedAmount[0]
								,(string)$FreeUnitInstanceID[0]
								,(string)$FreeUnitTypeName[0]
								,(string)$ExpireTime[0],$billetera);
*/								
	   }

    }

   }
    return $results;
	}	
    /*******************************************************************************/
}