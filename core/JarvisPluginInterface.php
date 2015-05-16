<?php

namespace JarvisPHP\core;

/**
 * Public interface for plugins
 * @author Stefano Bianchini
 * @website http://www.stefanobianchini.net
 */
interface JarvisPluginInterface
{
    /**
     * The behaviour of the plugin
     * @param string $command
     */
    public function answer($command);
    
    /**
     * Get the priority of the plugin
     * @return int
     */
    public function getPriority();
    
    /**
     * Is it the right plugin for the command?
     * @param string $command
     * @return boolean
     */
    public function isLikely($command);
    
    /**
     * The plugin does need a session?
     * @return boolean
     */
    public function hasSession();
}
