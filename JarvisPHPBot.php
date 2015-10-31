<?php
//Composer autoload
require 'vendor/autoload.php';

require 'TelegramBot/GenericCurl.php';
require 'TelegramBot/TelegramBot.php';
require 'TelegramBot/JarvisPHPTelegramBot.php';
require 'TelegramBot/allowedClientIdList.php';

use JarvisPHP\TelegramBot\JarvisPHPTelegramBot;
//Let's start the bot
JarvisPHPTelegramBot::run($allowedClientIdList);