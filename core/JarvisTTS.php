<?php
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
        $JarvisTTS = _JARVIS_TTS;
        $JarvisTTS::speak($sentence);
    }
    
}
