<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

JarvisPHP::bootstrap();
JarvisPHP::loadPlugin('Echo_plugin');

JarvisPHP::elaborateCommand("Just echo this string");
JarvisPHP::elaborateCommand("load echo");
JarvisPHP::elaborateCommand("Just echo my voice");
JarvisPHP::elaborateCommand("nothing");