<?php
declare(strict_types=1);
namespace alvin0319\TelegramBot\command;

use alvin0319\TelegramBot\TelegramBot;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class SendDailyReportCommand extends Command{

	public function __construct(){
		parent::__construct("dailyreport", "Send a daily report.");
		$this->setPermission("telegrambot.command.dailyreport");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(is_int($id = TelegramBot::getInstance()->getConfig()->getNested("chat-id", null))){
			TelegramBot::getInstance()->sendDailyReport($id);
			$sender->sendMessage("You have successfully sent the daily report.");
		}else{
			$sender->sendMessage("Chat-id is not set in config.yml.");
		}
		return true;
	}
}