<?php 
// Include the code that contains shopping cart's functions
include_once("cartFunctions.php");

// Check if user logged in 
if (! isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

$MainContent = "<div id='myShopCart' style='margin:auto'>";
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");
	// To Do 1 (Practical 4): 
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price*Quantity) AS Total
		FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry) ;
	$stmt->bind_param("i", $_SESSION["Cart"]); // “i" - integer
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		// To Do 2 (Practical 4): Format and display 
		// the page header and header row of shopping cart page
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
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
		// Declare a variable to compute subtotal before tax
		$subTotal = 0; 
		// To Do 3 (Practical 4):
		// Display the shopping cart content
		$MainContent .= "<tbody>";
		while ($row = $result->fetch_array()) {
			$MainContent .= "<tr>";
			$MainContent .= "<td style='width:50%'>$row[Name]<br />";
			$MainContent .= "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			$MainContent .= "<td>$formattedPrice</td>";
			$MainContent .= "<td>";
			// Update quantity of purchase
			$MainContent .= "<form action='cartFunctions.php' method='post'>";
			$MainContent .= "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { // To populate drop-down list from 1 to 10
				if($i == $row["Quantity"])
					// Select the drop-down list item with value same as the quantity of purchase
					$selected = "selected";
				else
					$selected = ""; // No specific item is selected
				$MainContent .= "<option value='$i' $selected>$i</option>";
			}
			$MainContent .= "</select>";	
			$MainContent .= "<input type='hidden' name='action' value='update' />";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			$MainContent .= "</form>";
			$MainContent .= "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			$MainContent .= "<td>$formattedTotal</td>";
			// Remove Item
			$MainContent .= "<td>";
			$MainContent .= "<form action='cartFunctions.php' method='post'>";
			$MainContent .= "<input type='hidden' name='action' value='remove' />";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			$MainContent .= "<input type='image' src='images/trash-can.png' alt='Remove Item'/>";
			$MainContent .= "</form>";
			$MainContent .= "</td>";
			$MainContent .= "</tr>";
			// To Do 6 (Practical 5):
		    // Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][]= array("productId"=>$row["ProductID"],
										"name"=>$row["Name"],
										"price"=>$row["Price"],
										"quantity"=>$row["Quantity"]);
			// Accumulate the running sub-total
			$subTotal += $row["Total"];
		}
		$MainContent .= "</tbody>";
		$MainContent .= "</table>";
		$MainContent .= "</div>";		
		// To Do 4 (Practical 4): 
		// Display the subtotal at the end of the shopping cart
		$MainContent .= "<p style='text-align:right; font-size: 20px'>
						Subtotal = S$". number_format($subTotal, 2);
		$_SESSION["SubTotal"] = round($subTotal, 2);
		// To Do 7 (Practical 5):
		// Add PayPal Checkout button on the shopping cart page
		$MainContent .= "<form method='post' action='checkoutProcess.php'>";
		$MainContent .= "<input type='image' style='float:right;'
						src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		$MainContent .= "</form></p>";
	}
	else {
		$MainContent .= "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	$MainContent .= "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
$MainContent .= "</div>";

include("MasterTemplate.php"); 
?>
