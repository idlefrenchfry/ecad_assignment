<?php

// Detect the current session
session_start();
$MainContent = "";

// Read the data input from previous page
$name = $_POST["name"];
$dob = $_POST["dob"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) " . $_POST["ph"];
$email = $_POST["email"];

// Establish database connection
include_once("mysql_conn.php");

// check if email is unique
$qry = "SELECT * FROM Shopper Where Email = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0 && $result->fetch_array()["ShopperID"] != $_SESSION["ShopperID"]) {
        $_SESSION["updateEmailFailedMsg"] = "This email is already registered with another account!";
        $_SESSION["updateName"] = $name;
        $_SESSION["updateDob"] = $dob;
        $_SESSION["updateAddress"] = $address;
        $_SESSION["updateCountry"] = $country;
        $_SESSION["updatePhone"] = $_POST["ph"];
        $_SESSION["updateEmail"] = $email;

        $stmt->close();
        $conn->close();

        header("Location: memberProfile.php");
    }

    else {
        $qry = "UPDATE Shopper
                SET Name = ?, BirthDate = ?, Address = ?, 
                    Country = ?, Phone = ?, Email = ?
                WHERE ShopperID = ?";
        
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ssssssi", $name, $dob, $address, $country, $phone, $email, $_SESSION["ShopperID"]);
        
        if ($stmt->execute()) { // SQL Statement executed succesfully
            $_SESSION["ShopperName"] = $name;
            $MainContent .= "<h3 class='text-success'>Succesfully updated profile!</h3>";
        }
        
        else {
            $MainContent .= "<h3 class='text-danger'>Error in inserting record.</h3>";
        }
    }
}

$stmt->close();
$conn->close();

include("MasterTemplate.php");

?>