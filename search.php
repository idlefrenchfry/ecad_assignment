<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />      
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
        <!-- Site specific Cascading Stylesheet -->
        <link rel="stylesheet" href="css/style.css">
    </head>
</html>

<?php 

// TO DO: Haven't touched this page

// Detect the current session
session_start();

// HTML Form to collect search keyword and submit it to the same page 
// in server
$MainContent = "<div style='width:80%; margin:auto;'>"; // Container
$MainContent .= "<form name='frmSearch' method='get' action=''>";
$MainContent .= "<div class='form-group row'>"; // 1st row
$MainContent .= "<div class='col-sm-9 '>";
$MainContent .= "<span class='page-title'>Product Search</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End of 1st row

$MainContent .= "<div class='form-group row'>"; // 2nd row
$MainContent .= "<label for='keywords' 
                  class='col-sm-3 col-form-label'>Product Name /Description:</label>";
$MainContent .= "<div class='col-sm-6'>";
$MainContent .= "<input class='form-control' name='keywords' id='keywords' 
                  type='search' required/>";
$MainContent .= "</div>";

$MainContent .= "<div class='col-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Search</button>";
$MainContent .= "</div>";

$MainContent .= "</div>";  // End of 2nd row

$MainContent .= "<div class='form-group row'>"; // 3rd row
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<br><label for='myCheck'>On Offer</label>";
$MainContent .= "&nbsp<input type='checkbox' id='check' name='check' value='yes'>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End of 3rd row


$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span>Price Range:</span>";
$MainContent .= "<br><input type='number' label for='num1' name='num1' placeholder='1' min='0' max='200' />     to  ";
$MainContent .= "<input type='number' label for='num2' name='num2' placeholder='160' min='0' max='200' />";
$MainContent .= "</div>";
$MainContent .= "</form>";

// The search keyword is sent to server
if (isset($_GET['keywords']) && (isset($_GET['num1']) || isset($_GET['num2']) || isset($_GET['check']))) {

    // To Do (DIY): Retrieve list of product records with "ProductTitle" 
    // contains the keyword entered by shopper, and display them in a table.
    include_once("mysql_conn.php");

    // get all products
    $qry = "SELECT p.*, ps.SpecVal FROM `product` AS p
            INNER JOIN ProductSpec as ps ON p.ProductID = ps.ProductID";
    
    $result = $conn->query($qry);

    $filtered_products = array();
    $filtered_prod_titles = array();

    while($row = $result->fetch_array()) {
        if (in_array($row["ProductTitle"], $filtered_prod_titles)) {
            continue;
        }

        if (isset($_GET["check"]) && $_GET["check"] == "yes") {
            if ($row["Offered"] != 1) {
                continue;
            }
        }

        if ($_GET['keywords'] != '') {
            if (stripos($row["ProductTitle"], $_GET["keywords"]) === FALSE &&
                stripos($row["ProductDesc"], $_GET["keywords"]) === FALSE &&
                stripos($row["SpecVal"], $_GET["keywords"]) === FALSE) {

                continue;
            }
        }

        // get offered price if there is
        $price = 0;

        if ($row["Offered"] == 1) {
            $price = $row["OfferedPrice"];
        }

        else {
            $price = $row["Price"];
        }
        
        if ($_GET['num1'] != '') {
            if ($price < $_GET['num1']) {
                continue;
            }
        }
    
        if ($_GET['num2'] != '') {
            if ($price > $_GET['num2']) {
                continue;
            }
        }

        $filtered_prod_titles[] = $row["ProductTitle"];
        $filtered_products[] = $row;
    }


    // Close connection
    $conn->close();
    
    

    if (count($filtered_products) > 0) {
        $MainContent .= "<p style='font-size: 15px; font-weight: bold;'>Search Results for $_GET[keywords]: </p>";
        $MainContent .= "<div class='card-deck d-flex justify-content-start;'>"; // Start of card deck
    foreach ($filtered_products as $row)
    {
        $MainContent .= "<div class='card' style='max-width: 18rem; margin-bottom: 10px;'>"; // Start of card

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

    }
   
     else {
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<p style='font-size: 20px;'>Oops, we can't find any search results for <a style='font-weight:bold'>$_GET[keywords].</a> </p>";
        $MainContent .= "<br><p style='font-size: 20px;'>Feel free to email us at <a href='mailto:egiftr@np.edu.sg'>egiftr@np.edu.sg</a> for more product details.</p>";
        $MainContent .= "<br><p style='font-size: 20px;'>Or you can subscribe to us for more latest updates!</p>";
        $MainContent .= "</div>";
     }
    
	// To Do (DIY): End of Code
}


$MainContent .= "</div>"; // End of Container
include("MasterTemplate.php");
?>

<script>  
$(document).ready(function(){  
    
	$( "#price_range" ).slider({
		range: true,
		min: 1000,
		max: 20000,
		values: [ <?php echo $minimum_range; ?>, <?php echo $maximum_range; ?> ],
		slide:function(event, ui){
			$("#minimum_range").val(ui.values[0]);
			$("#maximum_range").val(ui.values[1]);
			load_product(ui.values[0], ui.values[1]);
		}
	});
	
});  
</script>