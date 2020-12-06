<?php

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\CardException;

require_once './config.php';
require_once './vendor/autoload.php';
Stripe::setApiKey(STRIPE_SECRET_KEY);
if (isset($_POST['stripeToken'])) {
    try {
        $status = PaymentIntent::create([
            'amount'                   => ceil($_POST['price'] * 100),
            'currency'                 => 'usd',
            'payment_method'           => $_POST['stripeToken'],
            'error_on_requires_action' => true,
            'confirm'                  => true,
        ]);
        if ($status->status == 'succeeded') {
            header('location: ./index.php?msg=Payment Successfull&id=' . $status->id);
        }
    } catch (CardException $e) {
        echo 'Error code is:' . $e->getError()->code;
    }
}
