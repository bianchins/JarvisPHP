<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

JarvisPHP::initialize();

$class = "Echo_plugin";

$plugin = new $class($JarvisPHP);

$plugin->answer();
