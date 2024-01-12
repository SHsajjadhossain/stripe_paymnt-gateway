<?php

namespace App\Http\Controllers;

use Stripe;
use Session;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function stripe(){
        return view('stripe');
    }

    public function stripePost(Request $request)
    {

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => 100 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "This payment is tested purpose"
        ]);

        Session::flash('success', 'Payment successful!');

        return back();

    }


// ***********NEW WAY ***********
    public function checkout()
    {
        return view('checkout');
    }

    public function session(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $productname = $request->get('productname');
        $totalprice = $request->get('total');
        $two0 = "00";
        $total = "$totalprice$two0";

        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'USD',
                        'product_data' => [
                            "name" => $productname,
                        ],
                        'unit_amount'  => $total,
                    ],
                    'quantity'   => 1,
                ],

            ],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        return "Thanks for you order You have just completed your payment. The seeler will reach out to you as soon as possible";
    }

}
