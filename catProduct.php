<?php 
// Detect the current session
session_start();

// Content to be 60% container width
$MainContent = "<div style='width:60%; margin:auto;'>";

// Page Header
$MainContent .= "<div class='row' style='padding:5px; text-align:center;'>";
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
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.OfferedPrice, p.Offered
        FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID
        WHERE cp.CategoryID = ? ORDER BY p.ProductTitle ASC";

$stmt = $conn->prepare($qry); //Execute the SQL statement
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
    $offerPrice = number_format($row["OfferedPrice"],1);
    $onOffer = number_format($row["Offered"],1);
    $img = "./Images/products/$row[ProductImage]";

    // create card content
    $MainContent .= "<img class='card-img-top' src='$img' alt='Category Image'>";
    $MainContent .= "<div class='card-body'>"; // Start of card body
    if ($onOffer == 1)
    {
        
        $MainContent .= "<div style='max-height: 100px;'>";
        $MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
        $MainContent .= "</div>";
        $MainContent .= "<span style='font-weight: bold; color: grey;'>
                    <del>S$ $formattedPrice</del></span>";
        $MainContent .= "<p class='card-text' style='font-size: 1.3em; color:red; font-weight:bold;'>S$ $offerPrice</p>";
        $MainContent .="<h3 style='color:red; font-weight:bold;'>On Offer!</h3>";
        $MainContent .= "<a href='$product' class='btn btn-primary'>See Details</a>";

        

    }
    else{
      
        $MainContent .= "<div style='max-height: 100px;'>";
        $MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
        $MainContent .= "</div>";
        $MainContent .= "<div style='max-height: 100px;'>";
        $MainContent .= "<p class='card-text text-primary' style='font-size: 1.3em'>S$ $formattedPrice</p>";
        $MainContent .= "</div>";
        $MainContent .= "<div style='max-width: 100%;'>";
        $MainContent .= "<br><br><br><br><a href='$product' class='btn btn-primary' style='max-width:100%;'>See Details</a>";
        $MainContent .= "</div>";
        
    }
    $MainContent .= "</div>"; // End of card body
    $MainContent .= "</div>"; // End of card

}

$MainContent .= "</div>"; // End of card deck
$MainContent .= "</div>"; // End of main content row

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");  
?>
