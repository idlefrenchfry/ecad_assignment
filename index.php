<?php 
// Detect the current session
session_start();



// Establish data connection
include_once("mysql_conn.php");

$qry = "SELECT * FROM product WHERE OfferStartDate < CURDATE() AND OfferEndDate> CURDATE()";
$result = $conn->query($qry);
    
$MainContent = "<div class='bd-example'>";// start
if ($result->num_rows > 0) {
// the page header and header row of offer
    $MainContent = "<div id='carouselExampleCaptions' class='carousel slide' data-ride='carousel' >";
    $MainContent .= "<div class='carousel-inner'>";
    $rowcount = 0;
    while ($row = $result->fetch_array())
        {
	        $rowcount++;
	        $name = "$row[ProductTitle]";
	        $img = "./Images/Products/$row[ProductImage]";
	        $product = "productDetails.php?pid=$row[ProductID]";
	        $Quantity=$row["Quantity"];
	        $formattedPrice = number_format($row["Price"],2);
	        $offerend = new DateTime($row["OfferEndDate"]);
	        $today = new DateTime(Date("y-m-d"));
	        $daysleft = $today->diff($offerend)->days;
	        $formattedOfferedPrice = number_format($row["OfferedPrice"],2);
	    if($rowcount==1)
	    {
		    $MainContent .= "<div class='carousel-item active'>";
	    }
	    else
	    {
		    $MainContent .= "<div class='carousel-item'>";
	    }
	$MainContent .= "<div class='image'><a href='$product'><img class='d-block mx-auto' src='$img' alt='$name'></a></div>";
	$MainContent .= "<div class='top'>
	 <div class='text1'>Quick! Only $Quantity left! Offer ends in $daysleft days!</div></div>";
	$MainContent .= "<div class='middle'>
	<div class='text'>$name</div>
	<div class='text'><s>S$$formattedPrice</s></div>
	<div class='text'>S$$formattedOfferedPrice</div>
	</div>";
	$MainContent .= "</div>";
    }
    
    $MainContent .="</div>";
    


    
	$MainContent .= "<a class='carousel-control-prev' href='#carouselExampleCaptions' role='button' data-slide='prev'>";
	$MainContent .= "<span class='carousel-control-prev-icon' aria-hidden='true'></span>";
	$MainContent .= "<span class='sr-only'>Previous</span></a>";
	$MainContent .= "<a class='carousel-control-next' href='#carouselExampleCaptions' role='button' data-slide='next'>";
	$MainContent .= "<span class='carousel-control-next-icon' aria-hidden='true'></span>";
    $MainContent .= "<span class='sr-only'>Next</span>
                    </a>";
    
    $MainContent .= "</div>";
    
    

}
$MainContent .= "</div>";//end carousel 

$conn->close(); // Close database connnection     


//$MainContent = "<div><img src='Images/welcome2egiftr.png'  class='img-fluid' style='display:block; margin:auto;'/></div>";

include("MasterTemplate.php"); 
?>
