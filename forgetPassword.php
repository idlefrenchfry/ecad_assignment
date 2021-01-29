<?php 
// Detect the current session

session_start();

if (isset($_SESSION["forgotPasswordSuccess"])) {
    $MainContent = $_SESSION["forgotPasswordSuccess"];
    unset($_SESSION["forgotPasswordSuccess"]);
}

else {
    $MainContent = "<div style='width:80%; margin:auto;'>";
    $MainContent .= "<form name='forgetpassword' form method='POST'>";
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
    $MainContent .= "<span class='page-title'>Forget Password</span>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    $MainContent .= "<div class='form-group row'>";
    $MainContent .= "<label class='col-sm-3 col-form-label' for='email'>
                     Email Address:</label>";
    $MainContent .= "<div class='col-sm-6'>";
    
    if (isset($_SESSION["forgotPasswordEmail"])) {
        $MainContent .= "<input class='form-control' name='email' id='email'
                        value='$_SESSION[forgotPasswordEmail]' type='email' required />";
        unset($_SESSION["forgotPasswordEmail"]);
    }

    else {
        $MainContent .= "<input class='form-control' name='email' id='email'
                                type='email' required />";
    }

    if (isset($_SESSION["forgetPasswordEmailWrong"])) {
        $MainContent .= "<span class='text-danger'>$_SESSION[forgetPasswordEmailWrong]</text>";
        unset($_SESSION["forgetPasswordEmailWrong"]);
    }

    $MainContent .= "</div>";
    $MainContent .= "</div>";
    
    if (isset($_SESSION["forgetPasswordQn"])) {
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='pwdans'>
                        $_SESSION[forgetPasswordQn]</label>";
        $MainContent .= "<div class='col-sm-6'>";
        $MainContent .= "<input class='form-control' name='pwdans' id='pwdans'
                                type='text' required />";
        $MainContent .= "</div>";
        $MainContent .= "</div>";

        if(isset($_SESSION["forgotPasswordFailed"])) {
            $MainContent .= "<div class='text-danger offset-sm-3 mb-3' style='padding-left: 15px'>$_SESSION[forgotPasswordFailed]</div>";
            unset($_SESSION["forgotPasswordFailed"]);
        }
        
        unset($_SESSION["forgetPasswordQn"]);
    }
    
    $MainContent .= "<div class='form-group row'>";       
    $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
    $MainContent .= "<button class='btn btn-primary' type='submit'>Submit</button>";
    $MainContent .= "</div>";
    $MainContent .= "</div>";
    $MainContent .= "</form>";
}

$MainContent .= "</div>";

// Process after user click the submit button
if (isset($_POST['email'])) {
	// Read email address entered by user
    $email = $_POST['email'];

	// Retrieve shopper record based on e-mail address
	include_once("mysql_conn.php");
	$qry = "SELECT * FROM Shopper WHERE Email=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("s", $email);
	$stmt->execute();
	$result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        $shopperDetails = $result->fetch_array();

        $_SESSION["forgotPasswordEmail"] = $email;

        if (isset($_POST['pwdans'])) {
            $pwdans = $_POST['pwdans'];
            if ($pwdans == $shopperDetails["PwdAnswer"]) {
                unset($_SESSION["forgotPasswordEmail"]);
                $_SESSION["forgotPasswordSuccess"] = "<h3 class='text-success'>Your password is: $shopperDetails[Password]</h3>";
            }

            else {
                $_SESSION["forgetPasswordQn"] = $shopperDetails["PwdQuestion"];
                $_SESSION["forgotPasswordFailed"] = "You have entered the wrong answer.";
            }
        }

        else {
            // set question
            $_SESSION["forgetPasswordQn"] = $shopperDetails["PwdQuestion"];
        }

    }
    else {
        $_SESSION["forgetPasswordEmailWrong"] = "<p><span style='color:red;'>Wrong E-mail address!</span>";
    }
    
    echo "<meta http-equiv='refresh' content='0'>";
    
	$conn->close();
}

include("MasterTemplate.php"); 
?>