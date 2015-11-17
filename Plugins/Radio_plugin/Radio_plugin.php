<?php

namespace JarvisPHP\Plugins\Radio_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * Radio_plugin
 *
 * @author Stefano Bianchini
 */
class Radio_plugin implements \JarvisPHP\Core\JarvisPluginInterface {
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 5;
        
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        
        $start_vlc = false;
        $stream_url='';
        $answer='nothing done';
        
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                    
        if(preg_match(JarvisLanguage::translate('preg_match_relax_music',get_called_class()), $command)) { 
            $stream_url = 'http://pub4.radiotunes.com:80/radiotunes_relaxation_aacplus.flv';
            JarvisPHP::getLogger()->debug('Playing relaxation');
            $start_vlc = true;
        }
        if(preg_match(JarvisLanguage::translate('preg_match_lounge_music',get_called_class()), $command)) {
            $stream_url = 'http://pub4.radiotunes.com:80/radiotunes_smoothlounge_aacplus.flv';
            JarvisPHP::getLogger()->debug('Playing lounge');
            $start_vlc = true;
        }
        if(preg_match(JarvisLanguage::translate('preg_match_jazz_music',get_called_class()), $command)) { 
            $stream_url = 'http://pub4.radiotunes.com:80/radiotunes_smoothjazz_aacplus.flv';
            JarvisPHP::getLogger()->debug('Playing jazz');
            $start_vlc = true;
        }
        if(preg_match(JarvisLanguage::translate('preg_match_stop',get_called_class()), $command)) {
            $result = @socket_connect($socket, 'localhost', 9999);
            if ($result === false) {
                //Error handling
                JarvisPHP::getLogger()->warn('Failed to connect!');
                $answer = 'Failed to connect!';
            }
            else {
                $vlc_remote_command = 'stop'.chr(13);
                $vlc_remote_command = 'quit'.chr(13);
                socket_write($socket, $vlc_remote_command, strlen($vlc_remote_command));
                socket_close($socket);
                JarvisPHP::getLogger()->debug('Radio stopped');
                $answer = 'Radio stopped';
            }
            $start_vlc = false;    
        }
        if($start_vlc) {
            //Start Vlc on port 9999
            //exec('vlc -I rc --rc-host localhost:9999');
            @exec('/usr/bin/nohup /usr/bin/vlc -I rc --rc-host localhost:9999 &');

            sleep(1);

            $result = @socket_connect($socket, 'localhost', 9999);
            if ($result === false) {
                //Error handling
                JarvisPHP::getLogger()->warn('Failed to connect!');
                $answer = 'Failed to connect!';
            }
            else {
                $vlc_remote_command = 'add '.$stream_url.chr(13);
                socket_write($socket, $vlc_remote_command, strlen($vlc_remote_command));
                socket_close($socket);
                JarvisPHP::getLogger()->debug('Radio started');
                $answer = 'Radio started';
            }
        }
        
        $response = new \JarvisPHP\Core\JarvisResponse($answer, JarvisPHP::getRealClassName(get_called_class()), true);
        $response->send();
        
    }
    
    /**
     * Get plugin's priority
     * @return int
     */
    function getPriority() {
        return $this->priority;
    }
    
    /**
     * Is it the right plugin for the command?
     * @param string $command
     * @return boolean
     */
    function isLikely($command) {
        return preg_match(JarvisLanguage::translate('preg_match_activate_plugin',get_called_class()), $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return false;
    }
}
