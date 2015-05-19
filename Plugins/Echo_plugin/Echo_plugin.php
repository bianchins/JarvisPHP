<?php

namespace JarvisPHP\Plugins\Echo_plugin;

use JarvisPHP\core\JarvisSession;
use JarvisPHP\core\JarvisPHP;
use JarvisPHP\core\JarvisLanguage;
use JarvisPHP\core\JarvisTTS;

/**
 * A simple Echo plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Echo_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
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
        $answer = '';
        if(JarvisSession::get('echo_not_first_passage')) {
            $answer = $command;
        } else {
            JarvisSession::set('echo_not_first_passage',true);
            JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
            $answer = JarvisLanguage::translate('let_s_play',get_called_class());
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
        return true;
    }
}
