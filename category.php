<?php 
session_start();

// Content to be 60% container width
$MainContent = "<div style='width:60%; margin:auto;'>";

// Page Header
$MainContent .= "<div class='row' style='padding:5px'>";
$MainContent .= "<div class='col-12'>";
$MainContent .= "<span class='page-title'>Product Categories</span>";
$MainContent .= "<p>Select a category listed below:</p>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Establish data connection
include_once("mysql_conn.php");

$MainContent .= "<div class='row' style='padding:5px'>"; // Start of main content row

// Get all categories
$qry = "SELECT * FROM Category";
$result = $conn->query($qry);

$MainContent .= "<div class='card-deck flex-wrap justify-content-center'>"; // Start of card deck

// TO DO: Alphabetical Order

while ($row = $result->fetch_array()) {
    $MainContent .= "<div class='card' style='min-width: 250px; max-width:250px; margin-bottom: 10px;'>"; // Start of card

    // Get category details
    $catName = urlencode($row["CatName"]);
    $catProduct = "catProduct.php?cid=$row[CategoryID]&catName=$catName";
    $img = "./Images/category/$row[CatImage]";

    // create card content
    $MainContent .= "<img class='card-img-top' src='$img' alt='Category Image'>";
    $MainContent .= "<div class='card-body'>"; // Start of card body
    $MainContent .= "<h5 class='card-title'>$row[CatName]</h5>";
    $MainContent .= "<p class='card-text'>$row[CatDesc]</p>";
    $MainContent .= "<a href='$catProduct' class='btn btn-primary'>See Products</a>";

    $MainContent .= "</div>"; // End of card body
    $MainContent .= "</div>"; // End of card
}

$MainContent .= "</div>"; // End of card deck
$MainContent .= "</div>"; // End of main content row

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php"); 
?>