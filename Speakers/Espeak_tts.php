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
        exec('"C:\Program Files (x86)\eSpeak\command_line\espeak.exe" -w out.wav -v'._ESPEAK_LANGUAGE.'+'._ESPEAK_VOICE.' "'.$sentence.'" && vlc.exe --intf dummy --play-and-exit out.wav && rm out.wav');
    }
    
}
