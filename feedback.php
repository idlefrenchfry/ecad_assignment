<?php

session_start();

// get all feedbacks
include("mysql_conn.php");

$feedbacks = array();

$qry = "SELECT f.*, s.Name FROM `feedback` AS f
        JOIN Shopper AS s ON s.ShopperID = f.ShopperID";

$result = $conn->query($qry);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $feedbacks[] = $row;
    }
}

$total_no_feedbacks = count($feedbacks);

// get average ratings
$qry = "SELECT AVG(Rank) AS Ranking 
        FROM feedback";

$result = $conn->query($qry);
$row = $result->fetch_array();
$avg_rank = number_format($row["Ranking"], $decimals=2);
$avg_rank_percent = ceil(($avg_rank/5) * 100);

// get number of each ranking
$qry = "SELECT Rank, count(*) AS Occurrences
        FROM Feedback
        GROUP BY Rank";

$result = $conn->query($qry);

$rank_no = array(
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0
);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $rank_no[$row["Rank"]] = $row["Occurrences"];
    }
}

$yellow_star = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='#FFC107' class='bi bi-star-fill' viewBox='0 0 16 16'>";
$yellow_star .= "<path d='M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z'/>";
$yellow_star .= "</svg>";

$hollow_star = "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-star' viewBox='0 0 16 16'>";
$hollow_star .= "<path d='M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z'/>";
$hollow_star .= "</svg>";

$MainContent = "<div style='width:80%; margin:auto;'>"; // start of containing div

// Page title
$MainContent .= "<div class='row'>";
$MainContent .= "<div style='margin:auto' class='page-title mb-3'>Feedback</div>";
$MainContent .= "</div>";

/* Reviews by others */
$MainContent .= "<div class='row' style='margin-bottom: 70px;'>";

// overall feedback rating
$MainContent .= "<div class='col-sm-4'>"; // start of column 1

// -- average user rating
$MainContent .= "<div class='row'>";
$MainContent .= "<div class='card rounded-0' style='background-color: #FAFAFA;width: 100%; height: 210px;'>";
$MainContent .= "<div class='card-body'>"; // start of card body
$MainContent .= "<h4 class='card-title font-weight-bold'>Average Rating</h4>";
$MainContent .= "<p><span style='font-weight: bold; font-size: 1.3rem'>$avg_rank</span> <span style='font-size:1.1rem'>/ 5</span></p>";

// rating bar
$MainContent .= "<div class='progress'>";
$MainContent .= "<div class='progress-bar bg-warning' role='progressbar' style='width: $avg_rank_percent%' aria-valuenow='$avg_rank_percent' aria-valuemin='0' aria-valuemax='100'></div>";
$MainContent .= "</div>"; // end of progress bar
$MainContent .= "</div>"; // end of card body
$MainContent .= "</div>"; // end of card
$MainContent .= "</div>"; // end of row

// -- rating breakdown
$MainContent .= "<div class='row'>";
$MainContent .= "<div class='card border-0 rounded-0' style='width: 100%;'>";
$MainContent .= "<div class='card-body'>"; // start of card body
$MainContent .= "<h4 class='card-title font-weight-bold'>Rating Breakdown</h4>";

// -- bars
$rating_colours = array(
    5 => "bg-success",
    4 => "bg-primary",
    3 => "bg-info",
    2 => "bg-earning",
    1 => "bg-danger"
);

for ($rating = 5; $rating > 0; --$rating) {
    $current_rank_no_percentage = ($rank_no[$rating] / $total_no_feedbacks) * 100;

    $MainContent .= "<div class='d-flex'>";
    $MainContent .= "<div style='width:35px; line-height:1;'>";
    $MainContent .= "<div class='d-flex' style='height:9px; margin:5px 0;'><span class='d-block mr-1'>$rating</span> <span>$yellow_star</span></div>";
    $MainContent .= "</div>";

    $MainContent .= "<div style='width:230px;'>";

    // progress bar
    $MainContent .= "<div class='progress' style='height:9px; margin:8px 0;'>";
    $MainContent .= "<div class='progress-bar $rating_colours[$rating]' role='progressbar' aria-valuenow='$rating' aria-valuemin='0' aria-valuemax='5' style='width: $current_rank_no_percentage%'>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";

    $MainContent .= "</div>";
    $MainContent .= "<div style='margin-left:10px;'>$rank_no[$rating]</div>";
    $MainContent .= "</div>";
}
// -- end bars

$MainContent .= "</div>"; // end of card body
$MainContent .= "</div>"; // end of card
$MainContent .= "</div>"; // end of row

