<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/start.php';

use App\Services\DRB;

$drb = new DRB();

$mqtt = new \PhpMqtt\Client\MqttClient(
    $_ENV['BROKER_HOST'],
    $_ENV['BROKER_PORT'],
    $_ENV['BROKER_CLIENT_ID']
);

$mqtt->connect();

$mqtt->subscribe('ALink/#', function ($topic, $message) use ($drb, $mqtt) {

    echo $topic . " message:" . $message . "\n";
    $decodedMessage = json_decode($message);

    if ($decodedMessage->from === "client") {
        $topics = explode("/", $topic);
        $machineType = $topics[1];
        $machineId = $topics[2];

        $response = "";
        switch ($machineType) {
            case "DRB":
                $response = $drb->receiveRequest($machineId, $decodedMessage);
                break;
        }

        if (!empty($response)) {
            $mqtt->publish($topic, json_encode($response));
        }
    }
}, 0);

$mqtt->loop(true);
$mqtt->disconnect();
