<?php
namespace App\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = $this->createClient();

        $crawler = $client->request('GET', '/php-symfony/1');
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        // $crawler = $client->request('GET', '/2-symfony');
        // $this->assertTrue($client->getResponse()->isSuccessful());
        
        // $crawler = $client->request('GET', '/3-ddd');
        // $this->assertTrue($client->getResponse()->isSuccessful());        
    }
}
