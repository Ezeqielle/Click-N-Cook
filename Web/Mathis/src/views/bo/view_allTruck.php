<?php

require "../../../assets/functions/functions.php";
require "../../../bdd/connection.php";

$pdo = connectDB();
$query = $pdo->prepare("SELECT * FROM truck");
$query->execute();
$profil_data = $query->fetchAll(PDO::FETCH_OBJ);

?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>    All Truck    </title>
        <link rel="stylesheet" href="../../../assets/css/reset.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <!-- Custom fonts for this template-->
        <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>

    <body>
        <div class="container">
            <section class="headerAdmin">
                <h1>administrator view</h1>
                <a href="view_allFranchisee.php">Display all Franchisee</a>
            </section>

            <section class="listArray">
                <table class="table">
                    <thead class="thread-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">email</th>
                            <th scope="col">statut</th>
                            <th scope="col">delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($profil_data as $DASHBOARD_response) : ?>
                        <tr id = "<?php echo $DASHBOARD_response->id ?>">
                            <th scope="row"><?php echo $DASHBOARD_response->id ?></th>
                            <td><button class="btn btn-danger btn-sm delete">Delete</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </div>

        <script type="text/javascript" src="../../../assets/js/js_viewADMIN.js"></script>
    </body>
</html>
