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

		$MainContent .= "<div class='card' style='width: 18rem; margin-bottom: 10px;'>"; // Start of card

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
			
			$MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
			$MainContent .= "<span style='font-weight: bold; color: grey;'>
						<del>S$ $formattedPrice</del></span>";
			$MainContent .= "<p class='card-text' style='font-size: 1.3em; color:red; font-weight:bold;'>S$ $offerPrice</p>";
			$MainContent .="<h3 style='color:red; font-weight:bold;'>On Offer!</h3>";    

		}
		else{
			
			$MainContent .= "<h5 class='card-title'>$row[ProductTitle]</h5>";
			$MainContent .= "<div style='max-height: 100px;'>";
			$MainContent .= "<p class='card-text text-primary' style='font-size: 1.3em'>S$ $formattedPrice</p>";
			$MainContent .= "</div>";
			
		}
		$MainContent .= "</div>"; // End of card body
		$MainContent .= "<a href='$product' class='btn btn-primary btn-block'>See Details</a>";
		$MainContent .= "</div>"; // End of card
		
	}
    
	$MainContent .= "</div>"; // End of card deck
}

$conn->close(); // Close database connnection     



include("MasterTemplate.php"); 
?>
