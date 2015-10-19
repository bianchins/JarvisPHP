<?php

namespace JarvisPHP\Plugins\InformationOn_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * A simple InformationOn plugin that uses Wikipedia Opensearch API
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 * Based on Wiki_plugin of UgoRaffaele https://github.com/UgoRaffaele/
 */
class InformationOn_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
    /**
     * Priority of plugin
     * @var int  
     */
    var $priority = 10;
    
    /**
     * the behaviour of plugin
     * @param string $command
     */
    function answer($command) {
        //Extract search term
        preg_match(JarvisLanguage::translate('preg_match_activate_plugin',get_called_class()), $command, $matches);
        $search_term = $matches[2];
		$wiki_query_url = _WIKI_URL . "?action=opensearch&search=" . urlencode($search_term) . "&format=xml&limit=5";
		$xml = simplexml_load_string(file_get_contents($wiki_query_url));
        $item_array = $xml->Section->Item;
        $answer = JarvisLanguage::translate('nothing_found',get_called_class());
        //Have i got results?
        if(count($item_array)>0) {
            foreach ($item_array as $item) {
                if (strlen($item->Description) > 0) {
                    $answer = sprintf(JarvisLanguage::translate('search_result_is',get_called_class()), $item->Description);
                    break;
                }
            }
        }
        //Making response
        $response = new \JarvisPHP\Core\JarvisResponse($answer, JarvisTTS::speak($answer), JarvisPHP::getRealClassName(get_called_class()), true);
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
