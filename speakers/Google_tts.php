<?php

/**
 * Wrapper class for using GoogleTTS as tts
 *
 * @author Stefano Bianchini
 */
class Google_tts {
    
    public static function speak($sentence) {
        file_put_contents('tts.mp3', file_get_contents('http://translate.google.com/translate_tts?tl='._LANGUAGE.'&q='.urlencode($sentence)));
        exec('aplay tts.mp3 && rm tts.mp3');
    }
    
}
