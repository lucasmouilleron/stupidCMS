<?php require_once __DIR__."/libs/tools.php" ?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo GENERAL_COMPANY?> | home</title>
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
                <a href="index" class="active">index</a> <span class="sep">|</span> <a href="other">other page</a>
            </nav>
        </header>

        <div>
            <h2>Non administrable text</h2>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Certe, nisi voluptatem tanti aestimaretis. Longum est enim ad omnia respondere, quae a te dicta sunt. Odium autem et invidiam facile vitabis. Huius ego nunc auctoritatem sequens idem faciam. Non minor, inquit, voluptas percipitur ex vilissimis rebus quam ex pretiosissimis.
        </div>

        <div>
            <h2>Administrable text</h2>
            <?php _section("page1-about")?>
        </div>        

        <div>
            <h2>Administrable image</h2>
            <img src="<?php _img("image1.jpg")?>"/>
        </div>

        <footer>
            <p>&copy; <?php echo GENERAL_COMPANY ?> | <a href="mailto:<?php echo GENERAL_EMAIL?>"><?php echo GENERAL_EMAIL?></a></p>
        </footer>

    </div>

</body>
</html>