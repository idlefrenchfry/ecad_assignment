<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Giftr Online Gift Store</title>
        <!-- Latest Compiled and minified CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <!-- jQuery Library -->
        <script src="js/jquery-3.3.1.min.js"></script>
        <!-- Latest compiled Javascript -->
        <script src="js/bootstrap.min.js"></script>
        <!-- Site specific CSS -->
        <link rel="stylesheet" href="css/site.css">
    </head>
    <body>
        <div class="container-fluid">
            <!-- Row 1 -->
            <div class="row no-gutters">
                <div class="col-sm-12">
                    <a href="index.php">
                    <img src="Images/GIFTR.png" alt="Logo" class="img-fluid" style="width: 100%">
                    </a>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row no-gutters">
                <div class="col-sm-12">
                    <?php include("navbar.php"); ?>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="row">
                <div class="col-sm-12" style="padding: 15px;">
                    <?php echo $MainContent; ?>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="row">
                <div class="col-sm-12" style="text-align: right;">
                    <hr />
                    Do you need help? Please email to:
                    <a href="mailto:mamaya@np.edu.sg">egiftr@np.edu.sg</a>
                    <p style="font-size: 12px">&copy;Copyright by E-Giftr Group</p>
                </div>
            </div>  

        </div>
    </body>
</html>