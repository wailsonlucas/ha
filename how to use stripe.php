<?php 



include "./config.php";

$token = $_POST["stripeToken"];

$charge = \Stripe\Charge::create([
    "amount" => str_replace(",", "", $amount) * 100,
    "currency" => 'usd',
    "description" => $desc,
    "source" => $token,
]);
if ($charge) {
    header("Location:success.php?amount=$amount");
}