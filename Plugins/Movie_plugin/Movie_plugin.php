<?php

namespace JarvisPHP\Plugins\Movie_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;

/**
 * A Movie plugin for reading a movie overview from TheMovieDb
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Movie_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
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

        //Understand the movie
        preg_match(JarvisLanguage::translate('preg_match_activate_plugin',get_called_class()), $command, $matches);

        $movie_title = end($matches);

        $_THEMOVIEDB_API_KEY = '';

        //Load API key from json config
        if(file_exists('Plugins/Movie_plugin/api-key.json')) {
            //Create your own api key and put it in api-key.json
            // like {"themoviedb_key": "<your-api-key>"}
            $json_config = json_decode(file_get_contents('Plugins/Movie_plugin/api-key.json'));
            $_THEMOVIEDB_API_KEY = $json_config->themoviedb_key;
        }

        $token  = new \Tmdb\ApiToken($_THEMOVIEDB_API_KEY);
        $client = new \Tmdb\Client($token, ['secure' => false]);

        $result = $client->getSearchApi()->searchMovies($movie_title, ['language'=>_LANGUAGE, 'page'=>1]);

        if(count($result['results'])>0) {
            $answer = ($result['results'][0]['overview']) ? ($result['results'][0]['overview']) : JarvisLanguage::translate('no_results',get_called_class());
        } else {
            //No results
            $answer = JarvisLanguage::translate('no_results',get_called_class());
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
        return false;
    }
}
