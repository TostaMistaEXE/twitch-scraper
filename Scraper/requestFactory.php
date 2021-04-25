<?php

require_once(__DIR__ . '/vendor/autoload.php');

class Request
{
    private $requestUrl;
    private $requestFields;
    private $requestResult;
    private $requestType;
    private $ch;

    public function __construct($request)
    {
        $this->requestType = $request['type'];
        $this->requestUrl = $request['uri'];
        $this->start();
    }

    public function start()
    {
        $this->request();
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
    public function request()
    {
        $this->ch = curl_init();
        $this->setHeader();
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
        return $this->requestResult;
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
        $this->setUri();
        return new Request($this->parameters);
    }

    public function setParameters()
    {
        $this->parameters = reset($this->request);
    }

    public function setUri()
    {
        //If the request is from the API add the base URL
        //Otherwise, doesn't change the URI
        if (in_array($this->request, self::API))
            $this->parameters['uri'] = 'http://localhost:8000/api/streamers' . $this->parameters['uri'];
    }
}
$changeStatus = (new RequestFactory)->create(['online' => ['type' => 'GET', 'uri' => '/changeOnline', 'streamer' => 'xqcow', 'is_online' => '1']]);
//$this->requestUrl =  'https://api.twitch.tv/helix/streams/?user_login=' . $this->requestStreamer;
//$this->requestType = 'get';
//$this->setFields(array('Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb'));
