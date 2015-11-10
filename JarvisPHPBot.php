<?php
//Composer autoload
require 'vendor/autoload.php';

require 'TelegramBot/GenericCurl.php';
require 'TelegramBot/TelegramBot.php';
require 'TelegramBot/JarvisPHPTelegramBot.php';

use JarvisPHP\TelegramBot\JarvisPHPTelegramBot;

//Load allowedClientIdList
if(file_exists('TelegramBot/allowedClientIdList.json')) {
    $json_config = json_decode(file_get_contents('TelegramBot/allowedClientIdList.json'));
}

if($json_config) {
	//Let's start the bot
	JarvisPHPTelegramBot::run($json_config->allowedClientIdList);
}