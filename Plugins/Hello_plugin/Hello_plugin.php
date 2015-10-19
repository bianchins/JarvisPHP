<?php

namespace JarvisPHP\Plugins\Hello_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * A simple Hello plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Hello_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 2;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        $answer = '';
        JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
        $hour = date("H");
        if($hour>5 && $hour<=13) {
            //Morning
            $answer = JarvisLanguage::translate('hello_morning',get_called_class());
        }
        else if($hour>13 && $hour<18) {
            //Afternoon
            $answer = JarvisLanguage::translate('hello_afternoon',get_called_class());
        } else {
            //Evening (and night, of course)
            $answer = JarvisLanguage::translate('hello_evening',get_called_class());
        }
        

        JarvisTTS::speak($answer);
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
