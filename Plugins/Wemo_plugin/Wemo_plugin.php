<?php

namespace JarvisPHP\Plugins\Wemo_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * A IFTTT Plugin for my Wemo Insight Switch
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Wemo_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
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

        $_IFTTT_MAKER_KEY = '';
        $_IFTTT_MAKER_EVENT = '';

        //Load API key from json config
        if(file_exists('Plugins/Wemo_plugin/api-key.json')) {
            //Create your own api key and put it in api-key.json
            $json_config = json_decode(file_get_contents('Plugins/Wemo_plugin/api-key.json'));
            $_IFTTT_MAKER_KEY = $json_config->ifttt_key;
            $_IFTTT_MAKER_EVENT = $json_config->ifttt_event;
        }

        //Toggle switch via curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://maker.ifttt.com/trigger/'.$_IFTTT_MAKER_EVENT.'/with/key/'.$_IFTTT_MAKER_KEY);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
        curl_setopt($ch, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
        curl_setopt($ch, CURLOPT_POST, 1);
        $headers = array();
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers[] = 'Accept-Encoding: gzip, deflate';
        $headers[] = 'Accept-Language: it-IT,it;q=0.8,en-US;q=0.5,en;q=0.3';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Host: maker.ifttt.com';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        $result = curl_exec($ch);
        
        if(!$result) {
            JarvisPHP::getLogger()->error('Curl error: '.curl_error($ch));
            $answer = JarvisLanguage::translate('command_not_sent',get_called_class());  
        }
        else {
            $answer = JarvisLanguage::translate('command_sent_to_light_switch',get_called_class());    
        }
        
        curl_close($ch);
        
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
