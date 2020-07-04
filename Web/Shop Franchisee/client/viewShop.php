<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../extensions/lang.php');
if(isset($_POST['choose'])) {
    $_SESSION['idFranchisee'] = $_POST['id'];
    header('Location: shopClient.php');

}
if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

    require('../extensions/header.php');
    ?>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <article class="col-lg-12 radius">
                        <input type="text" name="search" class="form-control search" id="searchShop" placeholder="Search">
                    </article>
                </div>
                <?php
                $reqFranchisee = $db->query('SELECT * FROM FRANCHISEE WHERE active = TRUE AND entranceFee = TRUE AND admin = FALSE AND stripeKey IS NOT NULL');

                echo '<div class="row" id="result">';
                while($franchiseeData = $reqFranchisee->fetch()){
                    $reqLocationTruck = $db->query('SELECT location FROM TRUCK WHERE idFranchisee = ' .$franchiseeData["id"]);
                    $locationTruckData = $reqLocationTruck->fetch();

                    echo '<article name="franchisee" class="col-lg-4 radius">';
                    echo '<form action="viewShop.php" method="post" class="form-signin">';
                    echo $franchiseeData['nameFranchise'] . '<br>';
                    echo $franchiseeData['description'] . '<br>';
                    echo 'Ratings : ' . $franchiseeData['note'] . '<br>';
                    echo 'Location : ' . $locationTruckData['location'] . '<br>';
                    echo '<input type="hidden" name="id" value="' . $franchiseeData['id'] . '">';
                    echo '<button type="submit" name="choose" id="btn" class="btn btn-default btn-sm">Choose</button>';
                    echo '</form>';
                    echo '</article>';
                }
                $reqFranchisee->closeCursor();
                echo '</div>';
                ?>
            </section>
        </div>
        <script>
            window.addEventListener("DOMContentLoaded", () => {
                const btn = document.querySelector('#btn');
                btn.addEventListener('click', event => {

                    const idFranchisee = document.getElementsByName('franchisee');
                    for(let i = 0; i < idFranchisee.length; i++){
                        const productsSelect = idFranchisee[i].lastChild;
                        const choice = productsSelect.selectedIndex;

                        console.log(productsSelect.name);
                        console.log(productsSelect.value);
                    }

                    //console.log(idFranchisee.value);

                    /*const products = document.getElementsByName('products');
                    var productsArray = [];

                    for(let i = 0; i < products.length; i++){
                        const productsSelect = products[i].lastChild;
                        const choice = productsSelect.selectedIndex;
                        if(productsSelect.options[choice].value > 0){
                            productsArray.push(productsSelect.name);
                            productsArray.push(productsSelect.options[choice].value);
                        }
                    }

                    //on transforme le tableau en chaîne de caractère pour pouvoir l'envoyer en POST.
                    productsArray = JSON.stringify(productsArray);

                    //on fait la requête seulement si le tableau est rempli c'est à dire supérieur à deux caractères -> [].
                    if(productsArray.length > 2){
                        $.ajax({
                            type: 'POST',
                            url: "../extensions/insertOrderr.php",
                            data: {
                                productsArray: productsArray,
                            },
                            success: output => {
                                console.log(output);
                                window.location.replace('shopPayment.php');
                            }
                        })
                    };*/
                });
            });
        </script>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>