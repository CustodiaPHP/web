<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class FirebaseService {

    private Client $client;
    private string $key;

    public function __construct()
    {
        $this->key = $_ENV['FIREBASE_KEY'];
        $this->client = new Client([
            'timeout' => 15.0
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function sendNotification(string $service, string $status) : mixed
    {
        $notification = [
            'title' =>"$service status update",
            'body' => "$service is $status",
            'sound' => 'default',
            'badge' => '1'
        ];

        $body = [
            'to' => "/topic/$service",
            'notification' => $notification,
            'priority' => 'high'
        ];

        $response = $this->client->post('https://fcm.googleapis.com/fcm/send', [
            'headers' => [
                'Authorization' => 'key='.$this->key,
                'Content-Type' => 'application/json'
            ],
            'json' => $body
        ]);

        if($response->getStatusCode() == 200){
            return $response->getBody()->getContents();
        }

        return -1;
    }

}