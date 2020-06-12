# TelegramPM
A PocketMine-MP Plugin that connect PM Server <-> Telegram

## Setting

See [Step by setup](https://github.com/alvin0319/TelegramPM/blob/master/step-by-setup.md)

## API
> send message
>
> `\alvin0319\TelegramBot\TelegramBot::getInstance()->sendMessage("your message", "chat id")`

> Event
>
> `\alvin0319\TelegramBot\event\MessageReceiveEvent`
> 
> `\alvin0319\TelegramBot\event\MessageSendEvent`

## Note on use

After restarting the server and before running commands on the bot, you must log in with `/login <password>`.

> Welcome, username

If you see this, you are successfully logged in.

Command can be used as `/execute <command>`.

## Warning
YOU SHOULD NOT LEAK YOUR BOT'S TOKEN OR NAME. PASSWORD ENTRY IS REQUIRED, BUT THIS IS NOT SAFE.

## Test

![](https://raw.githubusercontent.com/alvin0319/TelegramPM/master/images/image.PNG)