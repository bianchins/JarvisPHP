<?php

/**
 * A simple Echo plugin
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
        JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
        JarvisSpeaker::speak($command);
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
        return preg_match('/echo/', $command);
    }
    
    /**
     * Does the plugin need a session?
     * @return boolean
     */
    function hasSession() {
        return false;
    }
}
