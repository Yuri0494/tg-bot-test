<?php

use GuzzleHttp\ClientInterface;

class TelegramApi {

private ClientInterface $client;

public function __construct($client) 
{
    $this->client = $client;
}

public function sendMessage($chatId, $text = null, $params = []) 
{
    $body = array_merge([
        'chat_id' => $chatId,
        'text' => $text,
    ], $params);

    return $this->client->request('GET', 'sendMessage', ['query' => $body])->getBody();
}

public function getMe() 
{
    return $this->client->request('GET', 'getMe')->getBody();
}

public function getUpdates() 
{
    return $this->client->request('GET', 'getUpdates')->getBody();
}

private function writeLogFile($string, $clear = false){
    $log_file_name = __DIR__."/message.txt";
    $now = date("Y-m-d H:i:s");
    if($clear == false) {
        file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n", FILE_APPEND);
    } else {
        file_put_contents($log_file_name, $now." ".print_r($string, true)."\r\n");
    }
}
}