#!/usr/bin/php
<?php 

require __DIR__.'/../vendor/autoload.php';

use verysimple\EventLoop\TickLoop;

TickLoop::addEvent(0, function($t){
	echo "TICK $t\n";
});

TickLoop::addEvent(1, function($t){
	echo "A SECOND $t\n";
});

TickLoop::addEvent(1, function($t){
	echo "B SECOND $t\n";
});

TickLoop::addEvent(60, function($t){
	echo "MINUTE $t\n";
	EventLoop::stop();
});

TickLoop::start();


// clearstatcache();
// $stat = stat($path);
// $stat['mtime'], $stat['size'];