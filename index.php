<?php

// TO DO Как работать с автозагрузкой классов
require __DIR__ . '/vendor/autoload.php';

use App\Server\Server;

header('Content-Type: application/json');
$server = new Server();
$server->handleRequest();