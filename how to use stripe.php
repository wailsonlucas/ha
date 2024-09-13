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

$stripeDetails = array(
        "secretKey" => "sk_test_51Kv8VaLKgzTG7qHjyArhpNFp1apmM4lT3RqjiH5mEP7HdYHaOA0n3KlGJrBji3hZbb91QxHgFDNvK88ss3Pr2G7600TlBqsVMe",
        "publishableKey" => "pk_test_51Kv8VaLKgzTG7qHjpwTfz0ziNSKO0ax9eHDGM8YTBJaAJ1LldKbmsEX0i6zdEbYzBGHxb2D2cZRANlZNHuw8IYWV00fJL5dAXX"
    );



s = sk_test_51KXocOHDn8oDvpIr2LwF7SnMLYtMey2ZXuECk6e7SCvC2aQNiYLhd78w0AbcIixyCK0nVF2XsDzJc4gqBppAMnlX00NoRdMEJI
p = pk_test_51KXocOHDn8oDvpIrrRgJxEgvAPNF9UL5wzYcMaiEBpczRGGwrdqextGA3j2BULhCedDd6hY55chO68hgg4LowzfO0069oTFhms