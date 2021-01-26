<?php
session_start();

// Check if user logged in 
if (! isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}


include_once("mysql_conn.php"); 
$pid=$_GET["pid"]; // Read Product ID from query string
if (isset($_POST['productDetails']))
{
    $pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
}
$conn->close();

?>