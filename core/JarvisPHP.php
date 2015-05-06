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
        
        //Error handler
        set_error_handler('JarvisPHP::error_handler');
        
        //Autoloading classes
        spl_autoload_register(function($className)
        {
            $namespace=str_replace("\\","/",__NAMESPACE__);
            $className=str_replace("\\","/",$className);
            $plugins="plugins/".(empty($namespace) ? "" : $namespace."/")."{$className}.php";
            $core="core/".(empty($namespace) ? "" : $namespace."/")."{$className}.php";
            @include_once($plugins);
            @include_once($core);
        });
        //Configure the Logger
        Logger::configure('config/log4php.xml');
        
        //Load config
        require 'config/Jarvis.php';
        
        //Session
        JarvisSession::start();
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
     * Parse the command and execute the plugin
     * @param string $command
     */
    static function elaborateCommand($command) {
                
        //Verify if there is an active plugin and the command session timeout
        if(JarvisSession::sessionInProgress() && (time() < (JarvisSession::get('last_command_timestamp')+_COMMAND_SESSION_TIMEOUT))) {
            JarvisPHP::getLogger()->debug('Detected active session: '.JarvisSession::getActivePlugin() . ' - last command '.JarvisSession::get('last_command_timestamp').', now is '.time());
            $plugin_class = JarvisSession::getActivePlugin();
            $plugin = new $plugin_class();
            $plugin->answer($command);
        }
        else {
            JarvisPHP::getLogger()->debug('Active session not detected or expired');
            $max_priority_found=-9999;
            $choosen_plugin = null;
            //Cycling plugins
            foreach(JarvisPHP::$active_plugins as $plugin_class) {
               $plugin = new $plugin_class();
               if($plugin->isLikely($command)) {
                   JarvisPHP::getLogger()->debug('Maybe '.$plugin_class.', check priority');
                   if($plugin->getPriority() > $max_priority_found) {
                       $max_priority_found = $plugin->getPriority();
                       $choosen_plugin = $plugin;
                   }
               }
            }
            JarvisPHP::getLogger()->debug('Choosen plugin: '.get_class($choosen_plugin));
            if(!is_null($choosen_plugin)) {
                if($choosen_plugin->hasSession()) {
                    JarvisSession::setActivePlugin(get_class($choosen_plugin));
                }
                $choosen_plugin->answer($command);
            } else {
                JarvisPHP::getLogger()->warn('No plugin found for command: '.$command);
            }
        }
        //Update last command timestamp
        JarvisSession::set('last_command_timestamp', time());
    }

    static function error_handler($error_level,$error_message,$error_file,$error_line,$error_context) {
        switch($error_level) {
            case E_USER_ERROR:
            case E_USER_WARNING:     
                JarvisPHP::getLogger()->error("[$errno] $errstr in $error_file on line $error_line");
        }

    }
    
} //JarvisPHP