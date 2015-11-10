<?php

namespace JarvisPHP\Plugins\Weather_plugin;

use JarvisPHP\Core\JarvisSession;
use JarvisPHP\Core\JarvisPHP;
use JarvisPHP\Core\JarvisLanguage;
use JarvisPHP\Core\JarvisTTS;
//OpenWeatherMap
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\Exception as OWMException;

/**
 * A Weather plugin for today / tomorrow forecast
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class Weather_plugin implements \JarvisPHP\Core\JarvisPluginInterface{
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
        
        $owm = new OpenWeatherMap();

        $tomorrow = new \DateTime();
        $tomorrow->modify('+1 day');

        $_OPENWEATHERMAP_API_KEY = '';

        //Load API key from json config
        if(file_exists('Plugins/Weather_plugin/api-key.json')) {
            //Create your own api key and put it in api-key.json
            $json_config = json_decode(file_get_contents('Plugins/Weather_plugin/api-key.json'));
            $_OPENWEATHERMAP_API_KEY = $json_config->openweathermap_key;
        }

        try {
            $forecast = $owm->getWeatherForecast('Rimini', 'metric', _LANGUAGE, $_OPENWEATHERMAP_API_KEY,2);
        } catch(OWMException $e) {
            JarvisPHP::getLogger()->error('OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
            $answer = JarvisLanguage::translate('weather_error',get_called_class());            
        } catch(\Exception $e) {
            JarvisPHP::getLogger()->error('General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').');
            $answer = JarvisLanguage::translate('weather_error',get_called_class());
        }

        if(preg_match(JarvisLanguage::translate('preg_match_today',get_called_class()), $command)) {
            $answer = JarvisLanguage::translate('forecast_for_today',get_called_class());
            foreach ($forecast as $weather) {
                if($weather->time->day->format('d.m.Y')==date('d.m.Y')) {
                    $answer.=JarvisLanguage::translate('from',get_called_class()) . " " .$weather->time->from->format('H:i') . " ". JarvisLanguage::translate('to',get_called_class()) . " " . $weather->time->to->format('H:i').": ";
                    $answer.=$weather->weather->description." ".sprintf(JarvisLanguage::translate('temperature',get_called_class()),trim(str_replace('&deg;C','',$weather->temperature)));
                }
            }
        } else if(preg_match(JarvisLanguage::translate('preg_match_tomorrow',get_called_class()), $command)) {
            $answer = JarvisLanguage::translate('forecast_for_tomorrow',get_called_class());
            foreach ($forecast as $weather) {
                if($weather->time->day->format('d.m.Y')==$tomorrow->format('d.m.Y')) {
                    $answer.=JarvisLanguage::translate('from',get_called_class()) . " " .$weather->time->from->format('H:i') . " ". JarvisLanguage::translate('to',get_called_class()) . " " . $weather->time->to->format('H:i').": ";
                    $answer.=$weather->weather->description." ".sprintf(JarvisLanguage::translate('temperature',get_called_class()),trim(str_replace('&deg;C','',$weather->temperature)));
                }
            }
        } else {
            $answer = JarvisLanguage::translate('not_understand_weather_day',get_called_class());
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
