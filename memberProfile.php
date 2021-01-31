<style>
label {
    display: unset !important;
    margin-bottom: 0 !important;
}
</style>

<script type="text/javascript">
function validateForm()
{
    let minDate = new Date()
    minDate.setYear(minDate.getFullYear() - 16)

    // check if date of birth indicates less than 15 y/o
    if (new Date(document.update.dob.value) > minDate) {
        alert("You must be at least 15 to sign up!")
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
    $pwdqn = $_SESSION["updateForgetQn"];
    $pwdans = $_SESSION["updateForgetAns"];
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
    
        $pwdqn = $row["PwdQuestion"];
        $pwdans = $row["PwdAnswer"];
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
$MainContent .= "<h5 class='card-title'><label for='name'>Name  <span style='color:red;'>*</span></label></h5>";
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
$MainContent .= "<h5 class='card-title'><label for='dob'>Birthday <span style='color:red;'>*</span></label></h5>";
$MainContent .= "<input class='form-control' name='dob' id='dob' 
                value='$dob' type='date' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Member's Country
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'><label for='country'>Country</label></h5>";
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
$MainContent .= "<h5 class='card-title'><label for='ph'>Phone Number</label></h5>";
$MainContent .= "<div class='d-flex align-items-center'>";
$MainContent .= "<span style='padding-right: 10px'>(65) </span>";
$MainContent .= "<input class='form-control' name='ph' id='ph' type='text' value='$ph' />";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Member's Email
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'><label for='email'>Email <span style='color:red;'>*</span></label></h5>";
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
$MainContent .= "<h5 class='card-title'><label for='address'>Address</label></h5>";
$MainContent .= "<textarea class='form-control' name='address' id='address'
                cols='25' rows='4' >$address</textarea>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>"; // end of card group
$MainContent .= "</div>"; // end of row 4

// --- start of row 5
$MainContent .= "<hr/>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<span class='page-subtitle'>In case you forget your password</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "<div class='row'>"; 
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Forget password qn
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'><label for='forgetPwdQn'>Question <span style='color:red;'>*</span></label></h5>";
$MainContent .= "<input class='form-control' name='forgetPwdQn' id='forgetPwdQn' 
                value='$pwdqn' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>";
$MainContent .= "</div>";

// --- start of row 6
$MainContent .= "<div class='row'>"; 
$MainContent .= "<div class='card-deck justify-content-center' style='width:100%;'>"; // start of card group

// Member's password ans
$MainContent .= "<div class='card border-0 mb-3' style='width: 18rem;'>";
$MainContent .= "<div class='card-body text-dark'>";
$MainContent .= "<h5 class='card-title'><label for='forgetPwdAns'>Answer <span style='color:red;'>*</span></label></h5>";
$MainContent .= "<input class='form-control' name='forgetPwdAns' id='forgetPwdAns' 
                value='$pwdans' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

$MainContent .= "</div>";
$MainContent .= "</div>";

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