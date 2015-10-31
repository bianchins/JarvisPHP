<?php
namespace JarvisPHP\TelegramBot;

/**
 * A Generic Curl form POST request to JarvisPHP
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class GenericCurl {

	static function exec($url, $fields) {


		$fields_string = "";
		//url-ify the data for the POST
		if(count($fields)>0) {
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
		} 
		
		//echo $fields_string;
		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, "TelegramBot/JarvisPHPSession.cookie");
		curl_setopt($ch, CURLOPT_COOKIEJAR, "TelegramBot/JarvisPHPSession.cookie"); 

		//execute post
		$result = @curl_exec($ch);

		$http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		//close connection
		curl_close($ch);



		return ($http_response_code==200) ? json_decode($result) : false;
	}
}