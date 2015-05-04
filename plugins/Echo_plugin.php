<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Echo_plugin {
    
    function __construct(&$db) {
        $this->db = $db;
    }
    
    function answer() {
        echo 'testing';
    }
    
}
