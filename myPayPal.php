<?php
$PayPalMode 		= 'sandbox';
$PayPalApiUsername 	= 'sb-xlezv3951511_api1.business.example.com'; 		
$PayPalApiPassword 	= 'NTLTWTC9FN7FP3CH'; 		
$PayPalApiSignature = 'At7M.vjpvoOSoEuUejMvbAJc494kAAhCqtUD2Y1sXe80Ctc5jb-1XQlQ'; 		
$PayPalCurrencyCode = 'SGD'; 	
$PayPalReturnURL 	= 'http://localhost:8081/ecad_assignment/checkoutProcess.php';               
$PayPalCancelURL 	= 'http://localhost:8081/ecad_assignment/shoppingcart.php'; 
                	
function PPHttpPost($methodName_, $nvpStr_, $PayPalApiUsername, $PayPalApiPassword, 
                    $PayPalApiSignature, $PayPalMode) {
	
	$API_UserName = urlencode($PayPalApiUsername);
	$API_Password = urlencode($PayPalApiPassword);
	$API_Signature = urlencode($PayPalApiSignature);
			
	if($PayPalMode=='sandbox'){
		$paypalmode = '.sandbox';
	}
	else {
		$paypalmode = '';
	}
	
	$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
	$version = urlencode('98');
		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);			
			
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);	
				
	$nvpreq = "METHOD=$methodName_&VERSION=$version";
	$nvpreq .= "&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
			
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
	$httpResponse = curl_exec($ch);
		
	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}
		
	
	$httpResponseAr = explode("&", $httpResponse);
		
	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}
		
	if((sizeof($httpParsedResponseAr) == 0) || 
	   (!array_key_exists('ACK', $httpParsedResponseAr))) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
		
	return $httpParsedResponseAr;
}
?>