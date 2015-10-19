<?php

require('vendor/autoload.php');

/**
 * JarvisTest
 *
 * @author Stefano Bianchini
 */
class JarvisTest extends PHPUnit_Framework_TestCase {
    
    protected $client;

    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_url' => 'http://localhost/',
            'defaults' => ['exceptions' => false]
        ]);
    }
    public function testInfo_plugin()
    {
        $response = $this->client->post('answer', [
            'body' => [
                'command'    => 'who are you',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        $this->assertEquals($data['choosen_plugin'], 'Info_plugin');
    }
    
    public function testEcho_plugin()
    {
        $response = $this->client->post('answer', [
            'body' => [
                'command'    => 'just echo',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        $this->assertEquals($data['choosen_plugin'], 'Echo_plugin');
    }
    
    public function testActualOutsideTemperature_plugin()
    {
        $response = $this->client->post('answer', [
            'body' => [
                'command'    => 'please give me outside temperature',
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = $response->json();
        $this->assertEquals($data['choosen_plugin'], 'ActualOutsideTemperature_plugin');
    }
}