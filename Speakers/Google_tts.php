<?php

namespace JarvisPHP\Speakers;

/**
 * Wrapper class for using GoogleTTS as tts
 *
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Google_tts {
    
    public static function speak($sentence) {

    	//Do not request a new mp3 if it exists in cache folder
    	if(!file_exists(_JARVISPHP_ROOT_PATH.'/Speakers/cache/'.md5($sentence).'.mp3')) {
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'http://translate.google.com/translate_tts?ie=UTF-8&client=t&tl='._LANGUAGE.'&q='.urlencode($sentence),
			    CURLOPT_USERAGENT => 'stagefright/1.2 (Linux;Android 5.0)',
			    CURLOPT_REFERER => 'http://translate.google.com/'
			));

			$resp = curl_exec($curl);

			curl_close($curl);

	        file_put_contents(_JARVISPHP_ROOT_PATH.'/Speakers/cache/'.md5($sentence).'.mp3', $resp);
    	}

        exec('aplay '._JARVISPHP_ROOT_PATH.'/Speakers/cache/'.md5($sentence).'.mp3');
    }

}
