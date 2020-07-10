<?php
session_start();
include('../../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../../lang/fr-lang.php');
} else {
    include('../../../lang/en-lang.php');
}
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND ($_SESSION['administrator'] == 0 || $_SESSION['administrator'] == 1) AND isset($_GET['idTruck'])){
    require "../../../assets/functions/functions.php";
    require "../../../bdd/connection.php";

    $pdo = connectDB();

    $truck = $_GET['idTruck'];
    $_SESSION['truck'] = $truck;

    $pdo = connectDB();
    $query = $pdo->prepare("SELECT * FROM BREAKDOWN WHERE idTruck = $truck");
    $query->execute();
    $profil_data = $query->fetchAll(PDO::FETCH_OBJ);

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
                        <a class="nav clickNCook navbar-brand" href="<?php if($_SESSION['administrator'] == 1) {  echo "view_allTruck.php"; } else { echo "../../../franchisee/shopGestion.php"; } ?>" title="<?php echo TXT_BO_TRUCK; ?>">Click'N Cook</a>
                    </div>
                    <div id="navbar" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li>
                                <a  href="<?php if($_SESSION['administrator'] == 1) {  echo "../../../admin/shopAdmin.php"; } else { echo "../../../franchisee/shop.php"; } ?>" class="glyphicon glyphicon-shopping-cart" title="<?php echo TXT_EXTENSIONS_SHOPG; ?>"></a>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li> <a href="<?php if($_SESSION['administrator'] == 1) {  echo 'view_allTruck.php'; } else { echo 'view_specFranchisee.php?idUser=' . $_SESSION['id']; } ?>" class="glyphicon glyphicon-user" title="<?php echo TXT_EXTENSIONS_PROFILE; ?>"></a> </li>
                            <li> <a href="../../../extensions/disconnect.php" class="glyphicon glyphicon-off"></a> </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <?php
        if(isset($_SESSION["errors"])){
            echo "<div class='alert alert-danger'>";
            foreach ($_SESSION["errors"] as $error){
                echo "<li>".$error."</li>";
            }
            echo "</div>";
        }
        ?>
        <main>
            <div class="container">
                <section class="col-lg-3"></section>
                <div class="row">
                    <article class="col-lg-6 radius">
                        <center>
                            <h1><?php echo TXT_BO_BKV; ?></h1>
                            <a class="btn btn-default btn-sm" href="view_specTruck.php?idTruck=<?php echo $truck; ?>"><?php echo TXT_BO_BACK; ?></a>
                            <a class="btn btn-default btn-sm" href="view_newBreakdown.php"><?php echo TXT_BO_ADD; ?></a>
                        </center>
                    </article>
                </div>
            </div>
            <div class="jumbotron" style="border-radius: 50px">
                <section class="listArray">
                    <table class="table">
                        <thead class="thread-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><?php echo TXT_BO_DATE; ?></th>
                            <th scope="col"><?php echo TXT_BO_DES; ?></th>
                            <th scope="col"><?php echo TXT_BO_STATUS; ?></th>
                            <th scope="col"><?php echo TXT_BO_PRICE; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($profil_data as $DASHBOARD_response) : ?>

                            <tr id = "<?php echo $DASHBOARD_response->id ?>">
                                <th scope="row"><?php echo $DASHBOARD_response->id ?></th>
                                <td><?php echo $DASHBOARD_response->date; ?></td>
                                <td><?php echo $DASHBOARD_response->description; ?></td>
                                <td><?php if($DASHBOARD_response->repaired == 1) { echo TXT_BO_REPAIR; } else { echo TXT_BO_NREPAIR; } ?></td>
                                <td><?php echo $DASHBOARD_response->price; ?></td>
                            </tr>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            </div>
            <script type="text/javascript" src="../../../assets/js/js_viewADMIN.js"></script>
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
