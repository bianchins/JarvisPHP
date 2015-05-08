<?php

/**
 * JarvisPHP Main Class
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisPHP {
       
    static $tokens = array();
    
    static $active_plugins = array();
    
    static function bootstrap() {
        
        //Autoloading classes
        spl_autoload_register(function($className)
        {
            $plugins="plugins/{$className}/{$className}.php";
            $core="core/{$className}.php";
            $speakers="speakers/{$className}.php";
            @include_once($plugins);
            @include_once($core);
            @include_once($speakers);
        });
        //Configure the Logger
        Logger::configure('config/log4php.xml');
        
        //Load config
        require 'config/Jarvis.php';
        
        //Session
        JarvisSession::start();
        
        //Core localization
        JarvisLanguage::loadCoreTranslation();
        JarvisPHP::getLogger()->debug('Loading "'._LANGUAGE.'" language file');
    }
        
    static function getLogger() {
        return Logger::getLogger('JarvisPHP');
    }
    
    /**
     * Load a plugin
     * @param string $plugin
     */
    static function loadPlugin($plugin) {        
        array_push(JarvisPHP::$active_plugins, $plugin);
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
                   JarvisPHP::getLogger()->debug('Maybe '.$plugin_class.', check priority');
                   if($plugin->getPriority() > $max_priority_found) {
                       $max_priority_found = $plugin->getPriority();
                       $choosen_plugin = $plugin;
                   }
               }
            }
            if(!is_null($choosen_plugin)) {
                JarvisPHP::getLogger()->debug('Choosen plugin: '.get_class($choosen_plugin));
                if($choosen_plugin->hasSession()) {
                    JarvisSession::setActivePlugin(get_class($choosen_plugin));
                }
                $choosen_plugin->answer($command);
            } else {
                JarvisPHP::getLogger()->debug('No plugin found for command: '.$command);
                JarvisTTS::speak(JarvisLanguage::translate('core_command_not_understand'));
            }
        }
        //Update last command timestamp
        JarvisSession::set('last_command_timestamp', time());
    }
    
} //JarvisPHP