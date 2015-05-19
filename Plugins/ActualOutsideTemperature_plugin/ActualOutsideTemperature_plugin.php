<?php

namespace JarvisPHP\Plugins\ActualOutsideTemperature_plugin;

use JarvisPHP\core\JarvisSession;
use JarvisPHP\core\JarvisPHP;
use JarvisPHP\core\JarvisLanguage;
use JarvisPHP\core\JarvisTTS;

/**
 * ActualOutsideTemperature_plugin
 *
 * @author Stefano Bianchini
 */
class ActualOutsideTemperature_plugin implements \JarvisPHP\Core\JarvisPluginInterface {
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 4;
    
    var $place = "Rimini, Italy";
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        
        $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
        $yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$this->place.'") and u="c"';
        $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
        
        // Make call with cURL
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        $json = curl_exec($session);
        // Convert JSON to PHP object
        $phpObj =  json_decode($json);
        $answer = sprintf(JarvisLanguage::translate('actual_temperature_outside_is',get_called_class()),$phpObj->query->results->channel->item->condition->temp, $phpObj->query->results->channel->atmosphere->humidity);
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
