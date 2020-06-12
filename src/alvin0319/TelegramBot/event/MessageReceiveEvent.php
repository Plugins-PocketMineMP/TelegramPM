<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\event;

use pocketmine\event\Event;

class MessageReceiveEvent extends Event{

	protected $id;

	protected $message;

	protected $roomId;

	protected $sender;

	public function __construct(int $id, string $message, int $roomId, string $sender){
		$this->id = $id;
		$this->message = $message;
		$this->roomId = $roomId;
		$this->sender = $sender;
	}

	/**
	 * Returns the Message id
	 * @return int
	 */
	public function getId() : int{
		return $this->id;
	}

	/**
	 * Returns the sender
	 * @return string
	 */
	public function getSender() : string{
		return $this->sender;
	}

	/**
	 * Returns the message
	 * @return string
	 */
	public function getMessage() : string{
		return $this->message;
	}

	/**
	 * Returns the room id
	 * @return int
	 */
	public function getRoomId() : int{
		return $this->roomId;
	}
}