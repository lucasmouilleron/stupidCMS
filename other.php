<?php require_once __DIR__."/libs/tools.php" ?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo GENERAL_COMPANY?> | page 2</title>
    <meta name="description" content="">
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

    <!-- /////////////////////////////////////////////////////////////// -->
    <div class="container">

        <header>
            <h1><?php echo GENERAL_COMPANY?></h1>
            <nav>
                <a href="index">index</a> <span class="sep">|</span> <a href="other" class="active">other page</a>
            </nav>
        </header>

        <div class="hot">
            <?php _cnt("other-about")?>
        </div>

        <div class="services">
            <?php _cnt("other-more")?>
        </div>

        <footer>
            <p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
        </footer>

    </div>

</body>
</html>