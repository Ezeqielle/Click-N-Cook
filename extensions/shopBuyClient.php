<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id']) AND $_SESSION['administrator'] == 2) {
    echo'
        <link rel="stylesheet" href="/stripeApiC/css/global.css" />
        <script src="https://js.stripe.com/v3/"></script>
        <script src="/stripeApiC/scriptClient.js" defer></script>
    ';

    include('../lang/lang.php');

    if($_SESSION['lang'] == 'FR') {
        include('../lang/fr-lang.php');
    } else {
        include('../lang/en-lang.php');
    }

    if(isset($_GET['requestForm'])) {
        $totalPrice = 0;

        require_once "../bdd/connection.php";
        $db = connectDB();

        $reqOrderDish = $db->prepare('SELECT idPurchaseClient, idDishClient, bill_numberClient, date, DISHCLIENT.price, CONTAINSDISHSALECLIENT.quantity, DISHCLIENT.idClient, name  FROM CONTAINSDISHSALECLIENT, PURCHASECLIENT, DISHCLIENT WHERE date is NULL AND CONTAINSDISHSALECLIENT.idDishClient = DISHCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND DISHCLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderDish->execute(array(
            'currentId' => $_SESSION['id']
        ));
        if(($orderDishDatas = $reqOrderDish->fetchALL()) != NULL) {
            if($reqOrderDish->rowCount() > 0) {
                foreach($orderDishDatas as $orderDishData) {
                    $totalPrice += $orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100));
                }

            } else {
                http_response_code(400);
            }
        }

        $reqOrderMenu = $db->prepare('SELECT idPurchaseClient, idMenuClient, bill_numberClient, date, MENUCLIENT.price, CONTAINSMENUSALECLIENT.quantity, MENUCLIENT.idClient, name  FROM CONTAINSMENUSALECLIENT, PURCHASECLIENT, MENUCLIENT WHERE date is NULL AND CONTAINSMENUSALECLIENT.idMenuClient = MENUCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND MENUCLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderMenu->execute(array(
            'currentId' => $_SESSION['id']
        ));

        if(($orderMenuDatas = $reqOrderMenu->fetchALL()) != NULL) {
            if($reqOrderMenu->rowCount() > 0) {
                foreach($orderMenuDatas as $orderMenuData) {
                    $totalPrice += $orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100));
                }
            } else {
                http_response_code(400);
            }
        }

        echo'
	        <div class="row">
                <section class="col-lg-4">
                </section>
				<article class="col-lg-4 radius">
                <div class="sr-root">
                  <div class="sr-main">
                    <header class="sr-header">
                      <div class="sr-header__logo"></div>
                    </header>
                    <div class="sr-payment-summary payment-view">
                      <h1 class="order-amount">' . number_format($totalPrice, 2) . TXT_CLIENT_MONNEY .'</h1>
                      <h4>' . TXT_EXTENSIONS_PF . '</h4>
                    </div>
                    <div class="sr-payment-form payment-view">
                      <div class="sr-form-row">
                        <label for="card-element">
                          ' . TXT_EXTENSIONS_PAYD . '
                        </label>
                        <div class="sr-combo-inputs">
                          <div class="sr-combo-inputs-row">
                            <input type="text" id="name" placeholder="' . TXT_EXTENSIONS_NAME . '" autocomplete="cardholder" class="sr-input"/>
                          </div>
                          <div class="sr-combo-inputs-row">
                            <div class="sr-input sr-card-element" id="card-element" ></div>
                          </div>
                        </div>
                        <div class="sr-field-error" id="card-errors" role="alert"></div>
                      </div>
                      <button id="submit"><div class="spinner hidden" id="spinner"></div><span id="button-text">' . TXT_EXTENSIONS_PAY . '</span></button>
                      <button id="cancel"><div class="spinner hidden" id="spinner"></div><span id="button-text">' . TXT_CLIENT_CANCEL . '</span></button>
                      <div class="sr-legal-text">' . TXT_EXTENSIONS_CARD . number_format($totalPrice, 2) . TXT_CLIENT_MONNEY . '.
                      </div>
                    </div>
                  </div>
                </div>
                </article>
                <section class="col-lg-4">
                </section>
            </div>
        ';
    }
    if(isset($_GET['cancel'])) {

        require_once "../bdd/connection.php";
        $db = connectDB();

        $reqOrderDish = $db->prepare('SELECT idPurchaseClient, idDishClient, bill_numberClient, date, DISHCLIENT.price, CONTAINSDISHSALECLIENT.quantity, DISHCLIENT.idClient, name  FROM CONTAINSDISHSALECLIENT, PURCHASECLIENT, DISHCLIENT WHERE date is NULL AND CONTAINSDISHSALECLIENT.idDishClient = DISHCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND DISHCLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderDish->execute(array(
            'currentId' => $_SESSION['id']
        ));

        $reqOrderMenu = $db->prepare('SELECT idPurchaseClient, idMenuClient, bill_numberClient, date, MENUCLIENT.price, CONTAINSMENUSALECLIENT.quantity, MENUCLIENT.idClient, name  FROM CONTAINSMENUSALECLIENT, PURCHASECLIENT, MENUCLIENT WHERE date is NULL AND CONTAINSMENUSALECLIENT.idMenuClient = MENUCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND MENUCLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderMenu->execute(array(
            'currentId' => $_SESSION['id']
        ));

        if($reqOrderDish->rowCount() > 0 || $reqOrderMenu->rowCount() > 0) {

            echo '
                <div class="row">
                    <article class="col-lg-12 radius" >
                        <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>' . TXT_CLIENT_PR . '</th>
                                <th>' . TXT_CLIENT_UP . '</th>
                                <th>' . TXT_CLIENT_AMOUNT . '</th>
                                <th>' . TXT_CLIENT_PWA . '</th>
                              </tr>
                            </thead>
                            <tbody>
                                <h1>' . TXT_CLIENT_BILLS . '</h1>';
                                $totalPrice = 0;
                                while($orderDishData = $reqOrderDish->fetch()) {
                                    echo '<tr>';
                                    echo '<td>' . $orderDishData['name'] . '</td>';
                                    echo '<td>'	. number_format($orderDishData['price'] + (($orderDishData['price'] * 10) / 100), 2) . TXT_CLIENT_MONNEY . '</td>';
                                    echo '<td>' . $orderDishData['quantity'] . '</td>';
                                    echo '<td>' . number_format($orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100)), 2) . TXT_CLIENT_MONNEY . '</td>';
                                    $totalPrice += $orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100));
                                    echo '</tr>';
                                }


                                while($orderMenuData = $reqOrderMenu->fetch()) {
                                    echo '<tr>';
                                    echo '<td>' . $orderMenuData['name'] . '</td>';
                                    echo '<td>'	. number_format($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100), 2) . TXT_CLIENT_MONNEY . '</td>';
                                    echo '<td>' . $orderMenuData['quantity'] . '</td>';
                                    echo '<td>' . number_format($orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100)), 2) . TXT_CLIENT_MONNEY . '</td>';
                                    $totalPrice += $orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100));
                                    echo '</tr>';
                                }
                                echo '<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>' . TXT_CLIENT_TOTAL . number_format($totalPrice, 2) . TXT_CLIENT_MONNEY . '</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <button style="margin-top: 0px" type="button" class="btn btn-default btn-sm" id="buy">' . TXT_CLIENT_BUY . '</button>
                        <a style="margin-top: 0px" href="shopClient.php" class="btn btn-default btn-sm">' . TXT_CLIENT_CANCEL . '</a>
                    </article>
                </div>
			';
        } else {
            http_response_code(400);
        }
    }
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}