$disabled = "disabled";
$formButtonMsg = "Log in to submit feedback!";
if (isset($_SESSION["ShopperID"])) {
    $disabled = "";
    $formButtonMsg = "Submit your own feedback!";
}

$MainContent .= "<div class='row mt-3'>";
$MainContent .= "<button $disabled type='button' class='btn btn-primary d-block' style='margin: auto;'  data-toggle='modal' data-target='#feedbackFormModal'>";
$MainContent .= $formButtonMsg;
$MainContent .= "</button>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of overall breakdown column

$MainContent .= "<div class='col-sm-8'>";

// all feedback
for ($i = 0; $i < $total_no_feedbacks; ++$i) {
    $feedback = $feedbacks[$i];

    $MainContent .= "<div class='row mb-2'>"; // start of row 2
    $MainContent .= "<div class='col-sm-12'>"; // start of column
    $MainContent .= "<div class='card rounded' style='width: 100%;'>";
    $MainContent .= "<div class='card-body'>"; // start of card body
    $MainContent .= "<h5 class='card-title font-weight-bold'>$feedback[Subject]</h5>";

    // ranking
    $total_stars = 5;

    // full stars
    for ($star_count = 0; $star_count < $feedback["Rank"]; ++$star_count) {
        $MainContent .= "<span class='mr-2'>$yellow_star</span>";
    }

    // empty stars
    $total_stars -= $feedback["Rank"];

    for ($remaining = 0; $remaining < $total_stars; ++$remaining) {
        $MainContent .= "<span class='mr-2'>$hollow_star</span>";
    }

    // feedback content
    $dateCreated = new DateTime($feedback["DateTimeCreated"]);
    $dateCreated = $dateCreated->format('Y-m-d');
    // $dateCreated = $dateCreated.getDate() + "/" + $dateCreated.getMonth() + "/" + $dateCreated.getYear();
    $MainContent .= "<div class='card-text mt-3'><u>$feedback[Name]</u><br />$dateCreated<br />$feedback[Content]</div>";

    $MainContent .= "</div>"; // end of card body
    $MainContent .= "</div>"; // end of card
    $MainContent .= "</div>"; // end of column
    $MainContent .= "</div>"; // end of row 2
}

$MainContent .= "</div>"; // end of column
$MainContent .= "</div>"; // end of feedback row

if (isset($_SESSION["ShopperID"])) {

    

    // Modal
    $MainContent .= "<div class='modal fade' id='feedbackFormModal' tabindex='-1' role='dialog' aria-labelledby='feedbackFormModalLabel' aria-hidden='true'>";
    $MainContent .= "<div class='modal-dialog' role='document'>";
    $MainContent .= "<div class='modal-content'>";
    $MainContent .= "<div class='modal-header'>";
    $MainContent .= "<h5 class='modal-title' id='feedbackFormModalLabel'>Submit your feedback!</h5>";
    $MainContent .= "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
    $MainContent .= "<span aria-hidden='true'>&times;</span>";
    $MainContent .= "</button>";
    $MainContent .= "</div>";
    $MainContent .= "<div class='modal-body'>";

    /* Submit own review */
    $MainContent .= "<form name='register' action='submitFeedback.php' method='post' 
                    onsubmit='return validateForm()'>";
    
    // subject
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<label class='col-sm-3 col-form-label font-weight-bold' for='subject'>Subject:</label>";
    $MainContent .= "<div class='col-sm-9'>";
    $MainContent .= "<input class='form-control' name='subject' id='subject'
                    type='text' required />";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    
    // rank
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<label class='col-sm-3 col-form-label font-weight-bold' for='rank'>Rating:</label>";
    $MainContent .= "<div class='col-sm-9'>";
    $MainContent .= "<select class='form-control' name='rank' id='rank' required>";
    $MainContent .= "<option>5</option>";
    $MainContent .= "<option>4</option>";
    $MainContent .= "<option>3</option>";
    $MainContent .= "<option>2</option>";
    $MainContent .= "<option>1</option>";
    $MainContent .= "</select>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    
    // content
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<label class='col-sm-3 col-form-label font-weight-bold' for='content'>Content:</label>";
    $MainContent .= "<div class='col-sm-9'>";
    $MainContent .= "<textarea class='form-control' name='content' id='content'
                      cols='25' rows='4' required></textarea>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    
    $MainContent .= "<div class='form-group row'>";       
    $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    

    $MainContent .= "</div>";
    $MainContent .= "<div class='modal-footer'>";
    $MainContent .= "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
    $MainContent .= "<button type='submit' class='btn btn-primary'>Save changes</button>";
    $MainContent .= "</div>";
    $MainContent .= "</form>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
}

$MainContent .= "</div>"; // end of containing div

include("MasterTemplate.php");

?>