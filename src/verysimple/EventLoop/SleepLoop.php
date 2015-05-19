<?php
namespace verysimple\EventLoop;
/**
 *
* @author jason
*
*/
class SleepLoop
{
	private static $START_TIME = 0;
	private static $CURRENT_TIME = 0;
	private static $LAST_SECOND = 0;
	private static $LAST_MINUTE = 0;
	private static $LAST_HOUR = 0;
	private static $SLEEP_TIME = 2000;

	private static $TICK_EVENTS = array();
	private static $SECOND_EVENTS = array();
	private static $MINUTE_EVENTS = array();
	private static $HOUR_EVENTS = array();
	private static $GO = true;

	private static function init() {
		self::$CURRENT_TIME = microtime(true);
		self::$START_TIME = self::$CURRENT_TIME;
		self::$LAST_HOUR = self::$CURRENT_TIME;
		self::$LAST_MINUTE = self::$CURRENT_TIME;
		self::$LAST_SECOND = self::$CURRENT_TIME;
		self::$GO = true;

	}

	private static function call($events) {
		foreach ($events as $event) {
			call_user_func($event,self::$CURRENT_TIME);
		}
	}

	private static function onTick() {
		self::$CURRENT_TIME = microtime(true);
		self::call(self::$TICK_EVENTS);
		if (self::$CURRENT_TIME - self::$LAST_SECOND >= 1) self::onSecond();
	}

	private static function onSecond() {
		self::call(self::$SECOND_EVENTS);
		if (self::$CURRENT_TIME - self::$LAST_MINUTE >= 60) self::onMinute();
		self::$LAST_SECOND = self::$CURRENT_TIME;
	}

	private static function onMinute() {
		self::call(self::$MINUTE_EVENTS);
		if (self::$CURRENT_TIME - self::$LAST_HOUR >= 3600) self::onHour();
		self::$LAST_MINUTE = self::$CURRENT_TIME;
	}

	private static function onHour() {
		self::call(self::$HOUR_EVENTS);
		self::$LAST_HOUR = self::$CURRENT_TIME;
	}

	static function addEvent($interval,callable $callback) {

		if ($interval >= 60) {
			self::$MINUTE_EVENTS[] = $callback;
		}
		elseif ($interval >= 1) {
			self::$SECOND_EVENTS[] = $callback;
		}
		else {
			self::$TICK_EVENTS[] = $callback;
		}
	}

	static function start() {

		self::init();

		while(self::$GO) {
			usleep(self::$SLEEP_TIME);
			self::onTick();
		}


	}

	static function stop() {
		self::$GO = false;
	}

	public function __construct() {
		throw new Exception('EventLoop is a static class and cannot be instantiated');
	}

}


