<?php 

// TO DO: Haven't touched this page yet

session_start(); // Detect the current session
// Create a container, 90% width of viewport
$MainContent = "<div style='width:90%; margin:auto;'>";

$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php"); 
$qry = "SELECT * from product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// To Do 1:  Display Product information. Starting ....

while ($row = $result->fetch_array()) 
{
    // Display Page Header - 
    // Product's name is read from the "ProductTitle" column of "product" table.

    $MainContent .= "<div class='row'>";
    $MainContent .= "<div class='col-sm-12' style='padding: 5px'>";
    $MainContent .= "<span class='page-title'>$row[ProductTitle]</span>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";

    // Start a new Row
    $MainContent .= "<div class='row'>";
    
    // Left Column - display the product's description
    $MainContent .= "<div class='col-sm-9' style='padding: 5px;'>";
    $MainContent .= "<p>$row[ProductDesc]</p>";

    // Left Column - display the product's Specification
    $qry = "SELECT s.SpecName, ps.SpecVal FROM productspec ps
            INNER JOIN specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=?
            ORDER BY ps.priority";

    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();

    while($row2 = $result2->fetch_array()) {
        $MainContent .= $row2["SpecName"].": ".$row2["SpecVal"]."<br / >";
    }

    $MainContent .= "</div>";

    // Right Column - display the product's image
    $img = "./Images/products/$row[ProductImage]";
    $MainContent .= "<div class='col-sm-3' style='vertical-align: top; padding: 5px;'>";
    $MainContent .= "<p><img style='max-height:200px; max-width: 200px' src=$img /></p>";

    // Right Column - display the product's price
    $formattedPrice = number_format($row["Price"], 2);
    $offeredPrice = number_format($row["OfferedPrice"],2);

    if ($offeredPrice <=0)
    {
        $MainContent .="Price: S$ $formattedPrice";
    }
    else{
        $MainContent .="Price:<span style='font-weight:bold; color: red; text-decoration: line-through;'>  
                    S$ $formattedPrice</span>";
        $MainContent .=" S$ $offeredPrice <br />";
    }
                    
}

// To Do 1:  Ending ....

// To Do 2:  Create a Form for adding the product to shopping cart. Starting ....
if($row["Quantity"] > 0) {
$quantity = $row['Quantity'];
$MainContent .= "<form action='cartFunctions.php' method='post'>";
$MainContent .= "<input type='hidden' name='productDetails' value='add' />";
$MainContent .= "<input type='hidden' name='product_id' value='$pid' />";
$MainContent .= "Quantity: <input type='number' name='quantity' value='1' min='1' max= $quantity style='width:40px;' required='required' />";
$MainContent .="<div style='font-style:italic'>In stock:&nbsp$quantity&nbspleft</div>";
$MainContent .= "<button type='submit' class='btn btn-primary' style='background-color:#f59acc; border-color:#f59acc; color:black;'>Add to Cart</button>";
$MainContent .= "</form>";
}
else{
    $MainContent .= "Quantity: <input type='number' disabled name='quantity' value='0'
                       style='width:40px' disabled />";

    $MainContent .= "<br><br><button type='submit' disabled class='btn btn-primary' style='background-color:#f59acc; border-color:#f59acc; color:black;'>Add to Cart</button>";
    
    $MainContent .= "<br><span style='font-weight: bold; color: red; font-size:20px'>Out of Stock!</span>";
}
$MainContent .= "</div"; // End of right column
$MainContent .= "</div>"; // End of row

// To Do 2:  Ending ....

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");  
?>
