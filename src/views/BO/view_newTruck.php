<?php
session_start();
include('../../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../../lang/fr-lang.php');
} else {
    include('../../../lang/en-lang.php');
}
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 1 ){

    require "../../../bdd/connection.php";
    $pdo = connectDB();

    $queryFranchisee = $pdo->query("SELECT * FROM FRANCHISEE WHERE admin = 0");
    $queryFranchisee->execute();


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
                        <a class="nav clickNCook navbar-brand" href="view_allTruck.php" title="<?php echo TXT_EXTENSIONS_SHOPG; ?>">Click'N Cook</a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a  href="../../../admin/shopAdmin.php" class="glyphicon glyphicon-shopping-cart" title="<?php echo TXT_EXTENSIONS_SHOP; ?>"></a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li> <a href="view_allTruck.php" class="glyphicon glyphicon-user" title="<?php echo TXT_EXTENSIONS_PROFILE; ?>"></a> </li>
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
                            <h1 id="title-01" class="display-3 text-bold"><?php echo TXT_BO_AB; ?></h1>
                            <a class="btn btn-default btn-sm" href="view_allTruck.php"><?php echo TXT_BO_BACK; ?></a>
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

                <div id="jumbo-01" class="jumbotron" style="border-radius: 50px">
                    <form action="/assets/addTruck.php" method="post">
                        <form>
                            <label for="location"><?php echo TXT_BO_L; ?><span class="required">*</span></label>
                            <input type="text" id="location"
                                   name="location"
                                   class="form-control"
                                   placeholder="<?php echo TXT_BO_L; ?>"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["location"]:"";?>">
                            <label for="registration"><?php echo TXT_BO_R; ?><span class="required">*</span> </label>
                            <input type="text" id="registration"
                                   name="registration"
                                   class="form-control"
                                   placeholder="<?php echo TXT_BO_R; ?>"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["registration"]:"";?>"></br>
                            <label for="idFranchisee"><?php echo TXT_BO_IF; ?><span class="required">*</span> </label></br>
                            <?php
                                echo '<select name="idFranchisee">';
                                while($profil_data = $queryFranchisee->fetch()) {
                                    echo '<option value="' .  $profil_data['id'] . '">' . $profil_data['id'] . '</option>';
                                }
                                echo '</select>';
                            ?>
                            <br><br><input type="submit" class="btn btn-primary btn-block" value="<?php echo TXT_BO_ADD; ?>">
                        </form>
                    </form>
                </div>
            </div>
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
