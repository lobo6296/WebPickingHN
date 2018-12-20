<?php
/*
  Autor: Vladimiro Rivera
  Fecha: 31/08/2016
 
  Metodos implementados:
    BalanceAdjustment          Pendiente
	FreeUnitAdjustment         Pendiente
	getQueryRechargeLogResult  Pendiente
	getBalanceResult           Corregido
*/
class ModelSoaArservices extends Model {

public function BalanceAdjustment($data = array()) {
$adjustment = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
   <soapenv:Header/>
   <soapenv:Body>
      <ars:AdjustmentRequestMsg>
			<RequestHeader>
				<cbs:Version>1</cbs:Version>
				<cbs:BusinessCode>1</cbs:BusinessCode>
				<cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
				<cbs:OwnershipInfo>
					<cbs:BEID>101</cbs:BEID>
					<cbs:BRID>101</cbs:BRID>
				</cbs:OwnershipInfo>
				<cbs:AccessSecurity>
					<cbs:LoginSystemCode>filter_bmploginsystemcode</cbs:LoginSystemCode>
					<cbs:Password>filter_bmppassword</cbs:Password>
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
				 <AdjustmentRequest>
					<ars:AdjustmentSerialNo>[secuencia]</ars:AdjustmentSerialNo>
					<ars:AdjustmentObj>
					   <ars:SubAccessCode>
					   <arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
					   </ars:SubAccessCode>
					</ars:AdjustmentObj>
					<ars:OpType>2</ars:OpType>
					<ars:AdjustmentInfo>
					   <arc:BalanceType>filter_Type</arc:BalanceType>
					   <arc:BalanceID>filter_InstanceID</arc:BalanceID>
					</ars:AdjustmentInfo>
					<ars:AdditionalProperty>
					   <arc:Code>C_ADJUST_TYPE</arc:Code>
					   <arc:Value>0</arc:Value>
					</ars:AdditionalProperty>
				 </AdjustmentRequest>
      </ars:AdjustmentRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $adjustment = str_replace('[secuencia]', $secuencia, $adjustment);
   
  foreach ($data as $key=>$parametro) {
  	$adjustment = str_replace($key,$parametro,$adjustment);
	$url = str_replace($key,$parametro,$url);
  } 
  
  $result=$soa->getResponse($adjustment,'Adjustment',$url);
  
 if ($result===FALSE)
  {
   $huboError=1;	  
  }
  
  
if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('ars', $ns['ars']);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);

   $Version=$xml->xpath('//cbs:Version');
   $ResultCode=$xml->xpath('//cbs:ResultCode');
   $ResultDesc=$xml->xpath('//cbs:ResultDesc');
   $AcctKey=$xml->xpath('//ars:AcctKey');
   $AdjustmentSerialNo=$xml->xpath('//ars:AdjustmentSerialNo');
 
   $results[] = array(
				'version'            => (string)$Version[0],
				'resultcode'         => (string)$ResultCode[0],
				'resultdesc'         => (string)$ResultDesc[0],
				'acctkey'            => (string)$AcctKey[0],
				'adjustmentserialno' => (string)$AdjustmentSerialNo[0]
			);
   }
   else {
   $results[] = array(
				'version'            => "",
				'resultcode'         => "",
				'resultdesc'         => "",
				'acctkey'            => "",
				'adjustmentserialno' => ""
			);	
   } 
 
 return $results;     
  
}	
	
