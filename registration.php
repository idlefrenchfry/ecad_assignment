<?php
//Detect the current session
session_start();
$MainContent = "";

//Read the data input from the previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];

//Create a password hash using the default bcrypt algorithm
$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

//Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Define the INSERT SQL statement
$qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, Password)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($qry);
// "ssssss" - 6 string parameters
$stmt->bind_param("ssssss", $name, $address, $country, $phone, $email, $password);

if ($stmt->execute()) { //SQL statement executed successfully
    //Retrieve the Shopper ID assigned to the new shopper
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry); //Execute the SQL and get the returned result
    while ($row = $result->fetch_array()) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }
    //Display successful message and ShopperID
    $MainContent .= "Registration Successful!<br />";
    $MainContent .= "Your Shopper ID is $_SESSION[ShopperID]<br />";
    //Save the Shopper name in a session variable
    $_SESSION["ShopperName"] = $name;
}
else {
    $MainContent .= "<h3 style='color:red'>Error in inserting record</h3>";
}

//Release the resource allocated for prepared statement
$stmt->close();
//Close database connection
$conn->close();
//Include the master template file for this page
include("MasterTemplate.php");
?>