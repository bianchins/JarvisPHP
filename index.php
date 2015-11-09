<?php 

use \JarvisPHP\Core\JarvisPHP;

//Composer autoload
require 'vendor/autoload.php';
//JarvisPHP Core
require 'Core/JarvisPHP.php';

//Define JarvisPHP Root Path
define('_JARVISPHP_ROOT_PATH', dirname(__FILE__));

//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');
JarvisPHP::loadPlugin('ActualOutsideTemperature_plugin');
JarvisPHP::loadPlugin('InformationOn_plugin');
JarvisPHP::loadPlugin('Hello_plugin');
JarvisPHP::loadPlugin('Weather_plugin');
JarvisPHP::loadPlugin('Movie_plugin');
JarvisPHP::loadPlugin('Gcalendar_plugin');
JarvisPHP::loadPlugin('Wemo_plugin');
JarvisPHP::loadPlugin('RaspPIVolume_plugin');

//Initialize JarvisPHP
JarvisPHP::bootstrap();