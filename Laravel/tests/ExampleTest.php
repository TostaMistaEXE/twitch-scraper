u<?php

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeDriverService;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;

require_once(__DIR__.'/../vendor/autoload.php');
putenv('WEBDRIVER_CHROME_DRIVER=chromedriver');


$options = new ChromeOptions();
$options->addArguments(array(    
    '--window-size=1280x800',
    '--no-sandbox'
));
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
$driver = ChromeDriver::start($capabilities);
$host = 'https://twitch.tv';

//$driver = RemoteWebDriver::create($host , $capabilities ,5000);

// Go to URL
$streamer = 'xqcow';
$driver->get('https://www.twitch.tv/'.$streamer);

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
                $fields = ['element_id' => $id, 'element_text' => $text, 'streamer'=> $streamer];
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
// Find element of 'History' item in menu by its css selector
$historyButton = $driver->findElement(
    WebDriverBy::cssSelector('#ca-history a')
);
// Read text of the element and print it to output
echo 'About to click to a button with text: ' . $historyButton->getText();

// Click the element to navigate to revision history page
$historyButton->click();
