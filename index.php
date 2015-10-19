<?php 

use \JarvisPHP\Core\JarvisPHP;

//error_reporting(E_ALL & ~E_NOTICE);

//Composer autoload
require 'vendor/autoload.php';
//JarvisPHP Core
require 'Core/JarvisPHP.php';

//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');
JarvisPHP::loadPlugin('ActualOutsideTemperature_plugin');
JarvisPHP::loadPlugin('InformationOn_plugin');
JarvisPHP::loadPlugin('Hello_plugin');

//Initialize JarvisPHP
JarvisPHP::bootstrap();