<?php
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

// Instantiate the logger as a dependency
/*$container = $app->getContainer();
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new \Monolog\Logger($settings['name']);
  $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
  $logger->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . '/logs/app.log', \Monolog\Logger::DEBUG));
  return $logger;
};*/

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
        'currentId' => /*$_SESSION['id']*/10
    ));

    if($reqOrder->rowCount() > 0) {
        $totalPriceTva = 0;
        $totalPrice = 0;
        while($orderData = $reqOrder->fetch()) {
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
            'currentId' => /*$_SESSION['id']*/10
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
    $pubKey = 'pk_test_51GqKO7I442Tn6Kr1TuMvJCl2iERVkU8IX9NI063wMenUJgybM2PZXmEHWMlPRJcqIJWDLD0R0v7ebY3Lx4sHFEnK00fDeiYR2H';
    return $response->withJson(['publicKey' => $pubKey]);
});

$app->post('/pay', function(Request $request, Response $response) use ($app)  {
  //$logger = $this->get('logger');
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
