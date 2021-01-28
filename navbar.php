<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 
	$content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                <a class='nav-link' href='memberProfile.php'>Profile</a></li>
                <li class='nav-item'>
                <a class='nav-link' href='changePassword.php'>Change Password</a></li>
                <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Logout</a></li>";
    // no. of items in cart
    if (isset($_SESSION["NumCartItem"])) {
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
	
}
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <span class="navbar-text ml-md-2"
          style="color: #F7BE81; max-width: 80%;">
        <?php echo $content1; ?>
    </span>

    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>

<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a href="category.php" class="nav-link">Product Category</a>
            </li>
            <li class="nav-item">
                <a href="search.php" class="nav-link">Product Search</a>
            </li>
            <li class="nav-item">
                <a href="shoppingCart.php" class="nav-link">Shopping Cart</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <?php echo $content2; ?>
        </ul>
    </div>
</nav>
