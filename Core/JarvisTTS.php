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
        JarvisPHP::getLogger()->info('JarvisPHP says with '.JarvisPHP::$TTS_name.': "'.$sentence.'"');
        $JarvisTTS = 'JarvisPHP\Speakers\\'.JarvisPHP::$TTS_name;
        $JarvisTTS::speak($sentence);
    }
    
}
