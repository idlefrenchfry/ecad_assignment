<?php

// Detect the current session
session_start();
$MainContent = "";

// Read the data input from previous page
$name = $_POST["name"];
$dob = $_POST["dob"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) " . $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$forgetPwdQn = $_POST["forgetPwdQn"];
$forgetPwdAns = $_POST["forgetPwdAns"];

include_once("mysql_conn.php");

// check if email is unique
$qry = "SELECT * FROM Shopper Where Email = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION["registerEmailFailedMsg"] = "This email is already registered with an account!";
        $_SESSION["registerName"] = $name;
        $_SESSION["registerDob"] = $dob;
        $_SESSION["registerAddress"] = $address;
        $_SESSION["registerCountry"] = $country;
        $_SESSION["registerPhone"] = $_POST["phone"];
        $_SESSION["registerEmail"] = $email;
        $_SESSION["registerForgetPwdQn"] = $forgetPwdQn;
        $_SESSION["registerForgetPwdAns"] = $forgetPwdAns;

        $stmt->close();
        $conn->close();
        header("Location: register.php");
    }

    else {
        $qry = "INSERT INTO Shopper(Name, BirthDate, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($qry);
        
        $stmt->bind_param("sssssssss", $name, $dob, $address, $country, $phone, $email, $password, $forgetPwdQn, $forgetPwdAns);
        
        if ($stmt->execute()) { // SQL Statement executed succesfully
            // Get new shopper ID
            $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
            $result = $conn->query($qry);
            while ($row = $result->fetch_array()) {
                $_SESSION["ShopperID"] = $row["ShopperID"];
            }

            // Display succesful message and shopper ID
            $MainContent .= "<h3 class='text-success'>Registration Succesful! <br />";
            $MainContent .= "Your Shopper ID is $_SESSION[ShopperID] <br /></h3>";
        
            // Save the Shopper Name in a session variable
            $_SESSION["ShopperName"] = $name;
        }
        
        else {
            $MainContent .= "<h3 style='coler:red'>Error in inserting record.</h3>";
        }
    }
} 

$stmt->close();
$conn->close();

include("MasterTemplate.php");

?>