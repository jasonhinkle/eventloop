#!/usr/bin/php
<?php 

require __DIR__.'/../vendor/autoload.php';

use verysimple\EventLoop\SleepLoop;

SleepLoop::addEvent(0, function($t){
	echo "TICK $t\n";
});

SleepLoop::addEvent(1, function($t){
	echo "A SECOND $t\n";
});

SleepLoop::addEvent(1, function($t){
	echo "B SECOND $t\n";
});

SleepLoop::addEvent(60, function($t){
	echo "MINUTE $t\n";
	EventLoop::stop();
});

SleepLoop::start();


// clearstatcache();
// $stat = stat($path);
// $stat['mtime'], $stat['size'];