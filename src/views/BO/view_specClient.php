<?php
session_start();
include('../../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../../lang/fr-lang.php');
} else {
    include('../../../lang/en-lang.php');
}
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 2 AND isset($_GET['idUser'])){
    require "../../../assets/functions/functions.php";
    require "../../../bdd/connection.php";

    $pdo = connectDB();
    $user = $_GET['idUser'];


    $queryUser = $pdo->prepare("SELECT * FROM CLIENT WHERE id = " . $user);
    $queryUser->execute();
    $dataUser = $queryUser->fetchAll(PDO::FETCH_OBJ);

    ?>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="page d'accueil">
        <link href="../../../bootstrap/docs/dist/css/bootstrap.css" rel="stylesheet">
        <link href="../../../bootstrap/docs/dist/js/bootstrap.js" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="../../../css/style.css">
        <link rel="shortcut icon" href="../../../images/logo.png ">
        <title>Click'N Cook</title>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <script src="../../../js/popup.js" type="text/javascript"></script>
        <script src="../../../js/searchShop.js" type="text/javascript"></script>

        <!-- Custom fonts for this template-->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    </head>
    <body>
    <div class="container">
        <header>
            <nav class="navbar navbar-inverse navbar-fixed-top">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only"><?php echo TXT_EXTENSIONS_TOGGLE; ?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="nav clickNCook navbar-brand" href="../../../client/viewShop.php" title="<?php echo TXT_EXTENSIONS_SHOP; ?>">Click'N Cook</a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a  href="../../../client/viewShop.php" class="glyphicon glyphicon-shopping-cart" title="<?php echo TXT_EXTENSIONS_SHOP; ?>"></a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li> <a href="<?php echo 'view_specClient.php?idUser=' . $_SESSION['id']; ?>" class="glyphicon glyphicon-user" title="<?php echo TXT_EXTENSIONS_PROFILE; ?>"></a> </li>
                            <li> <a href="../../../extensions/disconnect.php" class="glyphicon glyphicon-off"></a> </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <div class="container">
                <section class="col-lg-3"></section>
                <div class="row">
                    <article class="col-lg-6 radius">
                        <center>
                            <h1 id="title-01" class="display-3 text-bold"><?php echo TXT_BO_CD; ?></h1>
                            <a class="btn btn-default btn-sm" href="view_editClient.php?idUser=<?php echo $_GET['idUser']; ?>"><?php echo TXT_BO_EDIT; ?></a>
                        </center>
                    </article>
                </div>

                <?php
                if(isset($_SESSION["errors"])){
                    echo "<div class='alert alert-danger'>";
                    foreach ($_SESSION["errors"] as $error){
                        echo "<li>".$error."</li>";
                    }
                    echo "</div>";
                }
                ?>

                <!--        JUMBOTRON-->

                <div id="jumbo-01" class="jumbotron" style="border-radius: 50px">
                    <h2 class="display-5"><?php echo TXT_BO_INFO; ?></h2>
                    <hr class="my-4">

                    <div class="d-flex justify-content-center align-items-stretch">

                        <div class="p-2 Info">
                            <div class="list-group">
                                <?php foreach ($dataUser as $DASHBOARD_response) : ?>
                                    <a href="#" class="list-group-item list-group-item-action list-group-item-primary"><?php echo TXT_BO_LN . $DASHBOARD_response->lastName ?></a>
                                    <a href="#" class="list-group-item list-group-item-action list-group-item-secondary"><?php echo TXT_BO_FN . $DASHBOARD_response->firstName ?></a>
                                    <a href="#" class="list-group-item list-group-item-action list-group-item-success"><?php echo TXT_BO_M . $DASHBOARD_response->email ?></a>
                                <?php endforeach; ?>
                            </div><!--End list-group -->
                        </div><!-- end nfo-->

                        <div class="doc p-2">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action list-group-item-danger"><?php echo TXT_BO_CN . $DASHBOARD_response->contactNumber ?></a>
                                <a href="#" class="list-group-item list-group-item-action list-group-item-primary"><?php echo TXT_BO_ADDRESS . $DASHBOARD_response->address ?></a>
                                <a href="#" class="list-group-item list-group-item-action list-group-item-secondary"><?php echo TXT_BO_A . $DASHBOARD_response->advantage ?> %</a>
                            </div><!--End list-group -->
                        </div><!--end doc-->

                    </div><!-- end flex-->

                </div><!--End Jumbo info-->

            </div><!--End container -->
        </main>

    </div>
    <footer>
        <div class="footer">
            <div class="text">
                <?php
                echo '<p class="copyright">Click\'N Cook © ' . date('Y') . '</p>'
                ?>
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
    <script>window.jQuery || document.write('<script src="../../../bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../../bootstrap/docs/dist/js/bootstrap.min.js"></script>
    <script src="../../../bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    </body>
    </html>

    <?php
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../../../login/index.php');
    exit;
}
?>