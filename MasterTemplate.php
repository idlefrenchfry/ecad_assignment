<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mamaya e-BookStore</title>
        <!-- Latest Compiled and minified CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
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
            <div class="row">
                <div class="col-sm-12">
                    <a href="index.php"></a>
                    <img src="Images/GIFTR.png" alt="Logo" class="img-fluid" style="width: 100%">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="row">
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
                    <a href="mailto:mamaya@np.edu.sg">mamaya@np.edu.sg</a>
                    <p style="font-size: 12px">&copy;Copyright by Mamaya Group</p>
                </div>
            </div>  

        </div>
    </body>
</html>