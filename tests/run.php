<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use GhostZero\Tmi\Client;
use GhostZero\Tmi\ClientOptions;
use GhostZero\Tmi\Events\Twitch\SubEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubGiftEvent;
use GhostZero\Tmi\Events\Twitch\AnonSubMysteryGiftEvent;
use GhostZero\Tmi\Events\Twitch\ResubEvent;
use GhostZero\Tmi\Events\Twitch\SubGiftEvent;
use GhostZero\Tmi\Events\Twitch\SubMysteryGiftEvent;
include('getStreamers.php');

for ($i = 0; $i <= count($streamers)-1; ++$i) {

    $pid = pcntl_fork();
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // we are the parent
       // pcntl_wait($status); //Protect against Zombie children
    } else {
        $GLOBALS['streamer'] = ($streamers[$i]->streamer);
        function check()
        {
            $url = 'https://api.twitch.tv/helix/streams/?user_login=' . $GLOBALS['streamer'];
            $fields = array('Authorization: Bearer 2xzskw949bee02hli7jc8tpwwxe256', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb');
            $ch = curl_init();
            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $fields);

            //So that curl_exec returns the contents of the cURL; rather than echoing it
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);
            $object = json_decode($result, true);
            return empty($object['data']);
        }

        if (check())
            die();

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
            subbed($event, 'SubEvent');
        });
        $client->on(AnonSubGiftEvent::class, function (AnonSubGiftEvent $event) {
            print_r($event);
        });

        $client->on(AnonSubMysteryGiftEvent::class, function (AnonSubMysteryGiftEvent $event) {
            print_r($event);
        });
        $client->on(ResubEvent::class, function (ResubEvent $event) {
            subbed($event, 'ResubEvent');
        });
        $client->on(SubGiftEvent::class, function (SubGiftEvent $event) {
            gifted($event, 'SubGiftEvent');
        });
        $client->on(SubMysteryGiftEvent::class, function (SubMysteryGiftEvent $event) {
            subbed($event, 'SubMysteryGiftEvent');
        });
        $client->connect();
    }
}
