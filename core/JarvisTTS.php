<?php

class JarvisTTS {
    
    public static function speak($sentence) {
        JarvisPHP::getLogger()->info('JasperPHP says: "'.$sentence.'"');
        $JarvisTTS = _JARVIS_TTS;
        $JarvisTTS::speak($sentence);
    }
    
}
