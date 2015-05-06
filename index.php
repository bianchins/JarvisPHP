<?php 
require 'vendor/autoload.php';
require 'core/JarvisPHP.php';

//Initialize JarvisPHP
JarvisPHP::bootstrap();
//Load plugins
JarvisPHP::loadPlugin('Echo_plugin');
JarvisPHP::loadPlugin('Info_plugin');

//Here we go
$app = new \Slim\Slim();
$app->post('/answer/', function () use ($app) {
    JarvisPHP::elaborateCommand($app->request->post('command'));
    echo file_get_contents('JarvisPHP.log');
});
$app->run();