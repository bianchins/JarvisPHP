<?php

/**
 * JarvisLanguage
 *
 * @author Administrator
 */
class JarvisLanguage {
    
    private static $data = array();
    
    public static function loadCore() {
        $_lang = array();
        //Loading JarvisPHP Core language
        require 'language/JarvisPHP_'._LANGUAGE.'.php';
        JarvisLanguage::$data = array_merge(JarvisLanguage::$data, $_lang);
    }
    
    public static function loadPlugin($plugin) {
        $_lang = array();
        require 'plugins/'.$plugin.'/language/'.$plugin."_"._LANGUAGE.'.php';
        JarvisLanguage::$data = array_merge(JarvisLanguage::$data, $_lang);
    }
    
    public static function translate($text) {
        if(isset(JarvisLanguage::$data[$text])) {
            return JarvisLanguage::$data[$text];
        } else {
            return $text;
        }
    }
    
}
