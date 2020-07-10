<?php
function invoiceMail() {
    session_start();
    require_once "../bdd/connection.php";
    $db = connectDB();

    $header = "Mime-Version: 1.0\r\n";
    $header .= 'From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
    $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
    $header .= 'Content-Transfer-Encoding: 8bit';
    $email = $_SESSION['email'];
    $bill_number = $_SESSION['idPurchase'];

    $reqPurchase = $db->prepare('SELECT * FROM PURCHASE WHERE bill_number = :bill_number');
    $reqPurchase->execute(array(
        'bill_number' => $bill_number
    ));
    $purchaseData = $reqPurchase->fetch();

    $reqContainsIn = $db->prepare('SELECT * FROM CONTAINSIN WHERE idPurchase = :bill_number');
    $reqContainsIn->execute(array(
        'bill_number' => $bill_number
    ));


    $objet = "Invoice of your purchase";
    $message = "
    <html>
    <head>
       <title>Thank you for Purchase on Click'N Cook !</title>
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
                    while($containsInData = $reqContainsIn->fetch()) {
                        $reqItem = $db->query('SELECT * FROM ITEM WHERE id = ' . $containsInData['iditem']);
                        $itemData = $reqItem->fetch();

                        $message .= "<tr>
                            <td>" . $itemData['name'] . "</td>
                            <td>"	. $itemData['price'] . "$</td>
                            <td>" . $containsInData['quantity'] . "</td>
                            <td>" . $containsInData['quantity'] * $itemData['price']. "$</td>
                            </tr>";
                        $totalPrice += $containsInData['quantity'] * $itemData['price'];

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
    mail('support@clickncook.ovh', $objet, $message, $header);
}
?>