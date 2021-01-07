<?php
//Connection Parameters
$servername = 'localhost';
$username = 'root';
$userpwd = '';
$dbname = 'ecad_assignment'; 

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);	
}
?>
