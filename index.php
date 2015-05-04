<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

JarvisPHP::initialize();
//Testing
$_SESSION['active_plugin'] = 'Echo_plugin';

JarvisPHP::elaborateCommand("Just echo this string");