<?php


$pid = pcntl_fork();
if ($pid == -1) {
     die('could not fork');
} else if ($pid) {
     // we are the parent
     pcntl_wait($status); //Protect against Zombie children
} else {
    exec('php .\DefaultScraper.php sodapoppin');

     // we are the child
}
