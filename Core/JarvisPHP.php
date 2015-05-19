<?php

namespace JarvisPHP\core;

/**
 * JarvisPHP Main Class
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisPHP {
       
    static $tokens = array();
    
    static $active_plugins = array();
    
    static $slim = null;
        
    /**
     * Bootstrap JarvisPHP core
     */
    static function bootstrap() {       
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
            JarvisPHP::elaborateCommand(JarvisPHP::$slim->request->post('command'));
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
    static function getLogger() {
        return \Logger::getLogger('JarvisPHP');
    }
    
    /**
     * Load a plugin
     * @param string $plugin
     */
    static function loadPlugin($plugin) {        
        array_push(JarvisPHP::$active_plugins, 'JarvisPHP\Plugins\\'.$plugin.'\\'.$plugin);
    }
    
    /**
     * Returns Jarvis Language setting
     * @return string
     */
    static function getLanguage() {
        return _LANGUAGE;
    }
    
    /**
     * Parse the command and execute the plugin
     * @param string $command
     */
    static function elaborateCommand($command) {
                
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
                JarvisPHP::getLogger()->debug('No plugin found for command: '.$command);
                JarvisTTS::speak(JarvisLanguage::translate('core_command_not_understand'));
                $response = new \JarvisPHP\Core\JarvisResponse(JarvisLanguage::translate('core_command_not_understand'));
                $response->send();
            }
        }
        //Update last command timestamp
        JarvisSession::set('last_command_timestamp', time());
    }
    
    static function getRealClassName($fullClassName) {
        //Explode class name
        $classNameArray = explode('\\',$fullClassName);
        //Obtain the pure class name
        return end($classNameArray);
    }
    
    static function getNameSpace($fullClassName) {
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