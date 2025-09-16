<?php

namespace App\Services\Payments;

use App\Traits\CurrencyExchange;

/**
 * StripeService
 */
class StripeService
{
    use CurrencyExchange;

    protected $secret;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->secret =  config('stripe.secret_key');
    }

    /**
     * pay
     *
     * @param  mixed $invoice
     * @return void
     */
    public function pay($invoice)
    {
        try {
            $stripe = new \Stripe\StripeClient($this->secret);

            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => config('stripe.currency_code'),
                        'product_data' => [
                            'name' => 'Invoice-' . $invoice->id,
                        ],
                        'unit_amount' => (int)(convertAmountWithCurrencyConverter($invoice->last_paid)* 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success', ['invoice_id' => $invoice->id]),
                'cancel_url' => route('stripe.cancel'),
            ]);

            return $session->url;
        }catch (\Exception $e){
            dd($e);
            return false;
        }
    }
}
