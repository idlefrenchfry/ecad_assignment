<?php 
session_start();

if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'add':
        	addItem();
            break;
        case 'update':
            updateItem();
            break;
		case 'remove':
            removeItem();
            break;
    }
}

function addItem() {
	// Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 1
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	// Check if a shopping cart exist, if not create a new shopping cart
	if (! isset($_SESSION["Cart"])) {
		//Create a shopping cart for the shopper
		$qry = "INSERT INTO Shopcart(ShopperID) VALUES(?)";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["ShopperID"]); // "i" - integer
		$stmt->execute();
		$stmt->close();
		$qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
		$result = $conn->query($qry);
		$row = $result->fetch_array();
		$_SESSION["Cart"] = $row["ShopCartID"];
	}
  	// If the ProductID exists in the shopping cart, 
  	// update the quantity, else add the item to the Shopping Cart.
  	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ii", $_SESSION["Cart"], $pid);  // "i" - integer
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$addNewItem = 0;
	if ($result->num_rows > 0) { // Selected product exists in shopping cart
		// increase the quantity of purchase
		$qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?, 10)
				WHERE ShopCartID=? AND ProductID=?";
		$stmt = $conn->prepare($qry) ;
		// “iii" - 3 integers
		$stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
		$stmt->execute();
 		$stmt->close();
	 }
	else { //Selected product has yet to be added to shopping cart
		$qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
				SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
		$stmt = $conn->prepare($qry) ;
		// “iiii" - 4 integers
		$stmt->bind_param("iiii", $_SESSION["Cart"], $pid, $quantity, $pid);
		$stmt->execute();
		$stmt->close();
		$addNewltem = 1;
		}
  	$conn->close();
  	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])) {
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem;
	}
	else {
		$_SESSION["NumCartItem"] = 1;
	}
	// Redirect shopper to shopping cart page
	header ("Location: shoppingCart.php");
	exit;
}

function updateItem() {
	// Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 2
	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity" ];
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	$qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
	$stmt = $conn->prepare($qry) ;
	$stmt->bind_param("iii", $quantity, $pid, $cartid); // "i" - integer
	$stmt->execute();
	$stmt->close();
	$conn->close();
	header ("Location: shoppingCart.php");
	exit;

}

function removeItem() {
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}
	// TO DO 3
	// Write code to implement: if a user clicks on "Remove" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	
}		
?>
