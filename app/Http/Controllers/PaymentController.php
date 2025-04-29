<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function show()
    {
        return view('payment');
    }

    public function createIntent(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => 1999,       // 19,99 EUR en céntimos
            'currency' => 'eur',
        ]);

        return response()->json([
            'clientSecret' => $intent->client_secret,
        ]);
    }
}
