<?php
namespace JarvisPHP\TelegramBot;

use JarvisPHP\TelegramBot\TelegramBotApiWrapper;
use JarvisPHP\TelegramBot\GenericCurl;

//Very important!
define('_JARVISPHP_URL','http://localhost:8000/answer');

/**
 * A TelegramBot for JarvisPHP
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisPHPTelegramBot {
	
	public static function run($allowedClientIdList) {

		$bot = new TelegramBotApiWrapper();

		$offset = 0;

		echo "JarvisPHP Telegram Bot started at ".date('Y-m-d H:i:s')."\n";
		echo "-----------------------------------------------------\n";

		while(true) {

			$updates = $bot->apiRequestJson("getUpdates", array('offset'=>$offset));
			
			if($updates) {
				foreach($updates as $update) {

					if(isset($update->message->text)) {

						//TODO check if $update->message->chat->id is in enabled list

						$bot->apiRequestJson("sendChatAction", array('chat_id' => $update->message->chat->id, 'action' => 'typing'));

						echo "Processing message ->".$update->message->text."\n";

						$message = $update->message->text;

						$offset = $update->update_id + 1;

						$response = '';

						//Understand if the message is a telegram command (begins with "/")
						if(preg_match('$^/(.+)$', $message)) {
							
							//Telegram command
							switch($message) {
								case '/start': $response='I am JarvisPHP, a private bot. \u1F510'; break;
								case '/info': $response='I am JarvisPHP, a private bot. \u1F510'; break;
								case '/register': 
												$response='Ok, i registered your ID in registerIdLog.log'; 
												file_put_contents('TelegramBot/registerIdLog.log', '['.date('Y-m-d H:i:s').'] ID:'.$update->message->from->id . '; FIRSTNAME:'. $update->message->from->first_name . '; LASTNAME:'. $update->message->from->last_name. '; USERNAME:'. $update->message->from->username.PHP_EOL , FILE_APPEND | LOCK_EX);
												break;
							}

							//Encode emoji
							$response = preg_replace_callback('/\\\\u([0-9a-fA-F]+)/', function ($match) {
							    return iconv('UCS-4LE', 'UTF-8', pack('V', hexdec($match[1])));
							}, $response);

						} else {
							if(in_array($update->message->chat->id, $allowedClientIdList)) {
								//Redirect message to JarvisPhp
								$JarvisResponse = GenericCurl::exec(_JARVISPHP_URL, array('command'=>$message, 'tts' => 'None_tts'));

								$response = $JarvisResponse->answer;
							} else {
								$response = 'You are not allowed to speak with me.';
							}

						}
						if($response) {
							$bot->apiRequestJson("sendMessage", array('chat_id' => $update->message->chat->id, "text" => $response));
						}
					}
				}
			}
			sleep(1);
		}
	}
}