<?php
//session_start();
//if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
    echo'
        <link rel="stylesheet" href="../stripeApi/client/css/global.css" />
        <script src="https://js.stripe.com/v3/"></script>
        <script src="../stripeApi/client/script.js" defer></script>
    ';
	if(isset($_GET['requestForm'])) {

		require_once "../bdd/connection.php";
		$db = connectDB();
		
		$reqOrder = $db->prepare('SELECT * FROM CONTAINSIN, PURCHASE, ITEM WHERE idFranchisee = :currentId AND CONTAINSIN.idPurchase = PURCHASE.bill_number AND CONTAINSIN.idItem = ITEM.id AND date IS NULL');
		$reqOrder->execute(array(
			'currentId' => /*$_SESSION['id']*/10
		));
		if($reqOrder->rowCount() > 0) {
			$totalPrice = 0;
			while($orderData = $reqOrder->fetch()) {
				$totalPrice += $orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100));
			}

		} else {
			http_response_code(400);
		}

        echo'
	        <div class="row">
                <section class="col-lg-4">
                </section>
				<article class="col-lg-4">
                <div class="sr-root">
                  <div class="sr-main">
                    <header class="sr-header">
                      <div class="sr-header__logo"></div>
                    </header>
                    <div class="sr-payment-summary payment-view">
                      <h1 class="order-amount">' . number_format($totalPrice, 2) . '€</h1>
                      <h4>Purchase food</h4>
                    </div>
                    <div class="sr-payment-form payment-view">
                      <div class="sr-form-row">
                        <label for="card-element">
                          Payment details
                        </label>
                        <div class="sr-combo-inputs">
                          <div class="sr-combo-inputs-row">
                            <input type="text" id="name" placeholder="Name" autocomplete="cardholder" class="sr-input"/>
                          </div>
                          <div class="sr-combo-inputs-row">
                            <div class="sr-input sr-card-element" id="card-element"></div>
                          </div>
                        </div>
                        <div class="sr-field-error" id="card-errors" role="alert"></div>
                      </div>
                      <button id="submit"><div class="spinner hidden" id="spinner"></div><span id="button-text">Pay</span></button>
                      <button id="cancel"><div class="spinner hidden" id="spinner"></div><span id="button-text">Cancel</span></button>
                      <div class="sr-legal-text">
                        Your card will be charged ' . number_format($totalPrice, 2) . '€.
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

        $reqOrder = $db->prepare('SELECT * FROM CONTAINSIN, PURCHASE, ITEM WHERE idFranchisee = :currentId AND CONTAINSIN.idPurchase = PURCHASE.bill_number AND CONTAINSIN.idItem = ITEM.id AND date IS NULL');
        $reqOrder->execute(array(
            'currentId' => /*$_SESSION['id']*/10
        ));
		if($reqOrder->rowCount() > 0) {

			echo '
                <div class="row">
                    <article class="col-lg-12" >
                        <table class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>Product</th>
                                <th>Unit price</th>
                                <th>Address</th>
                                <th>Amount</th>
                                <th>Price with amount</th>
                              </tr>
                            </thead>
                            <tbody>
                                <h1>Bill</h1>';
                                $totalPrice = 0;
                                while($orderData = $reqOrder->fetch()) {
                                    $reqWarehouse = $db->query('SELECT address FROM WAREHOUSE WHERE id = ' .$orderData["idWarehouse"]);
                                    $warehouseData = $reqWarehouse->fetch();
                                    echo '<tr>';
                                        echo '<td>' . $orderData['name'] . '</td>';
                                        echo '<td>'	. number_format($orderData['price'] + (($orderData['price'] * 10) / 100), 2) . '€</td>';
                                        echo '<td>' . $warehouseData['address'] . '</td>';
                                        echo '<td>' . $orderData['quantity'] . '</td>';
                                        echo '<td>' . number_format($orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100)), 2) . '€</td>';
                                        $totalPrice += $orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100));
                                    echo '</tr>';
                                }
                            echo '<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><strong>Total price : ' . number_format($totalPrice, 2) . '€</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <button style="margin-top: 0px" type="button" class="btn btn-default btn-sm" id="buy">BUY</button>
                        <a style="margin-top: 0px" href="shop.php" class="btn btn-default btn-sm">Cancel</a>
                    </article>
                </div>
			';
		} else {
			http_response_code(400);
		}
	}
/*} else {
	echo '<img src="https://http.cat/401" alt="not found">';
	//header('Location: ../login/login.php');
	exit;
}*/