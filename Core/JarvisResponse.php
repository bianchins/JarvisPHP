<?php

namespace JarvisPHP\Core;

/**
 * JarvisResponse
 * the json response of JarvisPHP
 * 
 * @author Stefano Bianchini
 */
class JarvisResponse {
    private $success = false;
    private $answer = '';
    private $choosen_plugin = 'none';
    
    public function __construct($answer, $choosen_plugin='none', $success=false) {
        $this->answer = $answer;
        $this->choosen_plugin = $choosen_plugin;
        $this->success = $success;
    }
    
    public function send() {
        JarvisPHP::$slim->response->headers->set('Content-Type', 'application/json');
        $response = new \stdClass();
        $response->answer = $this->answer;
        $response->success = $this->success;
        $response->choosen_plugin = $this->choosen_plugin;
        JarvisPHP::$slim->response->setBody(json_encode($response));
    }
}
