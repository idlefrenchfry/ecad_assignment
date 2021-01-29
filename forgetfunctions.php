<?php

session_start();


$email = $_POST["email"];


// Close database connection
$conn->close();


include("MasterTemplate.php"); 
?>