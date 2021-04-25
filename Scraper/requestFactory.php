<?php

require_once(__DIR__ . '/vendor/autoload.php');

class Request
{
    private $requestUrl;
    private $requestFields;
    private $requestType;
    private $ch;

    public function __construct($request)
    {
        $this->requestType = $request['type'];
        $this->requestUrl = $request['uri'];
        $this->requestFields = $request;
    }

    public function start()
    {
        $this->ch = curl_init();
        $this->setRequestType();
        $this->setHeader();
        return new RequestResult(curl_exec($this->ch));
    }

    public function setRequestType()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl);

        if ($this->requestType == 'GET' || $this->requestType == 'get') {
            curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        } else {
            curl_setopt($this->ch, CURLOPT_POST, true);
        }

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }
    public function setHeader()
    {
        if ($this->requestType == 'get' || $this->requestType == 'GET') {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestFields);
        } else {
            $fields_string = http_build_query($this->requestFields);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);
        }
    }
}

class RequestResult
{
    public $result;
    public function __construct($result)
    {
        $this->result = $result;
    }

    public function decode()
    {
        return json_decode($this->result, true);
    }

    public function result()
    {
        return $this->result;
    }
}

class RequestFactory
{
    public const API = ['online', 'getStreamers', 'status', 'sub'];
    public $request;
    public $parameters;

    public function create(array $request)
    {
        $this->request = $request;
        $this->setParameters();
        $this->getKey();
        $this->setUri();
        return new Request($this->parameters);
    }

    public function setParameters()
    {
        //Gets the first array
        $this->parameters = reset($this->request);
    }
    public function getKey()
    {
        //Gets the first value of the array
        $this->request = key($this->request);
    }

    public function setUri()
    {
        //If the request is from the API add the base URL
        //Otherwise, is twitch API
        if (in_array($this->request, self::API))
            $this->parameters['uri'] = 'http://localhost:8000/api/streamers/' . $this->parameters['uri'];
        else if ($this->request == 'twitchOnline')
            $this->parameters['uri'] = 'https://api.twitch.tv/helix/streams/?user_login=nmplol';
    }
}
$changeStatus = (new RequestFactory)->create(['twitchOnline' => ['type' => 'GET', 'Authorization: XXX', 'Client-ID: XXXX']])->start();
dump($changeStatus->result());
