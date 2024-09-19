<?php

namespace App\Api\TelegramApi;

use Exception;
use App\HttpApiAdapters\HttpAdapterInterface;

class TelegramApi {
    private HttpAdapterInterface $client;

    public function __construct(HttpAdapterInterface $client)
    {
        $this->client = $client;
    }

    public function getMe()
    {
        try {
            return json_decode($this->client->sendGetRequest('getMe'), true);
        } catch (Exception $e) {
            return [];
        }
    }

    public function sendMessage($chatId, $text = null, $params = []) 
    {
        $body = array_merge([
            'chat_id' => $chatId,
            'text' => $text,
        ], $params);

        try {
            $this->client->sendGetRequest('sendMessage', $body);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
