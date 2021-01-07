<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
	$content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                <a class='nav-link' href='changePassword.php'>Change Password</a></li>
                <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Logout</a></li>";
	//To Do 2 (Practical 4) - 
    //Display number of item in cart
    if (isset($_SESSION["NumCartItem"])) {
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
	
}
?>
<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <!-- Dynamic Text Display -->
    <span class="navbar-text ml-md-2"
          style="color: #F7BE81; max-width: 80%;">
        <?php echo $content1; ?>
    </span>

    <!-- Toggler/Collapasible Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>

<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <!-- Collapsible part of navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <!-- Left-justified menu items -->
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

        <!-- Right-justified menu items -->
        <ul class="navbar-nav ml-auto">
            <?php echo $content2; ?>
        </ul>
    </div>
</nav>
