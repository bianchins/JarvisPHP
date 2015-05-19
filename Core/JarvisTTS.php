<?php

namespace JarvisPHP\Core;

/**
 * JarvisTTS
 * 
 * Class for interact with the choosen TTS
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisTTS {
    
    /**
     * Speak the sentence
     * @param string $sentence
     */
    public static function speak($sentence) {
        JarvisPHP::getLogger()->info('JarvisPHP says: "'.$sentence.'"');
        $JarvisTTS = 'JarvisPHP\Speakers\\'._JARVIS_TTS;
        $JarvisTTS::speak($sentence);
    }
    
}
