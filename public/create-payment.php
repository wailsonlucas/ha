<?php

require_once '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

\Stripe\Stripe::setApiKey("sk_test_51KXocOHDn8oDvpIr2LwF7SnMLYtMey2ZXuECk6e7SCvC2aQNiYLhd78w0AbcIixyCK0nVF2XsDzJc4gqBppAMnlX00NoRdMEJI");

header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/ha/public';

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => [[
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'price' => 'price_1PxF0sHDn8oDvpIrwdVVGZ0E',
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/success.html',
  'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
?>