<?php

require_once(__DIR__ . '/vendor/autoload.php');



class Request
{
    private $requestUrl;
    private $requestFields;
    private $requestResult;
    private $requestStreamer;
    private $requestStatus;

    public function __construct($streamer, $status = null, $url = null)
    {
        $this->requestStreamer = $streamer;
        $this->requestStatus = $status;
        $this->requestUrl = $url;
        $this->start();
       
    }

    public function start(){
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
            $this->requestFields = array('Authorization: Bearer 2xzskw949bee02hli7jc8tpwwxe256', 'Client-ID: gosbl0lt05vzj18la6v11lexhvpwlb');
            $this->request();
            return;
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

    public function request()
    {
        $fields_string = http_build_query($this->requestFields);
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->requestUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $this->requestResult = curl_exec($ch);
        return $this->result();
    }

    public function result()
    {
        dump($this->requestResult);
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
$changeStatus = RequestFactory::create('ludwig','0','online');
