# Step by setup

* Create your own Telegram bot using [BotFather](https://t.me/botFather)
* Copy the bot token and paste it in the token field of config.yml.
* Fill in the field values of allow-user and passwords. example:
```yaml
allow-user:
  - alvin0319
  - someuser

passwords:
  alvin0319: my_password_1
  someuser: my_password_2

lastMessage: ""

token: "YOUR BOT TOKEN"

chat-id: ~
```
* Next, plug in the server and run it, and enter the following command in your bot you created: `/login <your_password>`

If you see `Welcome, <username>`, you are successfully logged in.

And, you can use the command with `/execute <command>`.

## Question
> Q. What is chat-id?
* A. chat-id is your telegram chat room ID, which will be used in daily reports to be supported in the future.
> Q. The plugin says there is no password for user.
* A. Make sure that you typed correctly for the correct YAML grammar, and that you entered it in a case-sensitive way.
> Q. How can I get my chat-id?
* A. login with `/login <password>` and do `/getmyid`