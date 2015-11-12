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

		$curl = curl_init();
		
		curl_setopt_array($curl, array(
		    CURLOPT_RETURNTRANSFER => 1,
		    CURLOPT_URL => 'http://translate.google.com/translate_tts?ie=UTF-8&client=t&tl='._LANGUAGE.'&q='.urlencode($sentence),
		    CURLOPT_USERAGENT => 'stagefright/1.2 (Linux;Android 5.0)',
		    CURLOPT_REFERER => 'http://translate.google.com/'
		));

		$resp = curl_exec($curl);

		curl_close($curl);

        file_put_contents('tts.mp3', $resp);
        exec('aplay tts.mp3 && rm tts.mp3');
    }

}
