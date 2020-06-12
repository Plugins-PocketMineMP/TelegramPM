<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot;

use alvin0319\TelegramBot\event\MessageSendEvent;
use alvin0319\TelegramBot\sender\TelegramBotCommandSender;
use alvin0319\TelegramBot\singleton\SingletonTrait;
use alvin0319\TelegramBot\task\CheckMessageTask;
use alvin0319\TelegramBot\task\SendAsyncTask;
use alvin0319\TelegramBot\util\Promise;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\MainLogger;

class TelegramBot extends PluginBase{
	use SingletonTrait;

	/** @var TelegramBotCommandSender */
	protected $sender;

	protected $token;

	protected $users;

	protected $lastMessage;

	public function onLoad() : void{
		$this->init();
	}

	public function onEnable() : void{
		$this->saveResource("config.yml");
		$users = $this->getConfig()->getNested("allow-user", []);
		if(count($users) === 0){
			$this->getLogger()->error("Config type allow-user is empty, disabling plugin...");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$token = $this->getConfig()->getNested("token", "");
		if(trim($token) === ""){
			$this->getLogger()->error("Bot token is empty, disabling plugin...");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$passwords = $this->getConfig()->getNested("passwords", []);
		if(count($users) !== count($passwords)){
			$this->getLogger()->error("User count and password count does not match, disabling plugin...");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$found = false;
		$invalidUsers = [];
		foreach($users as $user){
			if(!isset($passwords[$user])){
				$found = true;
				$invalidUsers[] = $user;
			}
		}
		if($found){
			$this->getLogger()->error("User for " . implode(", ", $invalidUsers) . " does not have password.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		if(is_null($this->getConfig()->getNested("date", null))){
			$this->getConfig()->setNested("date", intval(date("d")));
		}
		$this->sender = new TelegramBotCommandSender();
		$this->token = $token;
		$this->users = $users;
		$this->lastMessage = $this->getConfig()->getNested("lastMessage", "");

		$this->getScheduler()->scheduleRepeatingTask(new CheckMessageTask(), 30); // async task delay
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}

	public function onDisable() : void{
		$this->getConfig()->setNested("lastMessage", (string) $this->lastMessage);
		$this->getConfig()->save();
	}

	public function getBotToken() : string{
		return $this->token;
	}

	public function getLastMessage() : string{
		return $this->lastMessage;
	}

	public function setLastMessage(string $message) : void{
		$this->lastMessage = $message;
	}

	public function dispatchCommand(int $id, string $command, ?int $chatId = null) : void{
		$this->getServer()->getCommandMap()->dispatch($this->sender, $command);
		$line = $this->sender->getLine();
		if(!is_string($line) or trim($line) === ""){
			$p = new \ReflectionProperty(MainLogger::class, "logStream");
			if(!$p->isPublic()){
				$p->setAccessible(true);
			}
			/** @var \Threaded $v */
			$v = $p->getValue(MainLogger::getLogger());
			$line = $v->shift();
		}
		if(!is_string($line)){
			$line = "";
		}
		$this->sendMessage($line, $id, $chatId);
	}

	public function sendMessage(string $message, int $id, ?int $chatId = null) : void{
		$ev = new MessageSendEvent($message, $id);
		$ev->call();
		if(!$ev->isCancelled()){
			$promise = new Promise();
			$reply = is_int($chatId);
			$this->getServer()->getAsyncPool()->submitTask(new SendAsyncTask($promise, $this->getBotToken(), $ev->getId(), $ev->getMessage(), $reply, $chatId));
			$promise->then(function(array $result){
				if($result["ok"]){
					$this->getLogger()->debug("Succeed to send message");
				}else{
					$this->getLogger()->debug("Failed to send message: " . $result["description"]);
				}
			})->catch(function($unused){
				$this->getLogger()->debug("Failed to send message");
			});
		}
	}

	public function getPasswordFor(string $userName) : string{
		return $this->getConfig()->getNested("passwords." . $userName, "");
	}

	public function checkDate() : void{
		if($this->getConfig()->getNested("date", intval(date("d"))) !== intval(date("d"))){
			$this->getConfig()->setNested("date", intval(date("d")));
			if(is_int($id = $this->getConfig()->getNested("chat-id", null))){

			}
		}
	}
}