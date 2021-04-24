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
    public function __construct($streamer, $status = null, $url = null)
    {
        $this->requestStreamer = $streamer;
        $this->requestStatus = $status;
        $this->requestUrl = $url;
        $this->start();
    }

    public function start()
    {
        $this->checkType();
    }

    public function checkType()
    {
        if ($this->requestUrl == 'online') {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeOnline';
            $this->requestFields = ['streamer' => $this->requestStreamer, 'is_online' => $this->requestStatus];
            $this->request();
            return;
        }
        if ($this->requestUrl == 'status') {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeStatus';
            $this->requestFields = ['streamer' => $this->requestStreamer, 'run' => $this->requestStatus];
            $this->request();
            return;
        }
        if ($this->requestUrl == 'checkTwitchOnline') {
            $this->requestUrl =  'https://api.twitch.tv/helix/streams/?user_login=' . $this->requestStreamer;
            $this->requestFields = array('Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb');
            $this->requestType = 'get';
            $this->request();
            return $this->decode();
        }
        if ($this->requestUrl == null) {
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeStatus';
            $this->requestFields = ['streamer' => $this->requestStreamer, 'run' => $this->requestStatus];
            $this->request();
            $this->requestUrl = 'http://localhost:8000/api/streamers/changeOnline';
            $this->requestFields = ['streamer' => $this->requestStreamer, 'is_online' => $this->requestStatus];
            $this->request();
            return;
        }
    }
    public function setRequestType()
    {
        $fields_string = http_build_query($this->requestFields);

        if ($this->requestType == 'get') {
            curl_setopt($this->ch, CURLOPT_HTTPGET, true);
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestFields);
        } else {
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);
        }
    }
    public function request()
    {
        $this->ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl);
        $this->setRequestType();
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $this->requestResult = curl_exec($this->ch);

        return $this->result();
    }
    public function decode()
    {
        return json_decode($this->requestResult, true);
    }
    public function result()
    {
       // dump($this->requestResult);
        return $this->requestResult;
    }
}
class RequestFactory
{
    public static function create($streamer, $status = null, $url = null)
    {
        return new Request($streamer, $status, $url);
    }
}
$changeStatus = RequestFactory::create('xqcow', '0');
