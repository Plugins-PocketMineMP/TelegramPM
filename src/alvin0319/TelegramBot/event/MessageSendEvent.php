<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\event;

use pocketmine\event\Cancellable;
use pocketmine\event\Event;

class MessageSendEvent extends Event implements Cancellable{

	protected $message;

	protected $id;

	public function __construct(string $message, int $id){
		$this->message = $message;
		$this->id = $id;
	}

	public function getMessage() : string{
		return $this->message;
	}

	public function getId() : int{
		return $this->id;
	}

	public function setMessage(string $message) : void{
		$this->message = $message;
	}

	public function setId(int $id) : void{
		$this->id = $id;
	}
}