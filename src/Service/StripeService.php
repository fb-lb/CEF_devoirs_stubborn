<?php

namespace App\Service;

use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{

    public function __construct()
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
    }

    public function createPaymentIntent(int $amount, string $currency = 'eur'): PaymentIntent
    {
        return PaymentIntent::create([
            'amount' => $amount, // amount in cents
            'currency' => $currency,
            'automatic_payment_methods' => ['enabled' => true],
        ]);
    }
}

?>