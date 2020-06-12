<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot;

use alvin0319\TelegramBot\event\MessageReceiveEvent;
use pocketmine\event\Listener;

class EventListener implements Listener{

	protected $loginUsers = [];

	public function onMessageReceive(MessageReceiveEvent $event) : void{
		$message = $event->getMessage();
		$messageId = $event->getRoomId();
		$id = $event->getId();
		TelegramBot::getInstance()->setLastMessage($message);
		if(substr($message, 0, 1) === "/"){
			if(is_int(strpos($message, "/execute"))){
				if(isset($this->loginUsers[$event->getSender()])){
					$commands = explode(" ", $message);
					$command = "";
					$args = "";
					foreach($commands as $key => $c){
						if($key === 1){
							$command = $c;
						}elseif($key >= 2){
							$args .= (substr($args, -1) !== " " ? " " : "") . $c;
						}
					}
					if(trim($command) !== ""){
						TelegramBot::getInstance()->dispatchCommand($id, $command . " " . $args, $event->getRoomId());
					}
				}else{
					TelegramBot::getInstance()->sendMessage("Please login with \"/login <password>\"", $id);
				}
			}elseif(is_int(strpos($message, "/login"))){
				$messages = explode(" ", $message);
				$password = $messages[1] ?? "";
				if(trim($password) !== ""){
					$pf = TelegramBot::getInstance()->getPasswordFor($event->getSender());
					if(trim($pf) !== ""){
						if($password === $pf){
							$this->loginUsers[$event->getSender()] = true;
							TelegramBot::getInstance()->sendMessage("Welcome, " . $event->getSender(), $id);
						}else{
							TelegramBot::getInstance()->sendMessage("Wrong password", $id);
						}
					}else{
						TelegramBot::getInstance()->sendMessage("You are not registered in config.", $id);
					}
				}else{
					TelegramBot::getInstance()->sendMessage("Usage: /login <password>", $id);
				}
			}elseif(is_int(strpos($message, "/getmyid"))){
				if(isset($this->loginUsers[$event->getSender()])){
					TelegramBot::getInstance()->sendMessage("Your room id: " . $event->getId(), $id);
				}else{
					TelegramBot::getInstance()->sendMessage("Please login with \"/login <password>\"", $id);
				}
			}
		}
	}
}