<?php
session_start();
require "../../../../assets/functions/functions.php";
require "../../../../bdd/connection.php";

$pdo = connectDB();

$truck = $_GET['idTruck'];
$_SESSION['truck'] = $truck;
$queryUser = $pdo->prepare("SELECT * FROM truck WHERE id = $truck");
$queryUser->execute();
$dataTruck = $queryUser->fetchAll(PDO::FETCH_OBJ);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>    Edit profile   </title>
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

    <h1 id="title-01" class="display-3 text-bold">Edit profile</h1>

    <!--        JUMBOTRON-->


    <div id="jumbo-01" class="jumbotron">
        <h2 class="display-5">EDIT</h2>
        <hr class="my-4">

        <div class="d-flex justify-content-center align-items-stretch">

            <form action="../../../assets/functions/editTruck.php" method="POST">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-label-group">
                                <input type=hidden" id="truck_id" class="form-control" required="required" autofocus="autofocus" value="<?php echo $dataTruck[0]->id?>">
                            </div>
                            <div class="form-label-group">
                                <input type="text" id="carRegistration" name="carRegistration" class="form-control" required="required" autofocus="autofocus" value="<?php echo $dataTruck[0]->registration ?>">
                                <label for="carRegistration">Car registration</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-label-group">
                                <input type="text" id="location" name="location" class="form-control" required="required" autofocus="autofocus" value="<?php echo $dataTruck[0]->location ?>">
                                <label for="location">Location</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <input type="text" id="idFranchisee" name="idFranchisee" class="form-control" required="required" value="<?php echo $dataTruck[0]->idFranchisee ?>">
                        <label for="idFranchisee">id Franchisee</label>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary btn-block" value="Update">
            </form>

            <div class="text-center">
                <a class="d-block small mt-3" href="view_specTruck.php.php?idUser=<?php echo $truck ?>">Back to truck</a>
            </div>

        </div><!-- end flex-->

    </div><!--End Jumbo info-->

</div><!--End container -->

</body>
</html>
