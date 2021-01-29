<?php 
// Detect the current session

session_start();

$MainContent ="";
$MainContent = "<div style='width:80%; margin:auto;'>";

$MainContent .= "<form name='forgetpassword' action='forgetfunctions.php' form method='post'>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Forget Password</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='eMail'>
                 Email Address:</label>";
$MainContent .= "<div class='col-sm-6'>";
$MainContent .= "<input class='form-control' name='eMail' id='eMail'
                        type='email' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";



$MainContent .= "<div class='form-group row'>";       
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button type='submit'>Submit</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";
$MainContent .= "</div>";


include("MasterTemplate.php"); 
?>