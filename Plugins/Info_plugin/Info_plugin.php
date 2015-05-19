<?php

namespace JarvisPHP\Plugins\Info_plugin;

use JarvisPHP\core\JarvisSession;
use JarvisPHP\core\JarvisPHP;
use JarvisPHP\core\JarvisLanguage;
use JarvisPHP\core\JarvisTTS;

/**
 * Info plugin
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Info_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
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
        if(preg_match(JarvisLanguage::translate('preg_match_tell_more',get_called_class()), $command)) {
            //Testing session
            JarvisPHP::getLogger()->debug('User says: '.$command);
            $answer = 'Ok, i am on '. php_uname();
            JarvisSession::terminate();
        }
        else {
            JarvisPHP::getLogger()->debug('Answering to command: "'.$command.'"');
            $answer = sprintf(JarvisLanguage::translate('my_name_is',get_called_class()),_SYSTEM_NAME, $_SERVER['SERVER_NAME'],$_SERVER['SERVER_ADDR']);
            
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
