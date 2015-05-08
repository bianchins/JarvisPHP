<?php

/**
 * JarvisLanguage
 *
 * @author Stefano Bianchini
 */
class JarvisLanguage {
    
    private static $data = array();
    
    public static function loadCoreTranslation() {
        $_lang = array();
        JarvisLanguage::$data['core'] = array();
        //Loading JarvisPHP Core language
        require 'language/JarvisPHP_'._LANGUAGE.'.php';
        JarvisLanguage::$data['core'] = array_merge(JarvisLanguage::$data['core'], $_lang);
    }
    
    public static function loadPluginTranslation($plugin) {
        $_lang = array();
        JarvisLanguage::$data[$plugin] = array();
        $language_file = 'plugins/'.$plugin.'/language/'.$plugin."_"._LANGUAGE.'.php';
        //Check if translation file exists
        if(file_exists($language_file)) {
            require $language_file;
            JarvisLanguage::$data[$plugin] = array_merge(JarvisLanguage::$data[$plugin], $_lang);
        }
    }
    
    public static function translate($text, $plugin='core') {
        if(isset(JarvisLanguage::$data[$plugin][$text])) {
            return JarvisLanguage::$data[$plugin][$text];
        } else {
            return $text;
        }
    }
    
}
