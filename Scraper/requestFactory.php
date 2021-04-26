<?php

require_once(__DIR__ . '/vendor/autoload.php');

class Request
{

    private $ch;

    public function __construct($ch)
    {
        $this->ch = $ch;
    }

    public function start()
    {
        return new RequestResult(curl_exec($this->ch));
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
    private $requestUrl;
    private $requestFields;
    private $requestType;
    private $ch;

    public function create(array $request)
    {
        $this->request = $request;
        $this->ch = curl_init(); //Initiates curl request
        $this->setParameters();
        $this->setRequestType();
        $this->setHeader();
        return new Request($this->ch);
    }

    public function setParameters()
    {
        //Gets the first array
        $this->requestFields = reset($this->request);

        $this->getKey();

        $this->requestUrl = $this->setUri();
        $this->requestType = $this->requestFields['type'];
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
        if (in_array($this->request, self::API)) {
            if ($this->request == 'sub')
                $this->requestFields['uri'] = 'http://localhost:8000/api/create/' . $this->requestFields['uri'];
            else {
                $this->requestFields['uri'] = 'http://localhost:8000/api/streamers/' . $this->requestFields['uri'];
            }
        } else if ($this->request == 'twitchOnline')
            $this->requestFields['uri'] = 'https://api.twitch.tv/helix/streams/?user_login=' . $this->requestFields['streamer'];

        return $this->requestFields['uri'];
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

$changeStatus = (new RequestFactory)->create(['twitchOnline' => ['type' => 'GET', 'streamer' => 'xqcow', 'Authorization: Bearer gokyy7wxa9apriyjr2evaccv6h71qn', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb']])->start();
dump(empty($changeStatus->decode()['data']));
$changeStatus = (new RequestFactory)->create(['status' => ['type' => 'POST', 'uri' => 'changeStatus', 'streamer' => 'xqcow', 'run' => '1']])->start();
dump($changeStatus->result());
