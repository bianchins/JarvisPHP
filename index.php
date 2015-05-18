<?php 

use \JarvisPHP\Core\JarvisPHP;

//Composer autoload
require 'vendor/autoload.php';
//JarvisPHP Core
require 'core/JarvisPHP.php';

//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');
JarvisPHP::loadPlugin('ActualOutsideTemperature_plugin');

//Initialize JarvisPHP
JarvisPHP::bootstrap();