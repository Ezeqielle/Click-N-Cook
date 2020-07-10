<?php
session_start();
include('../../../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../../../lang/fr-lang.php');
} else {
    include('../../../../lang/en-lang.php');
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="description" content="page d'accueil">
    <link href="../../../../bootstrap/docs/dist/css/bootstrap.css" rel="stylesheet">
    <link href="../../../../bootstrap/docs/dist/js/bootstrap.js" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../../../../css/style.css">
    <link rel="shortcut icon" href="../../../../images/logo.png ">
    <title>Click'N Cook</title>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="../../../../js/popup.js" type="text/javascript"></script>
    <script src="../../../../js/searchShop.js" type="text/javascript"></script>
</head>
<body>

    <!--navigation-->
    <div>
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <a class="nav clickNCook navbar-brand" href="../../../../login/indexFranchisee.php" title="<?php echo TXT_INDEX_LOGIN; ?>">Click'N Cook</a>
                    </div>
                </div>
            </nav>
        </header>

        <main>

            <div class="container-fluid" id="img">
                <img class="img-fluid background-image" src="../../../../images/food.png">
            </div>
            <div class="row" style="margin-bottom: 10px; width: 100%">
            </div>
            <div class="row" style="width: 100%;">
                <section style="border: none; background-color: unset;" class="col-lg-4">
                </section>
                <article style="border: none;" class="col-lg-4 radius">
                    <div class="container">


                        <div class="card card-container">

                            <div id="signup_div_wrapper">
                                <img class="profile-img-card logo-image" src="../../../../images/logo.png" alt="logo.png not found"/>

                                <!--FORMULAIRE-->
                                <form action="/assets/addFranchisee.php" method="POST">

                                                <input type="text" id="firstName"
                                                       name="firstName"
                                                       class="form-control"
                                                       placeholder="<?php echo TXT_REGISTER_PLACEHOLDER1; ?>"
                                                       required="required"
                                                       autofocus="autofocus"
                                                       value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["firstName"]:"";?>">

                                                <input type="text" id="lastName"
                                                       name="lastName"
                                                       class="form-control"
                                                       placeholder="<?php echo TXT_REGISTER_PLACEHOLDER2; ?>"
                                                       required="required"
                                                       autofocus="autofocus"
                                                       value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["lastName"]:"";?>">

                                                <input type="text" id="driverLicense"
                                                       name="driverLicense"
                                                       class="form-control"
                                                       placeholder="<?php echo TXT_BO_DLR; ?>" required="required"
                                                       value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["driverLicense"]:"";?>">

                                                <input type="email" id="inputEmail" name="inputEmail" class="form-control"
                                                       placeholder="<?php echo TXT_BO_EMAILAD; ?>" required="required"
                                                       value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["inputEmail"]:"";?>">

                                                <input type="password" id="inputPassword"
                                                       name="inputPassword" class="form-control"
                                                       placeholder="<?php echo TXT_BO_PASSWORD; ?>" required="required" value="">

                                                <input type="password" id="confirmPassword" name="confirmPassword"
                                                       class="form-control"
                                                       placeholder="<?php echo TXT_REGISTER_PLACEHOLDER3; ?>"
                                                       required="required" value="">
                                        </div><!-- end flow-row -->
                            <?php
                            if(isset($_SESSION["errors"])){
                                echo "<div class='alert alert-danger'>";
                                foreach ($_SESSION["errors"] as $error) {
                                    echo "<li>".$error."</li>";
                                }
                                echo "</div>";
                            }
                            ?>

                                        <button class="btn btn-lg btn-primary btn-block btn-signin Register" href="#" type="submit"><?php echo TXT_INDEX_REGISTER; ?></button>
                                        <center>
                                            <a href="../../../../login/indexFranchisee.php" class="option"><?php echo TXT_INDEX_LOGIN; ?></a>
                                        </center>

                                </form>
                            </div><!--end wraper-->
                        </div><!--end row-->
                    </div><!--end container-->
                </article>
                <section style="border: none; background-color: unset" class="col-lg-4">
                </section>
            </div>
            <div class="row" style="margin-top: 10px; width: 100%">
            </div>
        </main>
    </div>
</body>
<footer>
    <div class="footer">
        <div class="text">
            <?php
            echo '<p class="copyright">Click\'N Cook © ' . date('Y')
            ?>
            <font style="font-size: 15px; font-weight: 600" >
                |
                <a style="font-size: 15px" href="/WebGL/threeJs.php"><?php echo TXT_INDEX_PROPOS; ?></a>
                |
                <a style="font-size: 15px" href="../../../../login/index.php"><?php echo TXT_INDEX_AREAP; ?></a>
            </font>
            </p>
        </div>
        <div class="langs">
            <form method="POST" class="lang">
                <?php
                    echo '<button name="lang" class="btn btn-default btn-sm button-lang" title="' . TXT_INDEX_LANG . '">';
                    echo '<span class="language"><strong>' . $_SESSION['lang'] . '</strong></span>';
                    echo '</button>';
                ?>
            </form>
        </div>
    </div>

</footer>

<!-- Optional JavaScript -->
<script>window.jQuery || document.write('<script src="../../../../bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../../../bootstrap/docs/dist/js/bootstrap.min.js"></script>
<script src="../../../../bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
