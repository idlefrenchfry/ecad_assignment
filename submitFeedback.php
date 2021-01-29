<?php

// Detect the current session
session_start();
$MainContent = "";

// Read the data input from previous page
$subject = $_POST["subject"];
$rank = $_POST["rank"];
$content = $_POST["content"];
$shopperID = $_SESSION["ShopperID"];

include_once("mysql_conn.php");

// insert into database
$qry = "INSERT INTO Feedback(ShopperID, Subject, Content, Rank) VALUES(?, ?, ?, ?)";
$stmt = $conn->prepare($qry);
$stmt->bind_param("sssi", $shopperID, $subject, $content, $rank);
if ($stmt->execute()) {
    $MainContent .= "<h3 class='text-success'>Feedback submitted succesfully!</h3>";
}

else {
    $MainContent .= "<h3 style='coler:red'>Error in inserting record.</h3>";
}

$stmt->close();
$conn->close();

include("MasterTemplate.php");

?>