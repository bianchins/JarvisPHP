<?php 

use \JarvisPHP\Core\JarvisPHP;

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
JarvisPHP::loadPlugin('Weather_plugin');
JarvisPHP::loadPlugin('Movie_plugin');
JarvisPHP::loadPlugin('Gcalendar_plugin');

//Initialize JarvisPHP
JarvisPHP::bootstrap();