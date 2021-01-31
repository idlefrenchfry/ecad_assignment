<?php 
// Detect the current session
session_start();
	
if(isset($_SESSION["OrderID"])) {	
	$MainContent = "<p>Checkout successful. Your order number is $_SESSION[OrderID]</p>";
	$MainContent .= "<p>Thank you for your purchase.&nbsp;&nbsp;";
	$MainContent .= '<a href="index.php">Continue shopping</a></p>';
}

include("MasterTemplate.php"); 
?>