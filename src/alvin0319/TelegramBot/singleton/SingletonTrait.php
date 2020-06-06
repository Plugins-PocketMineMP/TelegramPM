<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\singleton;

trait SingletonTrait{

	private static $instance = null;

	public function init() : void{
		self::$instance = $this;
	}

	public static function getInstance() : self{
		return self::$instance;
	}
}