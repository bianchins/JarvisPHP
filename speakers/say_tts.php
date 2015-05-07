<?php

/**
 * Wrapper class for using OSX Say as tts
 *
 * @author Stefano Bianchini
 */
class say_tts {
    
    public function speak($sentence) {
        exec('/usr/bin/say "'.$sentence.'"');
    }
    
}
