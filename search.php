<?php 

// TO DO: Haven't touched this page

// Detect the current session
session_start();

// HTML Form to collect search keyword and submit it to the same page 
// in server
$MainContent = "<div style='width:80%; margin:auto;'>"; // Container
$MainContent .= "<form name='frmSearch' method='get' action=''>";
$MainContent .= "<div class='form-group row'>"; // 1st row
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Product Search</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End of 1st row
$MainContent .= "<div class='form-group row'>"; // 2nd row
$MainContent .= "<label for='keywords' 
                  class='col-sm-3 col-form-label'>Product Title:</label>";
$MainContent .= "<div class='col-sm-6'>";
$MainContent .= "<input class='form-control' name='keywords' id='keywords' 
                  type='search' />";
$MainContent .= "</div>";
$MainContent .= "<div class='col-sm-3'>";
$MainContent .= "<button type='submit'>Search</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";  // End of 2nd row
$MainContent .= "</form>";

// The search keyword is sent to server
if (isset($_GET['keywords'])) {
    $SearchText= $_GET["keywords"];
    $search = "%$_GET[keywords]%";

    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
    // contains the keyword entered by shopper, and display them in a table.
    include_once("mysql_conn.php");

    $qry = "SELECT ProductTitle, ProductID, Price, ProductDesc FROM product
            WHERE ProductTitle LIKE ?
            OR ProductDesc LIKE ?";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ss", $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    // Close connection and statement
    $stmt->close();
    $conn->close();
    
    $MainContent .= "<p style='font-size: 15px; font-weight: bold;'>Search Results for $SearchText: </p>";

    // Table format
    $MainContent .= "<table class='table table-striped'>";
    $MainContent .= "<thead class='thead-dark'>";
    $MainContent .= "<tr>";
    $MainContent .= "<th scope='col'>Title</th>";
    $MainContent .= "<th scope='col'>Description</th>";
    $MainContent .= "<th scope='col'>Price</th>";
    $MainContent .= "</tr>";
    $MainContent .= "</thead>";
    $MainContent .= "<tbody>";

    while ($row = $result->fetch_array())
    {
        // Original code
        // $MainContent .= "<p><a href='$product'>$row[ProductTitle]</a></p>";

        // code for table display
        $product = "productDetails.php?pid=$row[ProductID]";
        $MainContent .= "<tr>";
        $MainContent .= "<td><a href='$product'>$row[ProductTitle]</a></td>";
        $MainContent .= "<td style='width: 60%;'>$row[ProductDesc]</td>";
        $MainContent .= "<td>$"."$row[Price]</td>";
        $MainContent .= "</tr>";
    }

    $MainContent .= "</tbody>";
    $MainContent .= "</table>";
    
	// To Do (DIY): End of Code
}

$MainContent .= "</div>"; // End of Container
include("MasterTemplate.php");
?>