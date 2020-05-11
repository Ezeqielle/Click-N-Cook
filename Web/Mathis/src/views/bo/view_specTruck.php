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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
        <div class="row">
            <form action="../../assets/functions/addUser.php" method="POST">
                <div class="form-row">
                    <!-- ID -->
                    <div class="form-group col-md-4">
                        <label for="inputID">Truck ID :</label>
                        <p type="text" id="id"
                               name="id"
                               class="form-control"
                               placeholder="id"
                               required="required"
                               autofocus="autofocus"
                               value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["id"]:"";?>">
                    </div>
                    <!-- location -->
                    <div class="form-group col-md-4">
                        <label for="inputLocation">Location :</label>
                        <p type="text" id="location"
                               name="location"
                               class="form-control"
                               placeholder="location"
                               required="required"
                               autofocus="autofocus"
                               value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["location"]:"";?>">
                    </div>
                    <!-- id franchisee -->
                    <div class="form-group col-md-4">
                        <label for="inputIdFranchisee">Franchisee Name :</label>
                        <p type="text" id="franchisee"
                               name="franchisee"
                               class="form-control"
                               placeholder="franchisee"
                               required="required"
                               autofocus="autofocus"
                               value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["franchisee"]:"";?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <!-- maintenance -->
            <section class="listArray">
                <table class="table">
                    <tbody class="thread-dark">
                        <?php foreach ($dataMaintenance as $DASHBOARD_response) : ?>
                        <tr id="<?php $DASHBOARD_response->id ?>">
                            <th scope="row"><?php echo $DASHBOARD_response->id ?></th>
                            <td><?php echo $DASHBOARD_response->date ?></td>
                            <td><?php echo $DASHBOARD_response->garageName ?></td>
                            <td><?php echo $DASHBOARD_response->age ?></td>
                            <td><?php echo $DASHBOARD_response->mileage ?></td>
                            <td><?php echo $DASHBOARD_response->price ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>


</body>
</html>