<?php 
// Detect the current session
session_start();

// Content to be 60% container width
$MainContent = "<div style='width:90%; margin:auto;'>";

// Page Header
$MainContent .= "<div class='row' style='padding:5px;'>";
$MainContent .= "<div class='row'>";
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

$MainContent .= "<div class='card-deck d-flex justify-content-start;'>"; // Start of card deck

// TO DO: Alphabetical Order
$percentChange = 0;

while ($row = $result->fetch_array()) 
{

    $MainContent .= "<div class='card' style='width: 18rem; margin-bottom: 10px;'>"; // Start of card

    // Get product details
    $product = "productDetails.php?pid=$row[ProductID]";
    $formattedPrice = number_format($row["Price"], 2);
    $offerPrice = number_format($row["OfferedPrice"],2);
    $onOffer = number_format($row["Offered"],1);
    $img = "./Images/products/$row[ProductImage]";

    // create card content
    $MainContent .= "<img class='card-img-top' src='$img' alt='Category Image'>";
    $MainContent .= "<div class='card-body'>"; // Start of card body
    if ($onOffer == 1)
    {
        $percentChange = (1 - $formattedPrice / $offerPrice) * 100;
        $percentChange = round($percentChange, 0);
        $percentChange = abs($percentChange);
        $MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
        $MainContent .= "<span style='font-size:14px; color: grey;'>
                    <del>S$ $formattedPrice </del></span>";
        $MainContent .= "&nbsp<span style='color:#1daade; font-size:20px; font-weight:700;'>S$ $offerPrice</span>";
        $MainContent .="<h3 style='color:#e80d8b; font-weight:bold;'>-$percentChange%</h3>";    

    }
    else{
         
        $MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
        $MainContent .= "<span style='color:#1daade; font-size:20px; font-weight:700;'>S$ $formattedPrice</span>";
        
    }
    $MainContent .= "</div>"; // End of card body
    $MainContent .= "<a href='$product' class='btn btn-primary btn-block'>See Details</a>";
    $MainContent .= "</div>"; // End of card

}

$MainContent .= "</div>"; // End of card deck
$MainContent .= "</div>"; // End of main content row

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");  
?>