public function delFreeUnit($data = array()) {	
$adjustment = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
	<soapenv:Header/>
	<soapenv:Body>
		<ars:AdjustmentRequestMsg>
			<RequestHeader>
				<cbs:Version>1</cbs:Version>
				<cbs:BusinessCode>1</cbs:BusinessCode>
				<cbs:MessageSeq>[secuencia]</cbs:MessageSeq>
				<cbs:OwnershipInfo>
					<cbs:BEID>101</cbs:BEID>
					<cbs:BRID>101</cbs:BRID>
				</cbs:OwnershipInfo>
				<cbs:AccessSecurity>
					<cbs:LoginSystemCode>filter_bmploginsystemcode</cbs:LoginSystemCode>
					<cbs:Password>filter_bmppassword</cbs:Password>
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
			<AdjustmentRequest>
				<ars:AdjustmentSerialNo>[secuencia]</ars:AdjustmentSerialNo>
				<ars:AdjustmentObj>
					<ars:SubAccessCode>
						<arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
					</ars:SubAccessCode>
				</ars:AdjustmentObj>
				<ars:OpType>2</ars:OpType>			
            <ars:FreeUnitAdjustmentInfo>
               <ars:FreeUnitInstanceID>filter_InstanceID</ars:FreeUnitInstanceID>
               <ars:FreeUnitType>filter_Type</ars:FreeUnitType>
               <ars:AdjustmentType>1</ars:AdjustmentType>
               <ars:AdjustmentAmt>0</ars:AdjustmentAmt>
            </ars:FreeUnitAdjustmentInfo>				
				<ars:AdditionalProperty>
					<arc:Code>C_REMARKS</arc:Code>
					<arc:Value>Ajuste_[secuencia]</arc:Value>
				</ars:AdditionalProperty>
				<ars:AdditionalProperty>
					<arc:Code>C_ADJUST_TYPE</arc:Code>
					<arc:Value>2</arc:Value>
				</ars:AdditionalProperty>
			</AdjustmentRequest>
		</ars:AdjustmentRequestMsg>
	</soapenv:Body>
</soapenv:Envelope>
EOD;
  $url       = 'http://filter_ip:8080/services/ArServices';
  $huboError = 0;
  $secuencia = "Testing".date("YmdHis");
  $adjustment = str_replace('[secuencia]', $secuencia, $adjustment);
   
  foreach ($data as $key=>$parametro) {
  	$adjustment = str_replace($key,$parametro,$adjustment);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$this->getResponse($adjustment,'Adjustment',$url);
  
 if ($result===FALSE)
  {
   $huboError=1;	  
  }
  
  if ($result=== FALSE) {
	  $huboError=1;
  }
  
  if ($huboError==0) {
   $xml = new SimpleXMLElement($result);

   $ns = $xml->getNamespaces(true);
   $xml->registerXPathNamespace('ars', $ns['ars']);
   $xml->registerXPathNamespace('cbs', $ns['cbs']);

   $Version=$xml->xpath('//cbs:Version');
   $ResultCode=$xml->xpath('//cbs:ResultCode');
   $ResultDesc=$xml->xpath('//cbs:ResultDesc');
   $AcctKey=$xml->xpath('//ars:AcctKey');
   $AdjustmentSerialNo=$xml->xpath('//ars:AdjustmentSerialNo');
 
   $results[] = array(
				'version'            => (string)$Version[0],
				'resultcode'         => (string)$ResultCode[0],
				'resultdesc'         => (string)$ResultDesc[0],
				'acctkey'            => (string)$AcctKey[0],
				'adjustmentserialno' => (string)$AdjustmentSerialNo[0]
			);
   }
   else {
   $results[] = array(
				'version'            => "",
				'resultcode'         => "",
				'resultdesc'         => "",
				'acctkey'            => "",
				'adjustmentserialno' => ""
			);	
   } 
 
 return $results;   
}

public function getQueryRechargeLogResult($data = array()) {
$QueryRechargeLog = <<<EOD
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
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
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
                  xmlns:ars="http://www.huawei.com/bme/cbsinterface/arservices" 
				  xmlns:cbs="http://www.huawei.com/bme/cbsinterface/cbscommon" 
				  xmlns:arc="http://cbs.huawei.com/ar/wsservice/arcommon">
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
               <cbs:LoginSystemCode>filter_bmploginsystemcode</cbs:LoginSystemCode>
               <cbs:Password>filter_bmppassword</cbs:Password>
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
                  <arc:PrimaryIdentity>filter_numero_celular</arc:PrimaryIdentity>
               </ars:SubAccessCode>
            </ars:QueryObj>
         </QueryBalanceRequest>
      </ars:QueryBalanceRequestMsg>
   </soapenv:Body>
</soapenv:Envelope>
EOD;
  $soa = New Soa();
  $url       = 'http://filter_ip:8080/services/ArServices';
  $secuencia = "Testing".date("YmdHis");
  $queryBalance = str_replace('[secuencia]', $secuencia, $queryBalance);
   
  foreach ($data as $key=>$parametro) {
  	$queryBalance = str_replace($key,$parametro,$queryBalance);
	$url = str_replace($key,$parametro,$url);
  } 

  $result=$soa->getResponse($queryBalance,'QueryBalance',$url);
  /*
    Analizando respuesta: 
   */  
   $xml_queryBalance = new SimpleXMLElement($result);
   $nsqb = $xml_queryBalance->getNamespaces(true);
   $xml_queryBalance->registerXPathNamespace('ars', $nsqb['ars']);
   $xml_queryBalance->registerXPathNamespace('arc', $nsqb['arc']);
   $xml_queryBalance->registerXPathNamespace('cbs', $nsqb['cbs']);
   
   $ResultCode = $xml_queryBalance->xpath('//ResultHeader/cbs:ResultCode');
   $ResultDesc = $xml_queryBalance->xpath('//ResultHeader/cbs:ResultDesc');
   $results = array();
  
	$results['response'] = array(
	          'resultcode' => (int)$ResultCode[0],
			  'resultdesc' => (string)$ResultDesc[0]
	);

   if ((int)$ResultCode[0]==0) {
   /*************************************************************************************************************/

   foreach ($xml_queryBalance->xpath('//ars:BalanceResult') as $BalanceResult) {
	 $BalanceType     = $BalanceResult->xpath('arc:BalanceType');
	 $BalanceTypeName = $BalanceResult->xpath('arc:BalanceTypeName');
	 $TotalAmount     = $BalanceResult->xpath('arc:TotalAmount');
	 foreach ($BalanceResult->xpath('arc:BalanceDetail') as $BalanceDetail) {
		 $BalanceInstanceID = $BalanceDetail->xpath('arc:BalanceInstanceID');
		 $ExpireTime        = $BalanceDetail->xpath('arc:ExpireTime');
	 }
     /*******************************************/
     $sql = "select comverse_name
          from testing.accounts
         where balancetype = '".$BalanceType[0]."'";
	 /*******************************************/
	 $query = $this->mysql->query($sql);
     $billetera = $query->row['comverse_name'];
	 
	 $infobilleteras[] = array((string)$BalanceType[0]
	                   ,(string)$TotalAmount[0]
					   ,(string)$BalanceInstanceID[0]
					   ,(string)$BalanceTypeName[0]
					   ,(string)$ExpireTime[0]
					   ,(string)$billetera);
    }
	$results['response']['billeteras'] = $infobilleteras;
   }
    return $results;
 }
}