<script type="text/javascript">
function validateForm()
{
    // Check if password matched
	if (document.changePwd.pwd1.value != document.changePwd.pwd2.value) {
 	    alert("Passwords not matched!");
        return false;   // cancel submission
    }
    return true;  // No error found
}
</script>

<?php
// Detect the current session
session_start();
// Check if user logged in 
if (!isset($_SESSION["ShopperID"])) {
	// redirect to login page if not logged in
	header ("Location: login.php");
	exit;
}

$MainContent = "<div style='width:80%; margin:auto;'>";
$MainContent .= "<form name='changePwd' method='post' 
                       onsubmit='return validateForm()'>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Change Password</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='pwd1'>
                 New Password:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='pwd1' id='pwd1' 
                        type='password' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='pwd2'>
                 Retype Password:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='pwd2' id='pwd2'
                        type='password' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";       
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Update</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";

// Process after user click the submit button
if (isset($_POST['pwd1'])) {
	// get new password
    $new_pwd = $_POST["pwd1"];
    
    /* TO DO (Ana): Forget Password

    // $hashed_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
    
    */
    
	// update password
    include_once("mysql_conn.php");
    $qry = "UPDATE Shopper 
            SET Password = ?
            WHERE ShopperID = ?";

    $stmt = $conn->prepare($qry);
    $userID = $_SESSION["ShopperID"];
    $stmt->bind_param("si", $new_pwd, $userID);
    if($stmt->execute()) {
        $MainContent .= "<div class='col-sm-9 offset-sm-3'><p class='text-success'>Password successfully updated!</p></div>";
    }

    else {
        $MainContent .= "<div class='col-sm-9 offset-sm-3'><p class='text-danger'>Error! Something went wrong, please try again!</p></div>";
    }

    $stmt->close();
    $conn->close();
}

$MainContent .= "</div>";
include("MasterTemplate.php"); 
?>