<?php

namespace JarvisPHP\Plugins\RaspPIVolume_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * RaspberryPI Volume Control Plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 * Bash script from http://www.dronkert.net/rpi/vol.html
 */
class RaspPIVolume_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 1;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        $answer = '';

        if(preg_match(JarvisLanguage::translate('preg_match_mute',get_called_class()), $command)) {
            //Mute command
            exec(_JARVISPHP_ROOT_PATH.'/Plugins/RaspPIVolume_plugin/vol.sh 0');
        } else if(preg_match(JarvisLanguage::translate('preg_match_unmute',get_called_class()), $command)) {
            //Unmute command
            exec(_JARVISPHP_ROOT_PATH.'/Plugins/RaspPIVolume_plugin/vol.sh 65');
        } else if(preg_match(JarvisLanguage::translate('preg_match_volume_up',get_called_class()), $command)) {
            //Volume up command
            exec(_JARVISPHP_ROOT_PATH.'/Plugins/RaspPIVolume_plugin/vol.sh +');
        } else if(preg_match(JarvisLanguage::translate('preg_match_volume_down',get_called_class()), $command)) {
            //Volume down
            exec(_JARVISPHP_ROOT_PATH.'/Plugins/RaspPIVolume_plugin/vol.sh -');
        }
        $answer = JarvisLanguage::translate('command_executed',get_called_class());
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
