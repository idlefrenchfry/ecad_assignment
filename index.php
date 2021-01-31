<style>
.carousel-control-next,
.carousel-control-prev, .carousel-indicators {
    filter: invert(100%);
}

</style>

<?php 
// Detect the current session
session_start();



// Establish data connection
include_once("mysql_conn.php");

$qry = "SELECT * FROM product WHERE OfferStartDate <= CURDATE() AND OfferEndDate> CURDATE()";
$result = $conn->query($qry);

if ($result->num_rows > 0) {
	$MainContent = "<div class='row'>";
	$MainContent .= "<div style='margin:auto; font-size: 30px;' class='font-weight-bold mb-3'>On Offer!</div>";
	$MainContent .= "</div>";

	$MainContent .= "<div class='card-deck'>";// start

    while ($row = $result->fetch_array()) 
	{

		$MainContent .= "<div class='card' style='max-width: 18rem; margin-bottom: 10px;'>"; // Start of card

		// Get product details
		$product = "productDetails.php?pid=$row[ProductID]";
		$formattedPrice = number_format($row["Price"], 2);
		$offerPrice = number_format($row["OfferedPrice"],1);
		$onOffer = number_format($row["Offered"],1);
		$img = "./Images/products/$row[ProductImage]";

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
	$MainContent = "<img src='Images/welcome2egiftr.png'  
					class='img-fluid' 
					style='display:block; margin:auto;'/>";
}

$conn->close(); // Close database connnection     



include("MasterTemplate.php"); 
?>
