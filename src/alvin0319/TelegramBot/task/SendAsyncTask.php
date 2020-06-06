<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\task;

use alvin0319\TelegramBot\util\Promise;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Internet;

class SendAsyncTask extends AsyncTask{

	protected $botToken;

	protected $userId;

	protected $message;

	protected $reply = false;

	protected $chatId = null;

	public function __construct(Promise $promise, string $botToken, int $userId, string $message, bool $reply = false, ?int $chatId = null){
		$this->storeLocal($promise);
		$this->botToken = $botToken;
		$this->userId = $userId;
		$this->message = $message;
		$this->reply = $reply;
		$this->chatId = $chatId;
	}

	public function onRun() : void{
		$formatted = strval($this->message); //"```" . $this->message . "```"; // markdown
		$url = "https://api.telegram.org/bot" . $this->botToken . "/sendMessage?"; //https://api.telegram.org/bot" . $this->botToken . "/sendMessage?chat_id=" . $this->userId . "&text=" . $formatted; // . "&parse_mode=markdown";
		$data = [
			"chat_id" => $this->userId,
			"text" => $formatted
		];
		if($this->reply){
			if(is_int($this->chatId)){
				$data["reply_to_message_id"] = $this->chatId;
			}
		}
		$send = Internet::postURL($url, $data);
		if(!is_string($send)){
			$this->setResult(false);
			return;
		}
		$decoded = json_decode($send, true);
		if(!is_array($decoded)){
			$this->setResult(false);
			return;
		}
		$this->setResult($decoded);
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