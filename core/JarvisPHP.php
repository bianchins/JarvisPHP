<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class JarvisPHP {
    
    static function initialize() {
        //Autoloading classes
        spl_autoload_register(function($className)
        {
            $namespace=str_replace("\\","/",__NAMESPACE__);
            $className=str_replace("\\","/",$className);
            $class="/plugins/".(empty($namespace) ? "" : $namespace."/")."{$className}.php";
            include_once($class);
        });
        
        //Session
        session_start();
    }
    
    /**
     * Parse the command and execute the plugin
     * @param type $command
     */
    static function elaborateCommand($command) {
        //Verify if there is an active plugin
        if(!empty($_SESSION['active_plugin'])) {
            $plugin_class = $_SESSION['active_plugin'];
            $plugin = new $plugin_class();
            $plugin->answer($command);
        }
        else {
            //TODO ntltools parsing
            
        }
    }

    
} //JarvisPHP