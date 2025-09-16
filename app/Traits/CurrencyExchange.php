<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait CurrencyExchange
{
    public function currencyExchangeHelper($amount): float
    {

        $exhangeRate        = config('currency_exchange_rate') ?? 1;
        $convertedAmount    = $amount * $exhangeRate;

        return $convertedAmount;
    }
    public function currencyExchange($amount, $from, $to): float
    {
        $url                = "https://www.google.com/search?q=$from+to+$to";
        $get                = file_get_contents($url);
        $data               = preg_split('/\D\s(.*?)\s=\s/',$get);
        $exhangeRate        = (float) substr($data[1],0,12);
        $convertedAmount    = $amount * $exhangeRate;

        return $convertedAmount;
    }

    //get exchange rate from google
    public function getExchangeRate($from, $to): float
    {
        $url                = "https://www.google.com/search?q=$from+to+$to";
        $get                = file_get_contents($url);
        $data               = preg_split('/\D\s(.*?)\s=\s/',$get);
        $exhangeRate        = (float) substr($data[1],0,12);

        return $exhangeRate != 0 ? $exhangeRate : 1;
    }

//    protected $apiUrl = 'https://openexchangerates.org/api/latest.json';
//    protected $apiKey = '5b3c9c23c8f842d89b0d5131ed147406';
//
//    public function getConversionRate($baseCurrency, $targetCurrency)
//    {
//        $client = new Client();
//        $response = $client->request('GET', $this->apiUrl, [
//            'query' => [
//                'app_id'        => $this->apiKey,
//                'base'          => $baseCurrency,
//                'symbols'       => $targetCurrency
//            ]
//        ]);
//
//        $data = json_decode($response->getBody(), true);
//        $conversionRate = $data['rates'][$targetCurrency];
//
//        return $conversionRate;
//    }
}
