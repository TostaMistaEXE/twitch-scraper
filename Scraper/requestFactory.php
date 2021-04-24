<?php

require_once(__DIR__ . '/vendor/autoload.php');

class Request
{
    private $requestUrl;
    private $requestFields;
    private $requestResult;
    private $requestStreamer;
    private $requestStatus;
    private $requestType;
    private $ch;

    public function __construct($streamer = null, $status = null, $url = null, $customFields = null)
    {
        $this->requestStreamer = $streamer;
        $this->requestStatus = $status;
        $this->requestFields = $customFields;
        $this->requestUrl = $url;
        $this->start();
    }
    public function setFields($fields = null)
    {
        if ($fields != null)
            $this->requestFields = $fields;

    }
    public function start()
    {
        if ($this->requestUrl == 'online') {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeOnline';
            $this->setFields(['streamer' => $this->requestStreamer, 'is_online' => $this->requestStatus]);
            $this->request();
            return;
        }
        if ($this->requestUrl == 'status') {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeStatus';
            $this->setFields(['streamer' => $this->requestStreamer, 'run' => $this->requestStatus]);
            $this->request();
            return;
        }
        if ($this->requestUrl == 'sub') {
            $this->requestUrl = 'http://localhost:8000/api/create/sub';
            $this->setFields();
            $this->request();
            dump($this->requestFields);
            return;
        }
        if ($this->requestUrl == 'checkTwitchOnline') {
            $this->requestUrl =  'https://api.twitch.tv/helix/streams/?user_login=' . $this->requestStreamer;
            $this->requestType = 'get';
            $this->setFields(array('Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb'));
            $this->request();

            return $this->decode();
        }
        if ($this->requestUrl == 'getStreamers') {
            $this->requestUrl =  'http://localhost:8000/api/streamers/getAll';
            $this->requestType = 'get';
            $this->request();
            return $this->decode();
        }
        if ($this->requestUrl == null) {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeStatus';
            $this->setFields(['streamer' => $this->requestStreamer, 'run' => $this->requestStatus]);
            $this->request();

            $this->requestUrl = 'http://localhost:8000/api/streamers/changeOnline';
            $this->setFields(['streamer' => $this->requestStreamer, 'is_online' => $this->requestStatus]);
            $this->request();

            return;
        }
        return;
    }

    public function setRequestType()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl);

        if ($this->requestType == 'get') {
            curl_setopt($this->ch, CURLOPT_HTTPGET, true);
            $this->setHeader();
        } else {
            curl_setopt($this->ch, CURLOPT_POST, true);
            $this->setHeader();
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }
    public function setHeader()
    {
        if (!empty($this->requestFields)) {
            $fields_string = http_build_query($this->requestFields);

            if ($this->requestType == 'get') {
                curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestFields);
            } else {
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);
            }
        }
    }
    public function request()
    {
        $this->ch = curl_init();
        $this->setRequestType();
        $this->requestResult = curl_exec($this->ch);

        return $this->result();
    }
    public function decode()
    {
        return json_decode($this->requestResult, true);
    }
    public function result()
    {
        //dump($this->requestResult);
        return $this->requestResult;
    }
}
class RequestFactory
{
    public static function create($streamer = null, $status = null, $url = null, $customFields = null)
    {
        return new Request($streamer, $status, $url, $customFields);
    }
}
$changeStatus = RequestFactory::create(null, null, 'getStreamers');
