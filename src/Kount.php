<?php

namespace Kayue\Kount;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;

class Kount
{
    private $apiKey;
    private $client = null;

    const ORDER_STATUS_APPROVE = 'A';
    const ORDER_STATUS_ESCALATE = 'E';
    const ORDER_STATUS_REVIEW = 'R';
    const ORDER_STATUS_DECLINE = 'D';

    public function __construct($apiKey, ClientInterface $client = null)
    {
        $this->apiKey = $apiKey;

        if ($client) {
            $this->setClient($client);
        }
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    private function getClient()
    {
        if (!$this->client) {
            $this->client = new Client([
                'base_uri' => 'https://api.kount.net/rpc/v1/',
                'headers' => [
                    'X-Kount-Api-Key' => $this->getApiKey(),
                    'Accept' => 'application/json'
                ]
            ]);
        }

        return $this->client;
    }

    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function request($method, $uri = null, array $options = [])
    {
        try {
            return new Response($this->getClient()->request($method, $uri, $options));
        } catch (ClientException $e) {
            return new Response($e->getResponse());
        }
    }

    public function getEmail($email)
    {
        return $this->request('GET', 'vip/email.json', [
            'query' => [
                'email' => $email
            ]
        ]);
    }

    public function updateOrderStatus($transactionId, $status, $note = null, $agent = null)
    {
        $formParams = ['status' => [$transactionId => $status]];

        if ($note) {
            $formParams['note'] = [$transactionId => $note];
        }

        if ($agent) {
            $formParams['agent'] = [$transactionId => $agent];
        }

        return $this->request('POST', 'orders/status.json', [
            'form_params' => $formParams
        ]);
    }
}
