<?php 

use \JarvisPHP\Core\JarvisPHP;

//Composer autoload
require 'vendor/autoload.php';
//JarvisPHP Core
require 'core/JarvisPHP.php';

//Initialize JarvisPHP
JarvisPHP::bootstrap();
//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');
JarvisPHP::loadPlugin('ActualOutsideTemperature_plugin');

//Here we go
$app = new \Slim\Slim(array('debug' => false));
//POST /answer route
$app->post('/answer/', function () use ($app) {
    JarvisPHP::elaborateCommand($app->request->post('command'));
});

//Slim Framework Custom Error handler
$app->error(function (\Exception $e) use ($app) {
    JarvisPHP::getLogger()->error('Code: '.$e->getCode().' - '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().'');
});

$app->run();