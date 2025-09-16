<?php

namespace App\Services\Payments;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;


/**
 * PaypalService
 */
class PaypalService
{
    protected $baseUrl;
    protected $clientId;
    protected $secret;
    protected $currencyCode;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->baseUrl = config()->get('paypal.baseUrl');
        $this->clientId = config()->get('paypal.clientId');
        $this->secret = config()->get('paypal.secret');
        $this->currencyCode = config()->get('paypal.currency_code');
    }

    /**
     * getTransaction
     *
     * @param  mixed $order_id
     * @return void
     */
    public function getTransaction($order_id)
    {
        try {
            $client = $this->getHttpClient();

            $response = $client->request(
                'GET',
                $this->baseUrl . 'checkout/orders/' . $order_id,
                [
                    'headers' => [
                        'Authorization' => $this->getAccessToken()
                    ]
                ]
            );

            $data = json_decode($response->getBody(), true);

            return $data['gross_total_amount']['value'];
        } catch (BadResponseException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }
    }


    /**
     * getAccessToken
     *
     * @return void
     */
    public function getAccessToken()
    {
        try {
            $client = $this->getHttpClient();

            $response = $client->request(
                'POST',
                $this->baseUrl . 'oauth2/token',
                [
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'body' => 'grant_type=client_credentials',

                    'auth' => [$this->clientId, $this->secret, 'basic']
                ]
            );

            $data = json_decode($response->getBody(), true);

            return 'Bearer ' . $data['access_token'];
        } catch (BadResponseException $ex) {
            throw $ex;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * getHttpClient
     *
     * @return void
     */
    protected function getHttpClient()
    {
        return new Client([
            'headers' => [
                'Accept-Language' => 'en_US',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }
}
