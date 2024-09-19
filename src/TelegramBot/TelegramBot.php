<?php

namespace App\TelegramBot;

use App\Database\FileSystemBotDb;

class TelegramBot {
    public $api;
    public FileSystemBotDb $db;

    public function __construct(FileSystemBotDb $db, $clientApi)
    {
        $this->api = $clientApi;
        $this->db = $db;
    }
}