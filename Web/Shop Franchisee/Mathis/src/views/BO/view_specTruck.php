<?php
require "../../../assets/functions/functions.php";
require "../../../bdd/connection.php";

$pdo = connectDB();

$queryTruck = $pdo->prepare("SELECT * FROM truck");
$queryTruck->execute();
$dataTruck = $queryTruck->fetchAll(PDO::FETCH_OBJ);

$queryMaintenance = $pdo->prepare("SELECT * FROM maintenance");
$queryMaintenance->execute();
$dataMaintenance = $queryMaintenance->fetchAll(PDO::FETCH_OBJ);
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>    Spec Truck    </title>
    <link rel="stylesheet" href="../../../assets/css/reset.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Custom fonts for this template-->
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

        <h1 id="title-01" class="display-3 text-bold">Truck dashboard</h1>

<!--        NAV-->

        <div class="button-007 d-flex flex-row-reverse">
            <button type="button" class="btn btn-info">Update</button>
            <button type="button" class="upsetclass btn btn-warning">edit</button>
            <button type="button" class="btn btn-danger">delete</button>
        </div><!-- end button -->


<!--        JUMBOTRON-->


        <div id="jumbo-01" class="jumbotron">
            <h2 class="display-5">info</h2>
            <hr class="my-4">

            <div class="d-flex justify-content-center align-items-stretch">

                <div class="p-2 nfo">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Identification</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">Id : 345</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">Car licence plate : Bo 345 AA</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">Franchisee : Jerome</a>
                    </div><!--End list-group -->
                </div><!-- end nfo-->

                <div class="maintenance p-2">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Maintenance</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">12/12/2012</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">12/01/2011</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-light">more...</a>
                    </div><!--End list-group -->
                </div><!--end maintenance-->

                <div class="doc p-2">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">docs</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">Carte grise</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">Assurance</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">Livret</a>
                    </div><!--End list-group -->
                </div><!--end doc-->

            </div><!-- end flex-->


        </div><!--End Jumbo info-->

        <div id="jumbo-02" class="jumbotron">
            <h2 class="display-5">dashboard</h2>
            <hr class="my-4">
            <div class="d-flex justify-content-center align-items-stretch">

                <div class="Invoice p-2">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Invoices</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-primary">01/01/2020</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">12/01/2019</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-success">12/01/1999</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-light">more</a>
                    </div><!--End list-group -->
                </div><!--end Invoice-->
                <div class="stat p-2 ">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action list-group-item-danger">Stat</a>
                        <a href="#" class="list-group-item list-group-item-action list-group-item-light">
                            <canvas id="pieChart"></canvas>
                        </a>
                    </div><!--End list-group -->
                </div><!--end stat-->
            </div><!--end  d-flex -->
        </div><!--end jumbo dashboard -->




    </div><!--End container -->

    <script src="../../../assets/js/pie_chart.js"></script>
</body>
</html>