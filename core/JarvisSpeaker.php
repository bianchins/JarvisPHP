<?php

class JarvisSpeaker {
    
    public static function speak($sentence) {
        JarvisPHP::getLogger()->info('JasperPHP says: "'.$sentence.'"');
        _JARVIS_TTS::speak($sentence);
    }
    
}
