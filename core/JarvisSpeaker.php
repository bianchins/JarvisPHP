<?php

class JarvisSpeaker {
    
    public static function speak($sentence) {
        JarvisPHP::getLogger()->info('JasperPHP says: "'.$sentence.'"');
        exec('/usr/bin/espeak -w out.wav -v'._ESPEAK_LANGUAGE.'+'._ESPEAK_VOICE.' "'.$sentence.'" && aplay out.wav && rm out.wav');
    }
    
}
