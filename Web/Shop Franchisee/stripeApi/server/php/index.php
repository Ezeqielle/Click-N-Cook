<?php
ini_set('display_errors',1);
error_reporting(E_ALL);
session_start();
use Slim\Http\Request;
use Slim\Http\Response;
use Stripe\Stripe;
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

require './config.php';

if (PHP_SAPI == 'cli-server') {
  $_SERVER['SCRIPT_NAME'] = '/index.php';
}



$app = new \Slim\App;


$app->add(function ($request, $response, $next) {
    Stripe::setApiKey('sk_test_51GqKO7I442Tn6Kr1nvkyXoh2eMVYifLVsgcPNRtXodT0Ebt9rtxioyqv7UVwkOAiNo682bV2dWDr7LQ9QElBz09m00qyGJZ9lT');
    return $next($request, $response);
});


$app->get('/', function (Request $request, Response $response, array $args) {   
  // Display checkout page
  return $response->write(file_get_contents('../../client/index.html'));
});

function calculateOrderAmount($items) {
    require_once "../../../bdd/connection.php";
    $db = connectDB();

    $reqOrder = $db->prepare('SELECT * FROM CONTAINSIN, PURCHASE, ITEM WHERE idFranchisee = :currentId AND CONTAINSIN.idPurchase = PURCHASE.bill_number AND CONTAINSIN.idItem = ITEM.id AND date IS NULL');
    $reqOrder->execute(array(
        'currentId' => $_SESSION['id']
    ));

    $reqOrderDish = $db->prepare('SELECT idPurchaseClient, idDishClient, bill_numberClient, date, DISHCLIENT.price, CONTAINSDISHSALECLIENT.quantity, DISHCLIENT.idClient, name  FROM CONTAINSDISHSALECLIENT, PURCHASECLIENT, DISHCLIENT WHERE date IS NULL AND CONTAINSDISHSALECLIENT.idDishClient = DISHCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
    $reqOrderDish->execute(array(
        'currentId' => $_SESSION['id']
    ));

    $reqOrderMenu = $db->prepare('SELECT idPurchaseClient, idMenuClient, bill_numberClient, date, MENUCLIENT.price, CONTAINSMENUSALECLIENT.quantity, MENUCLIENT.idClient, name  FROM CONTAINSMENUSALECLIENT, PURCHASECLIENT, MENUCLIENT WHERE date IS NULL AND CONTAINSMENUSALECLIENT.idMenuClient = MENUCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
    $reqOrderMenu->execute(array(
        'currentId' => $_SESSION['id']
    ));

    if($reqOrder->rowCount() > 0) {
        $totalPriceTva = 0;
        $totalPrice = 0;
        while($orderData = $reqOrder->fetch()) {

            if($orderData['dish'] == TRUE) {
                $insertStockFranchisee = $db->prepare('INSERT INTO DISH (name, price, quantity, idFranchisee) VALUES(:name, :price, :quantity, :idFranchisee)');
                $insertStockFranchisee->execute(array(
                    'name' => $orderData['name'],
                    'price' => number_format(($orderData['price'] + (($orderData['price'] * 20) / 100)), 2),
                    'quantity' => $orderData['quantity'],
                    'idFranchisee' => $_SESSION['id']
                ));
            } else if($orderData['dish'] == FALSE){
                $insertStockFranchisee = $db->prepare('INSERT INTO INGREDIENT (name, quantity, idFranchisee) VALUES(:name, :quantity, :idFranchisee)');
                $insertStockFranchisee->execute(array(
                    'name' => $orderData['name'],
                    'quantity' => $orderData['quantity'],
                    'idFranchisee' => $_SESSION['id']
                ));
            }

            $reqQuantityProduct = $db->query('SELECT quantity FROM BELONGIN WHERE idItem = ' .$orderData["id"]);
            $quantityData = $reqQuantityProduct->fetch();

            $totalPrice += $orderData['quantity'] * $orderData['price'];
            $totalPriceTva += $orderData['quantity'] * ($orderData['price'] + (($orderData['price'] * 10) / 100));
            $reqUpdateStock = $db->prepare('UPDATE BELONGIN SET quantity = :quantity WHERE idItem = :productId');
            $reqUpdateStock->execute(array(
                'quantity' => $quantityData['quantity'] - $orderData['quantity'],
                'productId' => $orderData['id']
            ));
        }

        $reqUpdateDatePayment = $db->prepare('UPDATE PURCHASE SET date = NOW(), price = :price WHERE idFranchisee = :currentId AND date IS NULL');
        $reqUpdateDatePayment->execute(array(
            'price' => number_format($totalPriceTva, 2),
            'currentId' => $_SESSION['id']
        ));

        $totalPrice = $totalPrice * 100;

        return $totalPrice;
    } else if($reqOrderMenu->rowCount() > 0 || $reqOrderDish->rowCount() > 0) {
        $totalPriceTva = 0;
        $totalPrice = 0;

        if($reqOrderDish->rowCount() > 0) {
            while($orderDishData = $reqOrderDish->fetch()) {

                $reqDish = $db->prepare('SELECT * FROM DISH WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqDish->execute(array(
                    'name' => $orderDishData['name'],
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $dishData = $reqDish->fetch();


                $updateStockFranchisee = $db->prepare('UPDATE DISH SET quantity = :quantity WHERE name = :name AND idFranchisee = :idFranchisee');
                $updateStockFranchisee->execute(array(
                    'quantity' => $dishData['quantity'] - $orderDishData['quantity'],
                    'name' => $orderDishData['name'],
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));

                $totalPrice += $orderDishData['quantity'] * $orderDishData['price'];
                $totalPriceTva += $orderDishData['quantity'] * ($orderDishData['price'] + (($orderDishData['price'] * 10) / 100));
            }
        }

        if($reqOrderMenu->rowCount() > 0) {
            while($orderMenuData = $reqOrderMenu->fetch()) {

                $reqMenu = $db->prepare('SELECT * FROM MENU WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqMenu->execute(array(
                    'name' => $orderMenuData['name'],
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $menuData = $reqMenu->fetch();

                $reqContainsDishMenu = $db->prepare('SELECT * FROM CONTAINSDISHMENU WHERE idMenu = :idMenu');
                $reqContainsDishMenu->execute(array(
                    'idMenu' => $menuData['id']
                ));

                while($containsDishMenuData = $reqContainsDishMenu->fetch()) {
                    $reqDish = $db->prepare('SELECT * FROM DISH WHERE id = :id');
                    $reqDish->execute(array(
                        'id' => $containsDishMenuData['idDish']
                    ));
                    $dishData = $reqDish->fetch();

                    $updateStockFranchisee = $db->prepare('UPDATE DISH SET quantity = :quantity WHERE id = :id');
                    $updateStockFranchisee->execute(array(
                        'quantity' => $dishData['quantity'] - ($orderMenuData['quantity'] * $containsDishMenuData['quantity']),
                        'id' => $containsDishMenuData['idDish']
                    ));
                }

                $totalPrice += $orderMenuData['quantity'] * $orderMenuData['price'];
                $totalPriceTva += $orderMenuData['quantity'] * ($orderMenuData['price'] + (($orderMenuData['price'] * 10) / 100));
            }
        }

        $insertDatePaymentFranchisee = $db->prepare('INSERT INTO SALE (price, idFranchisee) VALUES(:price, :idFranchisee)');
        $insertDatePaymentFranchisee->execute(array(
            'price' => number_format($totalPrice, 2),
            'idFranchisee' => $_SESSION['idFranchisee']
        ));

        $reqSaleFranchisee = $db->query('SELECT * FROM SALE WHERE idFranchisee = ' . $_SESSION['idFranchisee'] . ' AND date IS NULL');
        $saleFranchiseeData = $reqSaleFranchisee->fetch();

        $reqOrderDishBis = $db->prepare('SELECT idPurchaseClient, idDishClient, bill_numberClient, date, DISHCLIENT.price, CONTAINSDISHSALECLIENT.quantity, DISHCLIENT.idClient, name  FROM CONTAINSDISHSALECLIENT, PURCHASECLIENT, DISHCLIENT WHERE date IS NULL AND CONTAINSDISHSALECLIENT.idDishClient = DISHCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderDishBis->execute(array(
            'currentId' => $_SESSION['id']
        ));

        $reqOrderMenuBis = $db->prepare('SELECT idPurchaseClient, idMenuClient, bill_numberClient, date, MENUCLIENT.price, CONTAINSMENUSALECLIENT.quantity, MENUCLIENT.idClient, name  FROM CONTAINSMENUSALECLIENT, PURCHASECLIENT, MENUCLIENT WHERE date IS NULL AND CONTAINSMENUSALECLIENT.idMenuClient = MENUCLIENT.id AND PURCHASECLIENT.idClient = :currentId AND verify is NULL');
        $reqOrderMenuBis->execute(array(
            'currentId' => $_SESSION['id']
        ));

        if($reqOrderDishBis->rowCount() > 0) {
            while($orderDishBisData = $reqOrderDishBis->fetch()) {

                $reqDish = $db->prepare('SELECT * FROM DISH WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqDish->execute(array(
                    'name' => $orderDishBisData['name'],
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $dishData = $reqDish->fetch();

                $insertDatePaymentFranchisee = $db->prepare('INSERT INTO CONTAINSDISHSALE (idDish, idSale, quantity) VALUES(:idDish, :idSale, :quantity)');
                $insertDatePaymentFranchisee->execute(array(
                    'idDish' => $dishData['id'],
                    'idSale' => $saleFranchiseeData['id'],
                    'quantity' => $orderDishBisData['quantity']
                ));
            }
            $updateDatePaymentClient = $db->prepare('UPDATE SALE SET date = NOW() WHERE id = :id AND date IS NULL');
            $updateDatePaymentClient->execute(array(
                'id' => $saleFranchiseeData['id']
            ));

        }

        if($reqOrderMenuBis->rowCount() > 0) {
            while($orderMenuBisData = $reqOrderMenuBis->fetch()) {
                $reqMenu = $db->prepare('SELECT * FROM MENU WHERE name = :name AND idFranchisee = :idFranchisee');
                $reqMenu->execute(array(
                    'name' => $orderMenuBisData['name'],
                    'idFranchisee' => $_SESSION['idFranchisee']
                ));
                $menuData = $reqMenu->fetch();

                $insertDatePaymentFranchisee = $db->prepare('INSERT INTO CONTAINSMENUSALE (idMenu, idSale, quantity) VALUES(:idMenu, :idSale, :quantity)');
                $insertDatePaymentFranchisee->execute(array(
                    'idMenu' => $menuData['id'],
                    'idSale' => $saleFranchiseeData['id'],
                    'quantity' => $orderMenuBisData['quantity']
                ));
            }
            $updateDatePaymentClient = $db->prepare('UPDATE SALE SET date = NOW() WHERE id = :id AND date IS NULL');
            $updateDatePaymentClient->execute(array(
                'id' => $saleFranchiseeData['id']
            ));

        }

        $reqVerifyMenu = $db->query('SELECT * FROM MENUCLIENT WHERE verify IS NULL');
        if($reqVerifyMenu->rowCount() > 0) {
            while ($verifyMenuData = $reqVerifyMenu->fetch()) {
                $reqUpdateDateDishPayment = $db->prepare('UPDATE MENUCLIENT SET verify = 1 WHERE id = :id AND verify IS NULL');
                $reqUpdateDateDishPayment->execute(array(
                    'id' => $verifyMenuData['id']
                ));
            }
        }

        $reqVerifyDish = $db->query('SELECT * FROM DISHCLIENT WHERE verify IS NULL');
        if($reqVerifyDish->rowCount() > 0) {
            while ($verifyDishData = $reqVerifyDish->fetch()) {
                $reqUpdateDateDishPayment = $db->prepare('UPDATE DISHCLIENT SET verify = 1 WHERE id = :id AND verify IS NULL');
                $reqUpdateDateDishPayment->execute(array(
                    'id' => $verifyDishData['id']
                ));
            }
        }

        $reqVerifyIngredient = $db->query('SELECT * FROM INGREDIENTCLIENT WHERE verify IS NULL');
        if($reqVerifyIngredient->rowCount() > 0) {
            while ($verifyIngredientData = $reqVerifyIngredient->fetch()) {
                $reqUpdateDateIngredientPayment = $db->prepare('UPDATE INGREDIENTCLIENT SET verify = 1 WHERE id = :id AND verify IS NULL');
                $reqUpdateDateIngredientPayment->execute(array(
                    'id' => $verifyIngredientData['id']
                ));
            }
        }


        $reqUpdateDatePayment = $db->prepare('UPDATE PURCHASECLIENT SET date = NOW(), price = :price WHERE idClient = :currentId AND date IS NULL');
        $reqUpdateDatePayment->execute(array(
            'price' => number_format($totalPriceTva, 2),
            'currentId' => $_SESSION['id']
        ));


        $totalPrice = $totalPrice * 100;

        return $totalPrice;
    } else {
        http_response_code(400);
    }
    return 0;
}

function generateResponse($intent/*, $logger*/)
{
  switch($intent->status) {
    case 'requires_action':
    case 'requires_source_action':
      // Card requires authentication
      return [
        'requiresAction'=> true,
        'paymentIntentId'=> $intent->id,
        'clientSecret'=> $intent->client_secret
      ];
    case 'requires_payment_method':
    case 'requires_source':
      // Card was not properly authenticated, suggest a new payment method
      return [
        error => 'Your card was denied, please provide a new payment method'
      ];
    case 'succeeded':
      // Payment is complete, authentication not required
      // To cancel the payment after capture you will need to issue a Refund (https://stripe.com/docs/api/refunds)
      //$logger->info('💰 Payment received!');
      return ['clientSecret' => $intent->client_secret];
  }
}

$app->get('/stripe-key', function (Request $request, Response $response, array $args) {

    $reqStripeKey = $db->prepare('SELECT * FROM FRANCHISEE id = :idFranchisee');
    $reqStripeKey->execute(array(
        'idFranchisee' => $_SESSION['idFranchisee']
    ));
    $stripeKeyData = $reqStripeKey->fetch();

    $pubKey = $stripeKeyData['stripeKey'];
    return $response->withJson(['publicKey' => $pubKey]);
});

$app->post('/pay', function(Request $request, Response $response) use ($app)  {
  $body = json_decode($request->getBody());

    $body->paymentIntentId = null;
  if($body->paymentIntentId == null) {
    $payment_intent_data = [
      'amount' => calculateOrderAmount($body->items),
      'currency' => $body->currency,
      'payment_method' => $body->paymentMethodId,
      'confirmation_method' => 'manual',
      'confirm' => true
    ];
      $customer = \Stripe\Customer::create();
      $payment_intent_data['customer'] = $customer->id;

    // Create new PaymentIntent
    $intent = \Stripe\PaymentIntent::create($payment_intent_data);
  } else {
    // Confirm the PaymentIntent to collect the money
    $intent = \Stripe\PaymentIntent::retrieve($body->paymentIntentId);
    $intent->confirm();
  }

  $responseBody = generateResponse($intent/*, $logger*/);
  return $response->withJson($responseBody);

});

$app->run();
