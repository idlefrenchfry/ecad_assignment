<?php 
// Detect the current session
session_start();

// Content to be 60% container width
$MainContent = "<div style='width:60%; margin:auto;'>";

// Page Header
$MainContent .= "<div class='row' style='padding:5px'>";
$MainContent .= "<div class='col-12'>";
$MainContent .= "<span class='page-title'>$_GET[catName]</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Establishe database connection
include_once("mysql_conn.php"); 

$MainContent .= "<div class='row' style='padding:5px'>"; // Start of main content row

// Get Category ID
$cid = $_GET["cid"]; 

// Get products based on category ID
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
        FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
        WHERE cp.CategoryID = ?";

$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$MainContent .= "<div class='card-deck flex-wrap justify-content-center'>"; // Start of card deck

// TO DO: Alphabetical Order

while ($row = $result->fetch_array()) 
{

    $MainContent .= "<div class='card' style='min-width: 250px; max-width:250px; margin-bottom: 10px;'>"; // Start of card

    // Get product details
    $product = "productDetails.php?pid=$row[ProductID]";
    $formattedPrice = number_format($row["Price"], 2);
    $img = "./Images/products/$row[ProductImage]";

    // create card content
    $MainContent .= "<img class='card-img-top' src='$img' alt='Category Image'>";
    $MainContent .= "<div class='card-body'>"; // Start of card body
    $MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
    $MainContent .= "<p class='card-text text-primary' style='font-size: 1.3em'>Price: S$ $formattedPrice</p>";
    $MainContent .= "<a href='$product' class='btn btn-primary'>See Details</a>";

    $MainContent .= "</div>"; // End of card body
    $MainContent .= "</div>"; // End of card

}

$MainContent .= "</div>"; // End of card deck
$MainContent .= "</div>"; // End of main content row

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");  
?>
