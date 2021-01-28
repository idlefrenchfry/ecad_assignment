<?php
// Detect the current session
session_start();

//  Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// When comparing strings, not case sensitive
// so either use the keyword BINARY (e.g. WHERE Password = BINARY ?)
// or get password and compare in php script
$qry = "SELECT Name, ShopperID, Password from Shopper WHERE Email = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

// Close statement
$stmt->close();

if($result->num_rows == 1) {
    while($row = $result->fetch_array()) {
        if ($row["Password"] == $pwd) {
            
            // save info in session
            $_SESSION["ShopperName"] = $row["Name"];
            $_SESSION["ShopperID"] = $row["ShopperID"];

            $qry = "SELECT ShopCartID FROM ShopCart WHERE ShopperID=? AND OrderPlaced=0";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("s", $_SESSION["ShopperID"]);
            $stmt->execute();
            $shopCartResult = $stmt->get_result();

            // Close statement
            $stmt->close();

            if($shopCartResult->num_rows == 1) {
                while($shopCartResultRow = $shopCartResult->fetch_array()) {
                    // Set Cart ID
                    $_SESSION["Cart"] = $shopCartResultRow["ShopCartID"];

                    // Set Num Cart ID
                    $qry = "SELECT Count(*) AS Count FROM ShopCartItem WHERE ShopCartID=?";
                    $stmt = $conn->prepare($qry);
                    $stmt->bind_param("i", $_SESSION["Cart"]);
                    $stmt->execute();
                    $numItemResult = $stmt->get_result();

                    $_SESSION["NumCartItem"] = 0;

                    while ($numItemResultRow = $numItemResult->fetch_array()) {
                        $_SESSION["NumCartItem"] = $numItemResultRow["Count"];
                    }

                    // Close statement
                    $stmt->close();
                }
            }

            // Close database connection
            $conn->close();

            // Redirect to home page
            header("Location: index.php");
            exit;
        }

        else {
            // Set error message
            $_SESSION['errors'] = "Invalid password";

            // Close database connection
            $conn->close();

            // Redirect to login page
            header("Location: login.php");
            exit;
        }
    }
}

else {
    // Set error message
    $_SESSION['errors'] = "Invalid username";

    // Redirect to login page
    header("Location: login.php");
    exit;
}

?>