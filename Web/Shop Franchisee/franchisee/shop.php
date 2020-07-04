<?php
session_start();
if (isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 0) {

    require('../extensions/header.php');
    ?>
    <script type="text/javascript" src="../js/shop.js"></script>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <article class="col-lg-12 radius">
                        <input type="text" name="search" class="form-control search" id="searchShop" placeholder="Search">
                        <button type="button" id="btn" class="btn btn-default btn-sm">BUY</button>
                        <?php
                        $reqExistOrder = $db->prepare('SELECT * FROM PURCHASE WHERE idFranchisee = :currentId AND date IS NULL');
                        $reqExistOrder->execute(array(
                            'currentId' => $_SESSION['id']
                        ));
                        if($reqExistOrder->rowCount() > 0) {
                            echo '<a href="shopPayment.php" class="btn btn-default btn-sm">Last Bill</a>';
                        }
                        if(isset($_GET['payment']) AND !empty($_GET['payment']) AND $_GET['payment'] == 'success') {
                            echo '<div class="alert alert-success alert-dismissible">
								  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
 							      <strong>Success!</strong> Your payment was successfully.
							  </div>';
                        }
                        ?>

                    </article>
                </div>
                <?php
                $reqProduct = $db->query('SELECT * FROM ITEM WHERE product_status = true AND (SELECT quantity FROM BELONGIN WHERE quantity > 0 AND BELONGIN.idItem = ITEM.id)');

                echo '<div class="row" id="result">';
                while($productData = $reqProduct->fetch()){
                    $reqQuantityProduct = $db->query('SELECT * FROM BELONGIN WHERE idItem = ' .$productData["id"]);
                    $quantityData = $reqQuantityProduct->fetch();

                    $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$quantityData["idWarehouse"]);
                    $warehouseData = $reqWarehouse->fetch();

                    echo '<article name="products" class="col-lg-4 radius">';
                    echo $productData['name'] . '<br>';
                    echo $quantityData['quantity'] . ' left<br>';
                    echo number_format($productData['price'] + (($productData['price'] * 10) / 100), 2) . ' $<br>';
                    echo $warehouseData['address'] . '<br>';
                    echo '<select name="' . $productData['name'] . '">';
                    for($i = 0; $i <= $quantityData['quantity']; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    echo '</select>';
                    echo '</article>';
                }
                $reqProduct->closeCursor();
                echo '</div>';
                ?>
            </section>
        </div>
        <script>
            window.addEventListener("DOMContentLoaded", () => {
                const btn = document.querySelector('#btn');
                btn.addEventListener('click', event => {

                    const products = document.getElementsByName('products');
                    var productsArray = [];

                    //on boucle pour chercher tous les produits que l'utilisateur a choisi.
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
                    };
                });
            });
        </script>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/indexFranchisee.php');
    exit;
}
?>