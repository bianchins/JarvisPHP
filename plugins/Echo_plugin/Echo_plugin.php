<?php

/**
 * A simple Echo plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Echo_plugin implements JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 3;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        if(JarvisSession::get('echo_not_first_passage')) {
            JarvisTTS::speak($command);
        } else {
            JarvisSession::set('echo_not_first_passage',true);
            JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
            JarvisTTS::speak(JarvisLanguage::translate('let_s_play',get_called_class()));
        }
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
        return preg_match('/echo/', $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return true;
    }
}
