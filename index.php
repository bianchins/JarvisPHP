<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

JarvisPHP::initialize();
JarvisPHP::enablePlugin('Echo_plugin');
//Testing
//$_SESSION['active_plugin'] = 'Echo_plugin';

JarvisPHP::elaborateCommand("Just echo this string");