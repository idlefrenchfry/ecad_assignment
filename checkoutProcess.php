<?php
session_start();
include_once("mypaypal.php");
include_once("mysql_conn.php");
$MainContent = "";

if($_POST) 
{
	if (!isset($_SESSION["deliveryMode"])) {
		$_SESSION["deliveryModeNotSelected"] = "<script>alert('Please select a delivery mode!');</script>";
		header("Location: shoppingCart.php");
		exit;
	}
	$errorMsg = "The following Items are out of stock: <br /><ul>";
	$checkOutOfStock = FALSE;

	foreach($_SESSION['Items'] as $item) {
		$qry = "SELECT Quantity FROM Product WHERE ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $item["productId"]);
		$stmt->execute();
		$quantityRes = $stmt->get_result();

		while ($res = $quantityRes->fetch_array()) {
			if ($res["Quantity"] < $item["quantity"]) {
				$errorMsg .= "<li>Product $item[productId] : $item[name]</li>";
				$checkOutOfStock = TRUE;
			}
		}
	}

	if ($checkOutOfStock) {
		$errorMsg .= "</ul>Please return to shopping cart to ammend your purchase!<br />";
		$MainContent .= $errorMsg;
		include("MasterTemplate.php");
		exit;
	}

	$paypal_data = '';
	
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	$qry = "SELECT * FROM gst ORDER BY EffectiveDate DESC LIMIT 1";
	$result = $conn->query($qry);
	$currentGst = $result->fetch_array()["TaxRate"];
	$_SESSION["Tax"] = round($_SESSION["SubTotal"]*($currentGst / 100), 2);
	$_SESSION["ShipCharge"] = $_SESSION["deliveryCharge"];
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                                 $_SESSION["Tax"] + 
												 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
			  '&BRANDNAME='.urlencode("Egiftr e-Gift Shop").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL ).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		
		$MainContent .= "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}


if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	
	foreach($_SESSION['Items'] as $key=>$item) 
	{
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["Tax"] + 
								                 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		foreach($_SESSION['Items'] as $item) 
		{
			$qry = "UPDATE Product SET Quantity=IF(Quantity > ?, Quantity-?, 0) WHERE ProductID = ?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("iii", $item["quantity"], $item["quantity"], $item["productId"]);
			$stmt->execute();
			$stmt->close();
		}
	
		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		$qry = "UPDATE shopcart SET OrderPlaced=1, Quantity=?, 
				SubTotal=?, ShipCharge=?, Tax=?, Total=?
				WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iddddi", $_SESSION["NumCartItem"], $_SESSION["SubTotal"], 
						  $_SESSION["ShipCharge"], $_SESSION["Tax"],
						  $total, $_SESSION["Cart"]);
		$stmt->execute();
		$stmt->close();

		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
										   $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		   {
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
				
			$ShipCountry = urldecode(
			               $httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
			
			
			$qry = "INSERT INTO orderdata (ShipName, ShipAddress,
										   ShipCountry,
										   ShipEmail, ShopCartID)
					VALUES(?, ?, ?, ?, ?)";
			
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("ssssi", $ShipName, $ShipAddress, $ShipCountry, $ShipEmail, $_SESSION["Cart"]);
			$stmt->execute();
			$stmt->close();
			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $result->fetch_array();
			$_SESSION["OrderID"] = $row["OrderID"];
			$conn->close();
			$_SESSION["NumCartItem"] = 0;
			unset($_SESSION["Cart"]);
			unset($_SESSION["deliveryMode"]);
			unset($_SESSION["deliveryCharge"]);

			header("Location: orderConfirmed.php");
			exit;
		} 
		else 
		{
		    $MainContent .= "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			 urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			$MainContent .= "<pre>";
			$MainContent .= print_r($httpParsedResponseAr);
			$MainContent .= "</pre>";
			
			$conn->close();
		}
	}
	else {
		$MainContent .= "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}

include("MasterTemplate.php"); 
?>