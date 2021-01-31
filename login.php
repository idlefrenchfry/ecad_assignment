<?php
// Detect the current session
session_start();
// Create a centrally located container
$MainContent = "<div style='width: 80%; margin: auto;'></div>";
// Create a HTMLForm within the container
$MainContent .= "<form action='checkLogin.php' method='post'>";
// First row - Header Row
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Member Login</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
// Second row - Entry of email address
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label for='email' class='col-sm-3 col-form-label'>
                 Email Address:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='email'
                 name='email' id='email' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
// 3rd Row
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='password'>
                  Password:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='password'
                 name='password' id='password' required />";

// Check if user was redirected to login page
// because of failed login
if (isset($_SESSION["errors"])) {
    $MainContent .= "<span class='text-danger'>$_SESSION[errors]</span>";
    unset($_SESSION["errors"]);
}

$MainContent .= "</div>";
$MainContent .= "</div>";
// 4th Row - Login Button
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Login</button>";
$MainContent .= "<p>Please <a href='register.php'>sign up</a> if you do not have an account.</p>";
$MainContent .= "<p><a href='forgetPassword.php'>Forgot Password</a></p>";

$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";
// Include the Page Layout template
include("MasterTemplate.php")
?>