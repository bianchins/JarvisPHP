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
        if(preg_match(JarvisLanguage::translate('preg_match_tell_more',get_called_class()), $command)) {
            //Testing session
            JarvisPHP::getLogger()->debug('User say: '.$command);
            JarvisTTS::speak("Ok, i am on ". php_uname());
            JarvisSession::terminate();
        }
        else {
            JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
            JarvisTTS::speak(sprintf(JarvisLanguage::translate('my_name_is',get_called_class()),$_SERVER['SERVER_NAME'],$_SERVER['SERVER_ADDR']));
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
        return preg_match(JarvisLanguage::translate('preg_match_activate_plugin',get_called_class()), $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return true;
    }
}
