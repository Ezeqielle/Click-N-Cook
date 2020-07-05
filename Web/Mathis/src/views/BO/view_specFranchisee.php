<?php
require "../../../assets/functions/functions.php";
require "../../../bdd/connection.php";

$pdo = connectDB();

$user = $_GET['idUser'];

$queryUser = $pdo->prepare("SELECT * FROM franchisee WHERE id = $user");
$queryUser->execute();
$dataUser = $queryUser->fetchAll(PDO::FETCH_OBJ);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>    Spec Franchisee    </title>
    <link rel="stylesheet" href="../../../assets/css/reset.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <link rel="stylesheet" href="../../../assets/css/bo_view.css">


</head>
<body>

<?php
if(isset($_SESSION["errors"])){
    echo "<div class='alert alert-danger'>";
    foreach ($_SESSION["errors"] as $error){
        echo "<li>".$error."</li>";
    }
    echo "</div>";
}
?>

<div class="container">

    <h1 id="title-01" class="display-3 text-bold">Franchisee dashboard</h1>

    <!--        NAV-->

    <div class="button-007 d-flex flex-row-reverse">
        <button type="button" class="upsetclass btn btn-warning" href="view_editFranchisee.php?idUser=<?php echo $user; ?>">edit</button>
        <button type="button" class="btn btn-danger">delete</button>
    </div><!-- end button -->


    <!--        JUMBOTRON-->


    <div id="jumbo-01" class="jumbotron">
        <h2 class="display-5">info</h2>
        <hr class="my-4">

        <div class="d-flex justify-content-center align-items-stretch">

            <div class="p-2 nfo">
                <div class="list-group">
                    <?php foreach ($dataUser as $DASHBOARD_response) : ?>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">Lastname: <?php echo $DASHBOARD_response->lastName ?></a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">Firstname: <?php echo $DASHBOARD_response->firstName ?></a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">Mail : <?php echo $DASHBOARD_response->email ?></a>
                    <?php endforeach; ?>
                </div><!--End list-group -->
            </div><!-- end nfo-->

            <div class="doc p-2">
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action list-group-item-danger">telephone: <?php echo $DASHBOARD_response->contact_number ?></a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-primary">permit de conduire: <?php echo $DASHBOARD_response->driversLicenceReference ?></a>
                    <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">n° sécurité social: <?php echo $DASHBOARD_response->socialSecurityNumber ?></a>
                </div><!--End list-group -->
            </div><!--end doc-->

        </div><!-- end flex-->

    </div><!--End Jumbo info-->

</div><!--End container -->

<script src="../../../assets/js/pie_chart.js"></script>
</body>
</html>