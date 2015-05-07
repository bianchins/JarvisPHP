<?php

/* 
 * Espeak voice: 
 * m1, m2, ... m8 male voices
 * f1, f2, f3, f4 female voices
 */
define('_ESPEAK_VOICE','m2');

//Language of text-to-speech
define('_ESPEAK_LANGUAGE','it');

//Amplitude (espeak -a <amplitude>)
define('_ESPEAK_AMPLITUDE','100');

//Command session timeout, in seconds
define('_COMMAND_SESSION_TIMEOUT', 10);

//Select TTS class
define('_JARVIS_TTS', 'espeak_tts');