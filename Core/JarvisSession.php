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
    static function start() {
        session_start();
    }
    
    /**
     * Set active plugin to session
     * @param string $pluginName
     */
    static function setActivePlugin($pluginName) {
        $_SESSION['active_plugin'] = $pluginName;
    }
    
    /**
     * Get active plugin
     * @return string
     */
    static function getActivePlugin() {
        return $_SESSION['active_plugin'];
    }
    
    /**
     * Check if a session is in progress
     * @return boolena
     */
    static function sessionInProgress() {
        return !empty($_SESSION['active_plugin']);
    }
    
    /**
     * Ends the session
     */
    static function terminate() {
        session_destroy();
    }
    
    /**
     * Set a variable to session
     * @param string $name
     * @param string $value
     */
    static function set($name, $value) {
        $_SESSION[$name] = $value;
    }
    
    /**
     * Get a variable from session
     * @param string $name
     * @return string
     */
    static function get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
    }
    
    /**
     * Reset session
     */
    static function reset() {
        session_unset();
    }
}