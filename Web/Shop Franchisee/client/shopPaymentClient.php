<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 2) {

    require('../extensions/header.php');
    ?>
    <script src="../js/shopPaymentClient.js" type="text/javascript"></script>
    <main>
        <div class="row">
            <section class="col-lg-12"id="bill">
                <div class="row">
                    <article class="col-lg-12 radius">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Unit price</th>
                                <th>Amount</th>
                                <th>Price with amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <h1>Bill</h1>
                            <?php
                            $totalPrice = 0;
                            $reqOrderDish = $db->prepare('SELECT idPurchaseClient, idDishClient, bill_numberClient, date, DISHCLIENT.price, CONTAINSDISHSALECLIENT.quantity, DISHCLIENT.idClient, name  FROM CONTAINSDISHSALECLIENT, PURCHASECLIENT, DISHCLIENT WHERE date is NULL AND CONTAINSDISHSALECLIENT.idDishClient = DISHCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
                            $reqOrderDish->execute(array(
                                'currentId' => $_SESSION['id']
                            ));
                            while($orderDishData = $reqOrderDish->fetch()) {
                                echo '<tr>';
                                echo '<td>' . $orderDishData['name'] . '</td>';
                                echo '<td>' . number_format($orderDishData['price'] + (($orderDishData['price'] * 10) / 100), 2) . '€</td>';
                                echo '<td>' . $orderDishData['quantity'] . '</td>';
                                echo '<td>' . number_format($orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100)), 2) . '€</td>';
                                $totalPrice += $orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100));
                                echo '</tr>';
                            }

                            $reqOrderMenu = $db->prepare('SELECT idPurchaseClient, idMenuClient, bill_numberClient, date, MENUCLIENT.price, CONTAINSMENUSALECLIENT.quantity, MENUCLIENT.idClient, name  FROM CONTAINSMENUSALECLIENT, PURCHASECLIENT, MENUCLIENT WHERE date is NULL AND CONTAINSMENUSALECLIENT.idMenuClient = MENUCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
                            $reqOrderMenu->execute(array(
                                'currentId' => $_SESSION['id']
                            ));
                            while($orderMenuData = $reqOrderMenu->fetch()) {
                                echo '<tr>';
                                echo '<td>' . $orderMenuData['name'] . '</td>';
                                echo '<td>'	. number_format($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100), 2) . '€</td>';
                                echo '<td>' . $orderMenuData['quantity'] . '</td>';
                                echo '<td>' . number_format($orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100)), 2) . '€</td>';
                                $totalPrice += $orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100));
                                echo '</tr>';
                            }
                            if($totalPrice == 0) {
                                header('Location: shopClient.php?insert=error');
                            }
                            ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><strong><?php echo 'Total price : ' . number_format($totalPrice, 2) . '€';?></strong></td>
                            </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-default btn-sm" id="buy">BUY</button>
                        <a href="shopClient.php" class="btn btn-default btn-sm">Cancel</a>
                    </article>
                </div>
            </section>
        </div>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>