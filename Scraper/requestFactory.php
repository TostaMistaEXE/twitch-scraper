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
        $this->setHeader();
        $this->setRequestType();
        return new RequestResult(curl_exec($this->ch));
    }

    public function setRequestType()
    {
        curl_setopt($this->ch, CURLOPT_URL, $this->requestUrl);

        if ($this->requestType == 'GET' || $this->requestType == 'get') {
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

        $fields_string = http_build_query($this->requestFields);

        if ($this->requestType == 'get') {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->requestFields);
        } else {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);
        }
    }

}

class RequestResult{
    public $result;
    public function __construct($result)
    {
        $this->result = $result;
        $this->decode();
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
    public const API = ['changeOnline', 'getStreamers', 'status', 'sub'];
    public $request;
    public $parameters;

    public function create(array $request)
    {
        $this->request = $request;
        $this->setParameters();
        $this->setUri();
        return new Request($this->parameters);
    }

    public function setParameters()
    {
        //Gets the first value of the array
        $this->parameters = reset($this->request);
    }

    public function setUri()
    {
        //If the request is from the API add the base URL
        //Otherwise, doesn't change the URI
        if (in_array($this->parameters['uri'], self::API))
            $this->parameters['uri'] = 'http://localhost:8000/api/streamers/' . $this->parameters['uri'];
    }
}
$changeStatus = (new RequestFactory)->create(['online' => ['type' => 'POST', 'uri' => 'changeOnline', 'streamer' => 'xqcow', 'is_online' => '1']])->start();
dump($changeStatus->decode()[0]['id']);
//$this->requestUrl =  'https://api.twitch.tv/helix/streams/?user_login=' . $this->requestStreamer;
//$this->requestType = 'get';
//$this->setFields(array('Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb'));
