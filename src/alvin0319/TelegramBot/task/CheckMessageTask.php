<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\task;

use alvin0319\TelegramBot\event\MessageReceiveEvent;
use alvin0319\TelegramBot\TelegramBot;
use alvin0319\TelegramBot\util\Promise;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class CheckMessageTask extends Task{

	public function onRun(int $unused) : void{
		$promise = new Promise();
		Server::getInstance()->getAsyncPool()->submitTask(new CheckMessageAsyncTask($promise, TelegramBot::getInstance()->getBotToken(), TelegramBot::getInstance()->getLastMessage()));
		$promise->then(function(array $data){
			$ev = new MessageReceiveEvent($data["userId"], $data["message"], $data["message_id"], $data["sender"]);
			$ev->call();
		})->catch(function($unused){
		});
	}
}