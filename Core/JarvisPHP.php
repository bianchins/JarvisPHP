<?php

namespace JarvisPHP\Core;

/**
 * JarvisPHP Main Class
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisPHP {
           
    private static $active_plugins = array();
    
    public static $slim = null;
    
    public static $TTS_name = null;

    /**
     * Bootstrap JarvisPHP core
     */
    public static function bootstrap() {       
        //Autoloading classes
        spl_autoload_register(function($className)
        {
            //Obtain the pure class name
            $pureClassName = JarvisPHP::getRealClassName($className);
            //Build the path
            $namespace = JarvisPHP::getNameSpace($className);
            if(file_exists($namespace.'/'.$pureClassName.'.php')) {
                include_once($namespace.'/'.$pureClassName.'.php');
            }
        });
        //Configure the Logger
        \Logger::configure('config/log4php.xml');
        
        //Load config
        require 'config/Jarvis.php';
        
        //Start session
        JarvisSession::start();
        
        //Core localization
        JarvisLanguage::loadCoreTranslation();
        JarvisPHP::getLogger()->debug('Loading "'._LANGUAGE.'" language file');
        
        //Routing
        JarvisPHP::$slim = new \Slim\Slim(array('debug' => false));
                
        //POST /answer route
        JarvisPHP::$slim->post('/answer/', function () {
            //Detect if the request forces a TTS
            $ttsFromPostRequest = JarvisPHP::$slim->request->post('tts');
            if(!empty($ttsFromPostRequest) && file_exists('Speakers\\'.JarvisPHP::$slim->request->post('tts').'.php')) {
                $forcedTTS = JarvisPHP::$slim->request->post('tts');
            } else {
                $forcedTTS = _JARVIS_TTS;
            }

            JarvisPHP::elaborateCommand(mb_strtolower(JarvisPHP::$slim->request->post('command'), 'UTF-8'), $forcedTTS);
        });

        //Slim Framework Custom Error handler
        JarvisPHP::$slim->error(function (\Exception $e) {
            JarvisPHP::getLogger()->error('Code: '.$e->getCode().' - '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().'');
        });
        
        JarvisPHP::$slim->run();
    }
    
    /**
     * Get Log4php object
     * @return Logger
     */
    public static function getLogger() {
        return \Logger::getLogger('JarvisPHP');
    }
    
    /**
     * Load a plugin
     * @param string $plugin
     */
    public static function loadPlugin($plugin) {        
        array_push(JarvisPHP::$active_plugins, 'JarvisPHP\Plugins\\'.$plugin.'\\'.$plugin);
    }
    
    /**
     * Returns Jarvis Language setting
     * @return string
     */
    public static function getLanguage() {
        return _LANGUAGE;
    }
    
    /**
     * Parse the command and execute the plugin
     * @param string $command
     */
    public static function elaborateCommand($command, $forcedTTS) {
                
        JarvisPHP::$TTS_name = $forcedTTS;

        //Jarvis tries to understand if the magic words that stop the session were pronounced
        if(preg_match(JarvisLanguage::translate('preg_match_magic_words_to_stop_session'),$command)) {
            JarvisSession::terminate();
        }

        //Verify if there is an active plugin and the command session timeout
        if(JarvisSession::sessionInProgress() && (time() < (JarvisSession::get('last_command_timestamp')+_COMMAND_SESSION_TIMEOUT))) {
            JarvisPHP::getLogger()->debug('Detected active session: '.JarvisSession::getActivePlugin() . ' - last command '.JarvisSession::get('last_command_timestamp').', now is '.time());
            $plugin_class = JarvisSession::getActivePlugin();
            //Load plugin's languages
            JarvisLanguage::loadPluginTranslation($plugin_class);
            $plugin = new $plugin_class();
            $plugin->answer($command);
        }
        else {
            //Clear all session variable
            JarvisSession::reset();
            JarvisPHP::getLogger()->debug('Active session not detected or expired');
            $max_priority_found=-9999;
            $choosen_plugin = null;
            //Cycling plugins
            foreach(JarvisPHP::$active_plugins as $plugin_class) {
               $plugin = new $plugin_class();
               //Load plugin's languages
               JarvisLanguage::loadPluginTranslation($plugin_class);
               if($plugin->isLikely($command)) {
                   JarvisPHP::getLogger()->debug('Maybe '.JarvisPHP::getRealClassName($plugin_class).', check priority');
                   if($plugin->getPriority() > $max_priority_found) {
                       $max_priority_found = $plugin->getPriority();
                       $choosen_plugin = $plugin;
                   }
               }
            }
            if(!is_null($choosen_plugin)) {
                JarvisPHP::getLogger()->debug('Choosen plugin: '.JarvisPHP::getRealClassName(get_class($choosen_plugin)));
                if($choosen_plugin->hasSession()) {
                    JarvisSession::setActivePlugin(get_class($choosen_plugin));
                }
                $choosen_plugin->answer($command);
            } else {
                if(preg_match(JarvisLanguage::translate('preg_match_magic_words_to_stop_session'),$command)) {
                    JarvisTTS::speak(JarvisLanguage::translate('response_to_magic_words_to_stop_session'));
                    $response = new \JarvisPHP\Core\JarvisResponse(JarvisLanguage::translate('response_to_magic_words_to_stop_session'));
                    $response->send();
                }
                else {
                    JarvisPHP::getLogger()->debug('No plugin found for command: '.$command);
                    JarvisTTS::speak(JarvisLanguage::translate('core_command_not_understand'));
                    $response = new \JarvisPHP\Core\JarvisResponse(JarvisLanguage::translate('core_command_not_understand'));
                    $response->send();
                }
            }
        }
        //Update last command timestamp
        JarvisSession::set('last_command_timestamp', time());
    }
    
    public static function getRealClassName($fullClassName) {
        //Explode class name
        $classNameArray = explode('\\',$fullClassName);
        //Obtain the pure class name
        return end($classNameArray);
    }
    
    public static function getNameSpace($fullClassName) {
       //Explode class name
        $classNameArray = explode('\\',$fullClassName);
        //Remove the pure class name
        array_pop($classNameArray);
        //Remove the JarvisPHP main namespace
        array_shift($classNameArray); 
        //Build the path
        $namespace = implode('/', $classNameArray);
        return $namespace;
    }
    
} //JarvisPHP