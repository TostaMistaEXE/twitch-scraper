<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class test extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            while (true) {
                $browser->wait()->until(
                    WebDriverExpectedCondition::titleIs('My Page')
                  );

            }
        });
    }
}
