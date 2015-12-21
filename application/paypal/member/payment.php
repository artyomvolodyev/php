<?php

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PayPalConnectionException;


require '../src/start.php';

$payer = new Payer();
$details = new Details();
$amount = new Amount();
$transaction = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();

$payer->setPaymentMethod('paypal');


$amount->setCurrency('GBP')
        ->setTotal('22.00')
        ->setDetails($details);

$transaction->setAmount($amount)
            ->setDescription('Membership');

$payment->setIntent('sale')
        ->setPayer($payer)
        ->setTransactions([$transaction]);

$redirectUrls->setReturnUrl('http://teeshirt.esy.es/paypal/paypal/pay.php?approved=true')
             ->setCancelUrl('http://teeshirt.esy.es/paypal/paypal/pay.php?approved=false');

$payment->setRedirectUrls($redirectUrls);

try{
    $payment->create($api);
    $hash = md5($payment->getId());
    $_SESSION['paypal_hash'] = $hash;

//    $store = $db->prepare("
//        INSERT INTO transactions_paypal(user_id, payment_id, hash, complete)
//        VALUES (:user_id, :payment_id, :hash, 0)
//    ");
//
//    $store->execute([
//        'user_id' => $_SESSION['user_id'],
//        'payment_id' => $payment->getId(),
//        'hash' => $hash
//    ]);
}
catch (PayPalConnectionException $e){
    header('Location: ../paypal/error.php');

}

foreach($payment->getLinks() as $link){
    if($link->getRel() == 'approval_url'){
        $redirectUrls = $link->getHref();
    }
}

header('Location:' .$redirectUrls);
