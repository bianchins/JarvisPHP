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
    }

    
} //JarvisPHP