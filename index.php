<?php


// TO DO Как работать с автозагрузкой классов
require 'vendor/autoload.php';
require './Server.php';

use GuzzleHttp\Client;
use TelegramApi;
use Server;

header('Content-Type: application/json');
$server = new Server();
$server->handleRequest();