<?php 

include_once("cartFunctions.php");


if (! isset($_SESSION["ShopperID"])) {
	
	header ("Location: login.php");
	exit;
}

$MainContent = "<div id='myShopCart' style='margin:auto'>";
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");

	// Retrieve from database and display shopping cart in a table

	$qry = "SELECT *, (Price*Quantity) AS Total
		FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry) ;
	$stmt->bind_param("i", $_SESSION["Cart"]); 
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();


	if (isset($_SESSION["deliveryModeNotSelected"])) {
		echo $_SESSION["deliveryModeNotSelected"];
		unset($_SESSION["deliveryModeNotSelected"]);
	}
	
	if ($result->num_rows > 0) {

 
		//Format and display the page header and header row of shopping cart page

		$MainContent .= "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		$MainContent .= "<div class='table-responsive' >";
		$MainContent .= "<table class='table table-hover'>"; 
		$MainContent .= "<thead class='cart-header'>";
		$MainContent .= "<tr>";
		$MainContent .= "<th width='250px'>Item</th>";
		$MainContent .= "<th width='90px'>Price (S$)</th>";
		$MainContent .= "<th width='60px'>Quantity</th>";
		$MainContent .= "<th width='120px'>Total (S$)</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "</tr>";
		$MainContent .= "</thead>";
		// Declare an array to store the shopping cart items in session variable 

		$_SESSION["Items"]=array();
		
		$subTotal = 0; 
		$noOfItems = 0;
		
		// Display the shopping cart content
		$MainContent .= "<tbody>";
		while ($row = $result->fetch_array()) {
			$MainContent .= "<tr>";
			$MainContent .= "<td style='width:50%'>$row[Name]<br />";
			$MainContent .= "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			$MainContent .= "<td>$formattedPrice</td>";
			$MainContent .= "<td>";
			
			$MainContent .= "<form action='cartFunctions.php' method='post'>";
			$MainContent .= "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { 
				if($i == $row["Quantity"])
					
					$selected = "selected";
				else
					$selected = ""; 
				$MainContent .= "<option value='$i' $selected>$i</option>";
			}
			$MainContent .= "</select>";	
			$MainContent .= "<input type='hidden' name='action' value='update' />";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			$MainContent .= "</form>";
			$MainContent .= "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			$MainContent .= "<td>$formattedTotal</td>";
			$MainContent .= "<td>";
			$MainContent .= "<form action='cartFunctions.php' method='post'>";
			$MainContent .= "<input type='hidden' name='action' value='remove' />";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			$MainContent .= "<input type='image' src='images/trash-can.png' alt='Remove Item'/>";
			$MainContent .= "</form>";
			$MainContent .= "</td>";
			$MainContent .= "</tr>";
			

		    // Store the shopping cart items in session variable as an associate array

			$_SESSION["Items"][]= array("productId"=>$row["ProductID"],
										"name"=>$row["Name"],
										"price"=>$row["Price"],
										"quantity"=>$row["Quantity"]);
			$subTotal += $row["Total"];
			
			$noOfItems += $row["Quantity"];
		}
		$MainContent .= "</tbody>";
		$MainContent .= "</table>";
		$MainContent .= "</div>";		
		
		// Display the subtotal at the end of the shopping cart
		
		$MainContent .= "<p style='text-align:right; font-size: 20px'>
						Total quantity: ".number_format($noOfItems,0);
		$_SESSION["Total quantity"] = round($noOfItems,0);
		$MainContent .= "<p style='text-align:right; font-size: 20px'>
						Subtotal: S$". number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);
		
		// Add PayPal Checkout button on the shopping cart page
		$express = "";
		$normal = "";
		if (isset($_SESSION["deliveryMode"])) {
			if ($_SESSION["deliveryMode"] == "express")
				$express = "checked";
			else
				$normal = "checked";
		}
		$MainContent .= "<div class='d-flex justify-content-between'>";
		$MainContent .= "<form name='deliveryMode' form method='POST'>";
		$MainContent .= "<input $normal onclick='this.form.submit()' type='radio' id='normal' name='delivery' value='normal'>";
		$MainContent .= "<label class='pl-2' onclick='this.form.submit()' for='normal'>Normal Delivery (Within 2 working days) $5</label><br>";
		$MainContent .= "<input $express onclick='this.form.submit()' type='radio' id='express' name='delivery' value='express'>";
		$MainContent .= "<label class='pl-2' onclick='this.form.submit()' for='express'>Express Delivery (Within 24 hours) $10</label><br>";
		$MainContent .= "</form>";

		$totalAmt = $subTotal;
		if ($normal == "checked")
		{
			$totalAmt += 5;
			$MainContent .= "<div><p style='text-align:right; font-size: 20px'>
						Total: S$". number_format($totalAmt, 2) . "</p>";
		$_SESSION["Total"] = round($totalAmt, 2);
		}
		else if ($express == "checked")
		{
			if ($subTotal > 200)
			{
			$MainContent .= "<div><p style='text-align:right; font-size: 20px'>
						Total: S$". number_format($subTotal, 2) . "</p>";
			$_SESSION["Total"] = round($subTotal, 2);
			}
			else
			{
				$totalAmt += 10;
				$MainContent .= "<div><p style='text-align:right; font-size: 20px'>
						Total: S$". number_format($totalAmt, 2) . "</p>";
				$_SESSION["Total"] = round($totalAmt, 2);
			}
		}
		$MainContent .= "<form method='post' action='checkoutProcess.php'>";
		$MainContent .= "<input type='image'
						src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		$MainContent .= "</form></div>";
		$MainContent.= "</div>";
	}
	else {
		$MainContent .= "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); 
}
else {
	$MainContent .= "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
$MainContent .= "</div>";
if (isset($_POST["delivery"])) {
	$_SESSION["deliveryMode"] = $_POST["delivery"];

	if ($_POST["delivery"] == "normal")
		$_SESSION["deliveryCharge"] = 5;
	else
		$_SESSION["deliveryCharge"] = 10;
	$MainContent = "";
	echo "<meta http-equiv='refresh' content='0'>";
}

include("MasterTemplate.php"); 
?>
