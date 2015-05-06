<?php

interface JarvisPluginInterface
{
    public function answer($command);
    public function getPriority();
    public function isLikely($command);
    public function hasSession();
}
