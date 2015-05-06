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
        //Verify if there is an active plugin
        if(JarvisSession::sessionInProgress()) {
            $plugin_class = JarvisSession::getActivePlugin();
            $plugin = new $plugin_class();
            $plugin->answer($command);
        }
        else {
            $max_priority_found=-9999;
            $choosen_plugin = null;
            //Cycling plugins
            foreach(JarvisPHP::$active_plugins as $plugin_class) {
               $plugin = new $plugin_class();
               if($plugin->isLikely($command)) {
                   if($plugin->getPriority() > $max_priority_found) {
                       $max_priority_found = $plugin->getPriority();
                       $choosen_plugin =& $plugin;
                   }
               }
            }
            if(!is_null($choosen_plugin)) {
                $choosen_plugin->answer($command);
            } else {
                JarvisPHP::getLogger()->warn('no plugin found for command: '.$command);
            }
        }
    }

    static function error_handler($error_level,$error_message,$error_file,$error_line,$error_context) {
        switch($error_level) {
            case E_USER_ERROR:
            case E_USER_WARNING:     
                JarvisPHP::getLogger()->error("[$errno] $errstr in $error_file on line $error_line");
        }

    }
    
} //JarvisPHP