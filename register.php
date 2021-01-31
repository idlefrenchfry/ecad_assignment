<script type="text/javascript">
function validateForm()
{
    // check if password matched
	if (document.register.password.value != document.register.password2.value) {
        alert ("Passwords do not match!")
        return false;
    }

    let minDate = new Date()
    minDate.setYear(minDate.getFullYear() - 16)

    // check if date of birth indicates less than 15 y/o
    if (new Date(document.register.dob.value) > minDate) {
        alert("You must be at least 15 to sign up!")
        return false;
    }

	// check if telephone number is correct
    if (document.register.phone.value != "") {
        var str = document.register.phone.value;
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

$name = "";
$dob = "";
$address = "";
$country = "";
$phone = "";
$email = "";
$pwdqn = "";
$pwdans = "";

if (isset($_SESSION["registerEmailFailedMsg"])) {
    $name = $_SESSION["registerName"];
    $dob = $_SESSION["registerDob"];
    $address = $_SESSION["registerAddress"];
    $country = $_SESSION["registerCountry"];
    $phone = $_SESSION["registerPhone"];
    $email = $_SESSION["registerEmail"];
    $pwdqn = $_SESSION["registerForgetPwdQn"];
    $pwdans = $_SESSION["registerForgetPwdAns"];
}

$MainContent = "<div style='width:80%; margin:auto;'>";
$MainContent .= "<form name='register' action='registration.php' method='post' 
                       onsubmit='return validateForm()'>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Membership Registration</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Name
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='name'>Name: <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='name' id='name'
                  value='$name' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Date of Birth
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='dob'>Date of Birth:  <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='dob' id='dob' 
                  value='$dob' type='date' />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Address
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='address'>Address:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<textarea class='form-control' name='address' id='address'
                  cols='25' rows='4' >$address</textarea>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Country
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='country'>Country:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='country' id='country' value='$country' type='text' />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Phone
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='phone'>Phone:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='phone' id='phone' value='$phone' type='text' />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Email Address
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='email'>
                 Email Address:  <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='email' id='email'
                  value='$email' type='email' required />";

// Same email message
if (isset($_SESSION["registerEmailFailedMsg"])) {
    $MainContent .= "<span class='text-danger'> $_SESSION[registerEmailFailedMsg]</span>";
}

$MainContent .= "</div>";
$MainContent .= "</div>";

// Password
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='password'>
                 Password:  <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='password' id='password'
                type='password' pattern='(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}' required />";
$MainContent .= "<small id='pwdHelp' class='form-text text-muted'>Please use a password of min length 8, at least 1 upper case letter, and 1 number</small>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Retype Password
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='password2'>
                 Retype Password:  <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='password2' id='password2'
                type='password' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Forget password section
$MainContent .= "<hr/>";

$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-subtitle'>In case you forget your password</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Forget password qn
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='forgetPwdQn'>
                 Question: <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='forgetPwdQn' id='forgetPwdQn' 
                  value='$pwdqn' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Forget password ans
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='forgetPwdAns'>
                 Answer: <span style='color:red;font-size:15px;'>*</span></label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='forgetPwdAns' id='forgetPwdAns' 
                  value='$pwdans' type='text' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";

// Register Button
$MainContent .= "<div class='form-group row'>";       
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Register</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";
$MainContent .= "</div>";

if (isset($_SESSION["registerEmailFailedMsg"])) {
    unset($_SESSION["registerEmailFailedMsg"]);
    unset($_SESSION["registerName"]);
    unset($_SESSION["registerDob"]);
    unset($_SESSION["registerAddress"]);
    unset($_SESSION["registerCountry"]);
    unset($_SESSION["registerPhone"]);
    unset($_SESSION["registerEmail"]);
    unset($_SESSION["registerForgetPwdQn"]);
    unset($_SESSION["registerForgetPwdAns"]);
}

include("MasterTemplate.php"); 
?>