<?php
function invoiceClientMail() {
    session_start();
    require_once "../bdd/connection.php";
    $db = connectDB();

    $header = "Mime-Version: 1.0\r\n";
    $header .= 'From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
    $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
    $header .= 'Content-Transfer-Encoding: 8bit';
    $email = $_SESSION['email'];
    $bill_number = $_SESSION['idPurchaseClient'];

    $reqPurchase = $db->prepare('SELECT * FROM PURCHASECLIENT WHERE bill_numberClient = :bill_numberClient');
    $reqPurchase->execute(array(
        'bill_numberClient' => $bill_number
    ));
    $purchaseData = $reqPurchase->fetch();

    $reqOrderDish = $db->prepare('SELECT * FROM CONTAINSDISHSALECLIENT WHERE idPurchaseClient = :idPurchase');
    $reqOrderDish->execute(array(
        'idPurchase' => $purchaseData['bill_numberClient']
    ));

    $reqOrderMenu = $db->prepare('SELECT * FROM CONTAINSMENUSALECLIENT WHERE idPurchaseClient = :idPurchase');
    $reqOrderMenu->execute(array(
        'idPurchase' => $purchaseData['bill_numberClient']
    ));

    $reqFranchisee = $db->prepare('SELECT * FROM FRANCHISEE WHERE id = :idFranchisee');
    $reqFranchisee->execute(array(
        'idFranchisee' => $_SESSION['idFranchisee']
    ));
    $franchiseeData = $reqFranchisee->fetch();


    $objet = "Invoice of your purchase";
    $message = "
    <html>
    <head>
       <title>Thank you for Purchase on " . $franchiseeData["nameFranchise"] . " !</title>
       <style type='text/css'>
            table {
                border-spacing: 0;
                border-collapse: collapse;
            }
              .table-bordered th,
            .table-bordered td {
                border: 1px solid #ddd !important;
            }
            .table-striped > tbody > tr:nth-of-type(odd) {
                background-color: #f9f9f9;
            }

        </style>
    </head>
    <body>
		<h1>Bill</h1>
			<table class='table table-bordered table-striped'>
				<thead>
			      <tr>
			        <th>Product</th>
			        <th>Unit price</th>
			        <th>Amount</th>
			        <th>Price with amount</th>
			      </tr>
			    </thead>
			    <tbody>";
                    $totalPrice = 0;
                    while($orderDishData = $reqOrderDish->fetch()) {
                        $reqItem = $db->query('SELECT * FROM DISHCLIENT WHERE id = ' . $orderDishData['idDishClient']);
                        $dishData = $reqItem->fetch();

                        $message .= "<tr>
                            <td>" . $dishData['name'] . "</td>
                            <td>"	. $dishData['price'] . "$</td>
                            <td>" . $orderDishData['quantity'] . "</td>
                            <td>" . $orderDishData['quantity'] * $dishData['price']. "$</td>
                            </tr>";
                        $totalPrice += $orderDishData['quantity'] * $dishData['price'];

                    }

                    while($orderMenuData = $reqOrderMenu->fetch()) {
                        $reqItem = $db->query('SELECT * FROM MENUCLIENT WHERE id = ' . $orderMenuData['idMenuClient']);
                        $menuData = $reqItem->fetch();

                        $message .= "<tr>
                            <td>" . $menuData['name'] . "</td>
                            <td>"	. $menuData['price'] . "$</td>
                            <td>" . $orderMenuData['quantity'] . "</td>
                            <td>" . $orderMenuData['quantity'] * $menuData['price']. "$</td>
                            </tr>";
                        $totalPrice += $orderMenuData['quantity'] * $menuData['price'];

                    }
                    $message .= "<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><strong>Total price : " . $totalPrice . "$</strong></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><strong>Total price with tva : " . $purchaseData['price'] . "$</strong></td>
					</tr>
				</tbody>
			</table>
			<p><i>This is an automatic email, please do not answer it</i></p>
		</body>
    </html>";

    mail($email, $objet, $message, $header);
    mail($franchiseeData['email'], $objet, $message, $header);
}
?>