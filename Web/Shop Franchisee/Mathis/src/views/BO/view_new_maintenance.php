<?php
session_start();


?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>    Add Maintenance    </title>
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
        foreach ($_SESSION["errors"] as $error) {
            echo "<li>".$error."</li>";
        }
        echo "</div>";
    }
    ?>
        <div class="container">
            <form action="../../../assets/functions/addMaintenance.php" method="post">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputDate">Date <span class="required">*</span></label>
                            <input type="date" id="date"
                                   name="date"
                                   class="form-control"
                                   placeholder="Date"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["date"]:"";?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputGarageName">Garage Name <span class="required">*</span> </label>
                            <input type="text" id="garageName"
                                   name="garageName"
                                   class="form-control"
                                   placeholder="Garage Name"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["garageName"]:"";?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputAge">Age <span class="required">*</span> </label>
                            <input type="number" id="age"
                                   name="age"
                                   class="form-control"
                                   placeholder="Age"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["age"]:"";?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputDescription">Description <span class="required">*</span> </label>
                            <input type="text" id="description"
                                   name="description"
                                   class="form-control"
                                   placeholder="description"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["description"]:"";?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputPrice">Price <span class="required">*</span> </label>
                            <input type="number" id="price"
                                   name="price"
                                   class="form-control"
                                   placeholder="Price"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["price"]:"";?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputMileage">Mileage <span class="required">*</span> </label>
                            <input type="number" id="mileage"
                                   name="mileage"
                                   class="form-control"
                                   placeholder="Mileage"
                                   required="required"
                                   autofocus="autofocus"
                                   value="<?php echo (isset($_SESSION["errorsInput"]))?$_SESSION["errorsInput"]["mileage"]:"";?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary col-md-12">Add</button>
                </form>
            </form>
        </div>
    </body>
</html>