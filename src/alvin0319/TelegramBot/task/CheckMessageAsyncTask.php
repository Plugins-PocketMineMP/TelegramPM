<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\task;

use alvin0319\TelegramBot\util\Promise;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class CheckMessageAsyncTask extends AsyncTask{

	protected $botToken;

	protected $lastMessage;

	public function __construct(Promise $promise, string $botToken, string $lastMessage){
		$this->botToken = $botToken;
		$this->storeLocal($promise);
		$this->lastMessage = $lastMessage;
	}

	public function onRun() : void{
		$url = Internet::getURL("https://api.telegram.org/bot" . $this->botToken . "/getUpdates");
		if($url === false){
			$this->setResult(false);
			return;
		}
		$data = json_decode($url, true);
		if(!is_array($data)){
			$this->setResult(false);
			return;
		}
		//var_dump($data);
		if((bool) $data["ok"]){
			if(isset($data["result"][count($data) - 1])){
				if(isset($data["result"][count($data) - 1]["message"])){
					$lastMessage = $data["result"][count($data["result"]) - 1]["message"]["text"] ?? ""; // text
					$userId = $data["result"][count($data["result"]) - 1]["message"]["chat"]["id"]; // user id
					$messageId = $data["result"][count($data["result"]) - 1]["message"]["message_id"]; // message identifier
					$sender = $data["result"][count($data["result"]) - 1]["message"]["chat"]["username"] ?? $data["result"][count($data["result"]) - 1]["message"]["from"]["username"]; // alvin0319
					if(trim($lastMessage) !== ""){
						if($lastMessage !== $this->lastMessage){
							$this->setResult([
								"message" => $lastMessage,
								"message_id" => $messageId,
								"userId" => $userId,
								"sender" => $sender
							]);
						}else{
							$this->setResult(false);
						}
					}else{
						$this->setResult(false);
					}
				}else{
					$lastMessage = $data["result"][count($data["result"]) - 1]["channl_post"]["text"] ?? ""; // text
					$userId = $data["result"][count($data["result"]) - 1]["channel_post"]["chat"]["id"]; // user id
					$messageId = $data["result"][count($data["result"]) - 1]["channel_post"]["message_id"]; // message identifier
					$sender = $data["result"][count($data["result"]) - 1]["channel_post"]["chat"]["username"] ?? $data["result"][count($data["result"]) - 1]["message"]["from"]["username"]; // alvin0319
					if(trim($lastMessage) !== ""){
						if($lastMessage !== $this->lastMessage){
							$this->setResult([
								"message" => $lastMessage,
								"message_id" => $messageId,
								"userId" => $userId,
								"sender" => $sender
							]);
						}else{
							$this->setResult(false);
						}
					}else{
						$this->setResult(false);
					}
				}
			}else{
				$this->setResult(false);
			}
		}else{
			$this->setResult(false);
		}
	}

	public function onCompletion(Server $server) : void{
		/** @var Promise $promise */
		$promise = $this->fetchLocal();
		if(is_array($result = $this->getResult())){
			$promise->resolve($result);
		}else{
			$promise->reject(null);
		}
	}
}