How to use JarvisPHP TelegramBot
--------------------------------

JarvisPHP TelegramBot is a Telegram Bot that interact with JarvisPHP.

You have to:
- set the right jarvisPhp Url in JarvisPHP, in line:
  define('_JARVISPHP_URL','http://localhost:8000/answer');
- Create a Bot in Telegram, please refer to https://core.telegram.org/bots
- Obtain a Bot Token from the @BotFather
- Set the Bot Token in a file called api-key.json with this structure:
  {"bot_token" : "<Your-Bot-Token>"}
- Add your ID in allowedClientIdList.json (yes, your bot will be private, only allowed from these ID!)

And finally, from the root of JarvisPHP project folder, execute:
php JarvisPHPBot.php
