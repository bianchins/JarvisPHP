<?php

/**
 * Info plugin
 */
class Info_plugin implements JarvisPluginInterface{
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
        if(preg_match('/(tell me more)/', $command)) {
            //Testing session
            JarvisPHP::getLogger()->debug('User say: '.$command);
            JarvisSpeaker::speak("What? No! Ok, i am on ". php_uname());
            JarvisSession::terminate();
        }
        else {
            JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
            JarvisSpeaker::speak("My name is JarvisPHP. I'm running on ".$_SERVER['SERVER_NAME']." with the ip address ".$_SERVER['SERVER_ADDR']);
        }
    }
    /**
     * Get plugin's priority
     * @return boolean
     */
    function getPriority() {
        return $this->priority;
    }
    
    /**
     * 
     * @param string $command
     * @return boolean
     */
    function isLikely($command) {
        return preg_match('/(info|information|who are you)/', $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return true;
    }
}
