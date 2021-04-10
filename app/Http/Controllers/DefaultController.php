<?php
# app/Command/Twitch/DefaultController.php

namespace App\Http\Controllers;

use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\SubEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubGiftEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubMysteryGiftEvent;
use GhostZero\Tmi\Events\Twitch\ResubEvent;
use GhostZero\Tmi\Events\Twitch\SubGiftEvent;
use GhostZero\Tmi\Events\Twitch\SubMysteryGiftEvent;

class DefaultController extends Controller
{
    public function handle()
    {


        $client = new Client(new ClientOptions([
            'options' => ['debug' => false],
            'connection' => [
                'secure' => true,
                'reconnect' => true,
                'rejoin' => true,
            ],
            'channels' => ['ludwig']
        ]));

        $client->on(SubEvent::class, function (SubEvent $event) {
            print_r($event->user);
        });
        $client->on(AnonSubGiftEvent::class, function (AnonSubGiftEvent $event) {
            //print_r($event);
        });

        $client->on(AnonSubMysteryGiftEvent::class, function (AnonSubMysteryGiftEvent $event) {
            //print_r($event);
        });

        $client->on(ResubEvent::class, function (ResubEvent $event) {
            //print_r($event);
        });
        $client->on(SubGiftEvent::class, function (SubGiftEvent $event) {
            //print_r($event);
        });
        $client->on(SubMysteryGiftEvent::class, function (SubMysteryGiftEvent $event) {
            //print_r($event);
        });
        $client->connect();
    }
}
