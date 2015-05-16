<?php

namespace JarvisPHP\Speakers;

/**
 * Wrapper class for using OSX Say as tts
 *
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Say_tts {
    
    public static function speak($sentence) {
        exec('/usr/bin/say "'.$sentence.'"');
    }
    
}
