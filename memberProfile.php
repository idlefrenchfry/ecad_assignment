<script type="text/javascript">
function validateForm()
{
    let today = new Date()
    today.setHours(0)
    today.setMinutes(0)
    today.setSeconds(0)
    today.setMilliseconds(0)
    
    // check if date of birth is today or after
    if (new Date(document.update.dob.value) >= today) {
        alert("Date of birth cannot be today / after today!")
        return false;
    }

	// check if telephone number is correct
    if (document.update.ph.value != "") {
        var str = document.update.ph.value;
        if (str.length != 8) {
            alert("Please enter an 8-digit phone number.")
            return false; // cancel submission
        }
        else if (str.substr(0, 1) != "6" &&
                str.substr(0, 1) != "8" &&
                str.substr(0, 1) != "9") {
            alert("Phone numbers in Singapore should start with 6, 8, or 9.");
            return false; // cancel submission
        }
    }

    return true;  // No error found
}
</script>

<?php

// Detect the current session
session_start();

// if not redirected from false email
if (isset($_SESSION["updateEmailFailedMsg"])) {
    $name = $_SESSION["updateName"];
    $dob = $_SESSION["updateDob"];
    $address = $_SESSION["updateAddress"];
    $country = $_SESSION["updateCountry"];
    $ph = $_SESSION["updatePhone"];
    $password = $_SESSION["updatePassword"];
    $email = $_SESSION["updateEmail"];
}

else {
    // Establish database connection
    include_once("mysql_conn.php");

    // set up statement
    $qry = ("SELECT * From Shopper WHERE ShopperID = ?");
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $_SESSION["ShopperID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // close statement and connection
    $stmt->close();
    $conn->close();
    
    while ($row = $result->fetch_array()) {
        $name = $row["Name"];
        $dob = $row["BirthDate"];
        $address = $row["Address"];
        $country = $row["Country"];
        $email = $row["Email"];
    
        // Remove (65) from front
        $ph = $row["Phone"];
        $ph = str_replace("(65)", "", $ph);
        $ph = trim($ph);
    
        // hide passwords with asteriks
        $password = $row["Password"];
        $pwd_len = strlen($password);
        $password = "";
    
        for($i = 0; $i < $pwd_len; ++$i) {
            $password .= "*";
        }
    }
}



$MainContent = "<div style='width:80%; margin:auto;'>"; // start of containing div
$MainContent .= "<div class='row'>";
$MainContent .= "<div style='margin:auto' class='page-title'>Profile</div>";
$MainContent .= "</div>";

$MainContent .= "<form name='update' action='updateMemberProfile.php' method='post' 
                       onsubmit='return validateForm()'>";

$MainContent .= "<div class='row mt-3'>"; // start of row 1
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Member's name
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Name</h5>";
$MainContent .= "<input class='form-control' name='name' id='name' 
                value='$name' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of card group
$MainContent .= "</div>"; // end of row 1

// --- start of row 2
$MainContent .= "<div class='row'>";
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Member's Birth date
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Birthday</h5>";
$MainContent .= "<input class='form-control' name='dob' id='dob' 
                value='$dob' type='date' />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Member's Country
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Country</h5>";
$MainContent .= "<input class='form-control' name='country' id='country' type='text' value='$country' />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of card group
$MainContent .= "</div>"; // end of row 2


// --- start of row 3
$MainContent .= "<div class='row'>"; 
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Member's Phone
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Phone Number</h5>";
$MainContent .= "<div class='d-flex align-items-center'>";
$MainContent .= "<span style='padding-right: 10px'>(65) </span>";
$MainContent .= "<input class='form-control' name='ph' id='ph' type='text' value='$ph' />";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Member's Email
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Email</h5>";
$MainContent .= "<input class='form-control' name='email' id='email' 
                value='$email' type='email' required />";

if (isset($_SESSION["updateEmailFailedMsg"])) {
    $MainContent .= "<span class='text-danger'> $_SESSION[updateEmailFailedMsg]</span>";
}

$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of card group
$MainContent .= "</div>"; // end of row 3


// --- start of row 4
$MainContent .= "<div class='row'>"; 
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Member's Address
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'>Address</h5>";
$MainContent .= "<textarea class='form-control' name='address' id='address'
                cols='25' rows='4' >$address</textarea>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of card group
$MainContent .= "</div>"; // end of row 4

// save button
$MainContent .= "<div class='row'>"; 
$MainContent .= "<button class='btn btn-primary' style='width: 100px; margin-left:1.25rem;' type='submit'>Save</button>";
$MainContent .= "</div>"; 

$MainContent .= "</form>"; // end of form
$MainContent .= "</div>"; // end of containing div

if (isset($_SESSION["updateEmailFailedMsg"])) {
    unset($_SESSION["updateEmailFailedMsg"]);
    unset($_SESSION["updateName"]);
    unset($_SESSION["updateDob"]);
    unset($_SESSION["updateAddress"]);
    unset($_SESSION["updateCountry"]);
    unset($_SESSION["updatePhone"]);
    unset($_SESSION["updateEmail"]);
}

include("MasterTemplate.php");

?>