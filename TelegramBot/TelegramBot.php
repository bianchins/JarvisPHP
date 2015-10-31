<?php

namespace JarvisPHP\TelegramBot;

/**
 * TelegramBot
 * @author Many
 * copied from example found in Telegram site 
 */
class TelegramBotApiWrapper {

	function exec_curl_request($handle) {
	  $response = curl_exec($handle);

	  if ($response === false) {
	    $errno = curl_errno($handle);
	    $error = curl_error($handle);
	    echo("Curl returned error $errno: $error\n");
	    curl_close($handle);
	    return false;
	  }

	  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
	  curl_close($handle);

	  if ($http_code >= 500) {
	    // do not want to DDOS server if something goes wrong
	    sleep(10);
	    return false;
	  } else if ($http_code != 200) {
	    $response = json_decode($response, false);
	    echo("Request has failed with error ".$response->error_code." : ".$response->description."\n");
	    if ($http_code == 401) {
	      throw new Exception('Invalid access token provided');
	    }
	    return false;
	  } else {
	    $response = json_decode($response, false);
	    if (isset($response->description)) {
	      echo("Request was successfull: ".$response->description."\n");
	    }
	    $response = $response->result;
	  }

	  return $response;
	}

	function apiRequestJson($method, $parameters) {
	  if (!is_string($method)) {
	    echo("Method name must be a string\n");
	    return false;
	  }

	  if (!$parameters) {
	    $parameters = array();
	  } else if (!is_array($parameters)) {
	    error_log("Parameters must be an array\n");
	    return false;
	  }

	  $parameters["method"] = $method;

	  $_BOT_TOKEN = '';
	  //Load API key from json config
      if(file_exists('TelegramBot/api-key.json')) {
          //Create your own bot token and put it in api-key.json
          // like {"bot_token": "<your-bot-token>"}
          $json_config = json_decode(file_get_contents('TelegramBot/api-key.json'));
          $_BOT_TOKEN = $json_config->bot_token;
      }

	  $handle = curl_init('https://api.telegram.org/bot'.$_BOT_TOKEN.'/');
	  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
	  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
	  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
	  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

	  return $this->exec_curl_request($handle);
	}

}