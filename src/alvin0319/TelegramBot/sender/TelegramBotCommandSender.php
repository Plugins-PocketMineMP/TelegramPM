<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\sender;

use pocketmine\command\CommandSender;
use pocketmine\lang\TextContainer;
use pocketmine\permission\PermissibleBase;
use pocketmine\permission\PermissionAttachment;
use pocketmine\permission\PermissionAttachmentInfo;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\MainLogger;
use pocketmine\utils\TextFormat;

class TelegramBotCommandSender implements CommandSender{

	protected $line = "";

	/** @var PermissibleBase */
	private $perm;

	/** @var int|null */
	protected $lineHeight = null;

	public function __construct(){
		$this->perm = new PermissibleBase($this);
		$this->lines = new \Threaded();
	}

	public function getLine() : ?string{
		$line = $this->line;
		$this->cleanLine();
		return $line;
	}

	public function cleanLine() : void{
		$this->line = "";
	}

	public function isPermissionSet($name) : bool{
		return $this->perm->isPermissionSet($name);
	}

	public function hasPermission($name) : bool{
		return $this->perm->hasPermission($name);
	}

	public function addAttachment(Plugin $plugin, string $name = null, bool $value = null) : PermissionAttachment{
		return $this->perm->addAttachment($plugin, $name, $value);
	}

	public function removeAttachment(PermissionAttachment $attachment) : void{
		$this->perm->removeAttachment($attachment);
	}

	public function recalculatePermissions(){
		$this->perm->recalculatePermissions();
	}

	/**
	 * @return PermissionAttachmentInfo[]
	 */
	public function getEffectivePermissions() : array{
		return $this->perm->getEffectivePermissions();
	}

	/**
	 * @return Server
	 */
	public function getServer(){
		return Server::getInstance();
	}

	/**
	 * @param TextContainer|string $message
	 *
	 * @return void
	 */
	public function sendMessage($message){
		if($message instanceof TextContainer){
			$message = $this->getServer()->getLanguage()->translate($message);
		}else{
			$message = $this->getServer()->getLanguage()->translateString($message);
		}

		/*
		foreach(explode("\n", trim($message)) as $line){
			MainLogger::getLogger()->info($line);
		}
		*/
		$message = TextFormat::clean($message);
		$line = $this->line;
		$this->line .= ($line === "" ? "" : "\n") . $message;
	}

	public function getName() : string{
		return "TelegramBot";
	}

	public function isOp() : bool{
		return true;
	}


	public function setOp(bool $value) : void{

	}

	public function getScreenLineHeight() : int{
		return $this->lineHeight ?? PHP_INT_MAX;
	}

	public function setScreenLineHeight(int $height = null) : void{
		if($height !== null and $height < 1){
			throw new \InvalidArgumentException("Line height must be at least 1");
		}
		$this->lineHeight = $height;
	}
}