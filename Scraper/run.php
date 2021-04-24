<?php

require_once(__DIR__ . '/vendor/autoload.php');

use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitczh\SubEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubGiftEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubMysteryGiftEvent;
use GhostZero\Tmi\Events\Twitch\ResubEvent;
use GhostZero\Tmi\Events\Twitch\SubGiftEvent;
use GhostZero\Tmi\Events\Twitch\SubMysteryGiftEvent;
include('requestFactory.php');
$streamers = RequestFactory::create('xqcow', null, 'getStreamers');

for ($i = 0; $i <= count($streamers) - 1; ++$i) {

    $pid = pcntl_fork();
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // we are the parent
        // pcntl_wait($status); //Protect against Zombie children
    } else {
        $GLOBALS['streamer'] = ($streamers[$i]->streamer);
        cli_set_process_title($GLOBALS['streamer'] . 'run.php');

        $request = RequestFactory::create($GLOBALS['streamer'], '1', 'status');
        $request = RequestFactory::create($GLOBALS['streamer'], null, 'checkTwitchOnline');
        $data = $request->decode();
        
        if (empty($data['data'])) {
            $request = RequestFactory::create($GLOBALS['streamer'], '0');
            die();
        }

        $request = RequestFactory::create($GLOBALS['streamer'], '1', 'online');

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
        function giftedRequest($event, $type): void
        {

            $fields = ['recipient' => $event->recipient, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => $event->user, 'streamer' => $GLOBALS['streamer']];
            RequestFactory::create($GLOBALS['streamer'], null,'sub',$fields);
        }

        /**
         * @param SubEvent $event
         */
        function subbedRequest($event, $type): void
        {
            $fields = ['recipient' => $event->user, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => NULL, 'streamer' => $GLOBALS['streamer']];
            RequestFactory::create($GLOBALS['streamer'], null,'sub',$fields);
        }

        $client->on(SubEvent::class, function (SubEvent $event) {
            subbedRequest($event, 'SubEvent');
        });
        $client->on(AnonSubGiftEvent::class, function (AnonSubGiftEvent $event) {
            print_r($event);
        });

        $client->on(AnonSubMysteryGiftEvent::class, function (AnonSubMysteryGiftEvent $event) {
            print_r($event);
        });
        $client->on(ResubEvent::class, function (ResubEvent $event) {
            subbedRequest($event, 'ResubEvent');
        });
        $client->on(SubGiftEvent::class, function (SubGiftEvent $event) {
            giftedRequest($event, 'SubGiftEvent');
        });
        $client->on(SubMysteryGiftEvent::class, function (SubMysteryGiftEvent $event) {
            subbedRequest($event, 'SubMysteryGiftEvent');
        });
        $client->connect();
    }
}
