<?php

/**
 * A simple Echo plugin
 */
class Echo_plugin implements JarvisPluginInterface{
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
        JarvisPHP::getLogger()->info('Answering to command: "'.$command.'"');
        JarvisSpeaker::speak($command);
    }
    
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
}
