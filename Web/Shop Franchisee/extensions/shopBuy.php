<?php
//session_start();
//if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
    echo'
        <link rel="stylesheet" href="../stripeApi/client/css/normalize.css" />
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
				$totalPrice += $orderData['quantity'] * $orderData['price'];
			}

		} else {
			http_response_code(400);
		}

        echo'
            <body>
                <div class="sr-root">
                  <div class="sr-main">
                    <header class="sr-header">
                      <div class="sr-header__logo"></div>
                    </header>
                    <div class="sr-payment-summary payment-view">
                      <h1 class="order-amount">' . $totalPrice . '€</h1>
                      <h4>Purchase a Pasha photo</h4>
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
                      <div class="sr-legal-text">
                        Your card will be charged ' . $totalPrice . '€.
                      </div>
                    </div>
                    <div class="sr-section hidden completed-view">
                      <div class="sr-callout">
                            <pre>
                
                            </pre>
                      </div>
                      <button onclick="window.location.href = \'/\';">Restart demo</button>
                    </div>
                  </div>
                </div>
            </body>
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
                                echo '<td>'	. $orderData['price'] . '$</td>';
                                echo '<td>' . $warehouseData['address'] . '</td>';
                                echo '<td>' . $orderData['quantity'] . '</td>';
                                echo '<td>' . $orderData['quantity'] * $orderData['price'] . '$</td>';
                                $totalPrice += $orderData['quantity'] * $orderData['price'];
                            echo '</tr>';
						}
					echo '<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><strong>Total price : ' . $totalPrice . '$</strong></td>
						</tr>
					</tbody>
				</table>
				<button type="button" class="btn btn-default btn-sm" id="buy">BUY</button>
				<a href="shop.php" class="btn btn-default btn-sm">Cancel</a>
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