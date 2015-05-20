<?php

namespace JarvisPHP\Core;

/**
 * JarvisLanguage
 *
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisLanguage {
    
    private static $data = array();
    
    /**
     * Load core translations
     */
    public static function loadCoreTranslation() {
        $_lang = array();
        JarvisLanguage::$data['core'] = array();
        //Loading JarvisPHP Core language
        if(file_exists('language/JarvisPHP_'._LANGUAGE.'.php')) {
            require 'language/JarvisPHP_'._LANGUAGE.'.php';
            JarvisLanguage::$data['core'] = array_merge(JarvisLanguage::$data['core'], $_lang);
        }
    }
    
    /**
     * Load the translations of a plugin (plugin name)
     * @param string $plugin
     */
    public static function loadPluginTranslation($plugin) {
        $plugin_class = JarvisPHP::getRealClassName($plugin);
        $_lang = array();
        JarvisLanguage::$data[$plugin] = array();
        $language_file = 'Plugins/'.$plugin_class.'/language/'.$plugin_class."_"._LANGUAGE.'.php';

        //Check if translation file exists
        if(file_exists($language_file)) {
            require $language_file;
            JarvisLanguage::$data[$plugin] = array_merge(JarvisLanguage::$data[$plugin], $_lang);
        }
    }
    
    /**
     * Translate a string
     * @param string $text
     * @param string $plugin
     * @return string
     */
    public static function translate($text, $plugin='core') {
        if(isset(JarvisLanguage::$data[$plugin][$text])) {
            return JarvisLanguage::$data[$plugin][$text];
        } else {
            return $text;
        }
    }
    
}
