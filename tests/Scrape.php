<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;


require_once('C:\Users\nunom\Documents\GitHub\twitch-scraper\vendor\autoload.php');
putenv('WEBDRIVER_CHROME_DRIVER=C:\Users\nunom\Documents\GitHub\twitch-scraper\tests\chromedriver.exe');


getStreamer();
$driver = startDriver();
scrape($driver);

function getStreamer()
{

    $GLOBALS['streamer'] = $GLOBALS['argv'][1];
    if (empty($GLOBALS['streamer'])) {
        var_dump('Please enter a streamer as argument');
        return "test";
    }
}

function startDriver()
{

    $driver = ChromeDriver::start();
    // Go to URL
    $streamer = $GLOBALS['streamer'];
    $driver->get('https://www.twitch.tv/' . $streamer);
    return $driver;
}
function scrape($driver)
{
    while (true) {
        // Find search element by its id, write 'PHP' inside and submit
        try {
            $elements = '';
            $elements = $driver->findElements(WebDriverBy::cssSelector('.tw-c-background-alt'));
            foreach ($elements as $element) {
                if ($element == null || $elements == null) {
                    break;
                }
                $text = $element->getText();
                $id = $element->getID();
                if ($text != null) {
                    $url = 'http://localhost:8000/api/create/sub';
                    $fields = ['element_id' => $id, 'element_text' => $text, 'streamer' => $GLOBALS['streamer']];
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
                    dump($text);
                    dump($id);
                }
                $text = '';
            }
            $elements = '';
        } catch (Exception $e) {
            $elements = '';
            dump($e);
        }
    }
}
