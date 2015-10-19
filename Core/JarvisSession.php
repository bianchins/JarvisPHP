<?php

namespace JarvisPHP\Core;

/**
 * Class for manage session (context)
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
class JarvisSession {
    
    /**
     * Begin session
     */
    public static function start() {
        session_start();
    }
    
    /**
     * Set active plugin to session
     * @param string $pluginName
     */
    public static function setActivePlugin($pluginName) {
        $_SESSION['active_plugin'] = $pluginName;
    }
    
    /**
     * Get active plugin
     * @return string
     */
    public static function getActivePlugin() {
        return $_SESSION['active_plugin'];
    }
    
    /**
     * Check if a session is in progress
     * @return boolena
     */
    public static function sessionInProgress() {
        return !empty($_SESSION['active_plugin']);
    }
    
    /**
     * Ends the session
     */
    public static function terminate() {
        session_unset('active_plugin');
        session_destroy();
    }
    
    /**
     * Set a variable to session
     * @param string $name
     * @param string $value
     */
    public static function set($name, $value) {
        $_SESSION[$name] = $value;
    }
    
    /**
     * Get a variable from session
     * @param string $name
     * @return string
     */
    public static function get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
    }
    
    /**
     * Reset session
     */
    public static function reset() {
        session_unset();
    }
}