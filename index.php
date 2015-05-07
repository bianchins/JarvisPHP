<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

//Initialize JarvisPHP
JarvisPHP::bootstrap();
//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');

//Here we go
$app = new \Slim\Slim(array('debug' => false));
$app->post('/answer/', function () use ($app) {
    JarvisPHP::elaborateCommand($app->request->post('command'));
    echo file_get_contents('JarvisPHP.log');
});

$app->error(function (\Exception $e) use ($app) {
    //Slim Framework Custom Error handler
    JarvisPHP::getLogger()->error('Code: '.$e->getCode().' - '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine().'');
});

$app->run();