<?php

namespace JarvisPHP\Speakers;

//Load TTS config
require 'config/Espeak_config.php';

/**
 * Wrapper class for using espeak as tts
 *
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Espeak_tts {
    
    public static function speak($sentence) {
        @exec('/usr/bin/espeak -w out.wav -v'._ESPEAK_LANGUAGE.'+'._ESPEAK_VOICE.' "'.$sentence.'" && aplay out.wav && rm out.wav');
    }
    
}
