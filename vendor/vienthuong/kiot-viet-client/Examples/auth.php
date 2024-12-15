<?php

require_once('vendor/autoload.php');

use VienThuong\KiotVietClient;

$client = new KiotVietClient\Client('<YOUR CLIENT ID>', '<YOUR CLIENT SECRET>');

// Fetch access token
$token = $client->fetchAccessToken();

var_dump($token);

$client->setExpiresAt(0);

// Set token callback
$client->setTokenCallback(function ($token) {
    var_dump("Token is Refreshed");
    var_dump($token);
});
