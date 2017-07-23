<?php

namespace API;

use Goutte;

class GoutteClient extends \PHPUnit\Framework\TestCase
{
    private $clientGoutte;
    
    private $endpoint;

    public function setUp()
    {
        $this->clientGoutte = new Goutte\Client();
        $this->endpoint = "http://localhost:8080";
    }
    public function testGetStatusTextHtml()
    {
        $this->clientGoutte->setHeader("Accept", "text/html");
        $this->clientGoutte->request('GET', sprintf('%s/statuses', $this->endpoint));
        $response = $this->clientGoutte->getResponse();
        
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html;charset=UTF-8', $response->getHeader('Content-Type'));
    }
}