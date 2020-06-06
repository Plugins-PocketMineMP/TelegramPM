<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\event;

use pocketmine\event\Event;

class MessageReceiveEvent extends Event{

	protected $id;

	protected $message;

	protected $messageId;

	protected $sender;

	public function __construct(int $id, string $message, int $messageId, string $sender){
		$this->id = $id;
		$this->message = $message;
		$this->messageId = $messageId;
		$this->sender = $sender;
	}

	public function getId() : int{
		return $this->id;
	}

	public function getSender() : string{
		return $this->sender;
	}

	public function getMessage() : string{
		return $this->message;
	}

	public function getMessageId() : int{
		return $this->messageId;
	}
}