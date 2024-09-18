<?php

require 'vendor/autoload.php';
require './TelegramApiInterface.php';

use GuzzleHttp\ClientInterface;

class TelegramBot {
    public $api;
    public FileSystemBotDb $db;

    public function __construct(FileSystemBotDb $db, $clientApi)
    {
        $this->api = $clientApi;
        $this->db = $db;
    }
}