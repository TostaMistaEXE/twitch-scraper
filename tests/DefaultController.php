<?php
# app/Command/Twitch/DefaultController.php

namespace App\Command\Twitch;

require_once('C:\Users\nunom\Documents\GitHub\twitch-scraper\vendor\autoload.php');


use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\SubEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubGiftEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubMysteryGiftEvent;
use GhostZero\Tmi\Events\Twitch\ResubEvent;
use GhostZero\Tmi\Events\Twitch\SubGiftEvent;
use GhostZero\Tmi\Events\Twitch\SubMysteryGiftEvent;

$GLOBALS['streamer'] = 'ludwig';
$client = new Client(new ClientOptions([
    'options' => ['debug' => false],
    'connection' => [
        'secure' => true,
        'reconnect' => true,
        'rejoin' => true,
    ],
    'channels' => [$GLOBALS['streamer']]
]));

/**
 * @param SubGiftEvent $event
 */
function gifted($event, $type): void
{
    $url = 'http://localhost:8000/api/create/sub';
    $fields = ['recipient' => $event->recipient, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => $event->user, 'streamer' => $GLOBALS['streamer']];
    $fields_string = http_build_query($fields);
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //execute post
    $result = curl_exec($ch);
    dump($result);
}

/**
 * @param SubEvent $event
 */
function subbed($event, $type): void
{
    $url = 'http://localhost:8000/api/create/sub';
    $fields = ['recipient' => $event->user, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => NULL, 'streamer' => $GLOBALS['streamer']];
    $fields_string = http_build_query($fields);
    $ch = curl_init();
    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //execute post
    $result = curl_exec($ch);
    dump($result);
}

$client->on(SubEvent::class, function (SubEvent $event) {
    subbed($event,'SubEvent');
});
$client->on(AnonSubGiftEvent::class, function (AnonSubGiftEvent $event) {
    print_r($event);
});

$client->on(AnonSubMysteryGiftEvent::class, function (AnonSubMysteryGiftEvent $event) {
    print_r($event);
});
$client->on(ResubEvent::class, function (ResubEvent $event) {
    subbed($event,'ResubEvent');
});
$client->on(SubGiftEvent::class, function (SubGiftEvent $event) {
    gifted($event,'SubGiftEvent');
});
$client->on(SubMysteryGiftEvent::class, function (SubMysteryGiftEvent $event) {
    gifted($event,'SubMysteryGiftEvent');
});
$client->connect();
