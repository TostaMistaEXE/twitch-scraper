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
$streamers = CreateRequest::getAllStreamers();
$streamers = $streamers->decode();
class CreateRequest
{
    public static function updateStatus($streamer, $status)
    {
        return (new RequestFactory)->create(['status' => ['type' => 'POST', 'uri' => 'changeStatus', 'streamer' => $streamer, 'run' => $status]])->start();
    }
    public static function updateOnline($streamer, $status)
    {
        return (new RequestFactory)->create(['status' => ['type' => 'POST', 'uri' => 'changeOnline', 'streamer' => $streamer, 'is_online' => $status]])->start();
    }
    public static function getAllStreamers()
    {
        return (new RequestFactory)->create(['getStreamers' => ['type' => 'GET', 'uri' => 'getAll']])->start();
    }
    public static function checkIfTwitchOnline($streamer)
    {
        $request = (new RequestFactory)->create(['twitchOnline' => ['type' => 'GET', 'streamer' => $streamer, 'Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb']])->start();
        return $request->decode();
    }
    public static function createSub($streamer, $fields)
    {
        return (new RequestFactory)->create(['sub' => ['type' => 'POST', 'uri' => 'sub', $fields]])->start();
    }
}
class TwitchIRC
{
    public static function create()
    {

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

            $fields = ['recipient' => $event->recipient, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => $event->user];
            CreateRequest::createSub($GLOBALS['streamer'], $fields);
        }

        /**
         * @param SubEvent $event
         */
        function subbedRequest($event, $type): void
        {
            $fields = ['recipient' => $event->user, 'plan' => $event->plan->plan, 'type' => $type, 'gifter' => NULL, 'streamer' => $GLOBALS['streamer']];
            CreateRequest::createSub($GLOBALS['streamer'], $event);
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
for ($i = 0; $i <= count($streamers) - 1; ++$i) {
    $pid = pcntl_fork();
    if ($pid == -1) {
        die('could not fork');
    } else if ($pid) {
        // we are the parent
        // pcntl_wait($status); //Protect against Zombie children
    } else {
        $GLOBALS['streamer'] = ($streamers[$i]['streamer']);
        cli_set_process_title($GLOBALS['streamer'] . 'run.php');

        if (!empty(CreateRequest::checkIfTwitchOnline($GLOBALS['streamer'])['data'])) {
            CreateRequest::updateStatus($GLOBALS['streamer'], '1');
            CreateRequest::updateOnline($GLOBALS['streamer'], '1');
        } else {
            CreateRequest::updateStatus($GLOBALS['streamer'], '0');
            CreateRequest::updateOnline($GLOBALS['streamer'], '0');
            die();
        }


        TwitchIRC::create();
    }
}
