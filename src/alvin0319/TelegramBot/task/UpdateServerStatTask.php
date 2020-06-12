<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\task;

use alvin0319\TelegramBot\TelegramBot;
use pocketmine\scheduler\Task;

class UpdateServerStatTask extends Task{

	public function onRun(int $currentTick) : void{
		TelegramBot::getInstance()->checkDate();
		if($currentTick % 1200 === 0){ // 1 minute
			TelegramBot::getInstance()->updateServerStat();
		}
	}
}