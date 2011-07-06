<?php
namespace App\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // Home
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        // Category
        $crawler = $client->request('GET', '/web/2');
        $link = $crawler->filter('a:contains("Web")')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // Sub-category
        $crawler = $client->request('GET', '/web/2');
        $link = $crawler->filter('a:contains("PHP")')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // Slug
        $crawler = $client->request('GET', '/php/5');
        $link = $crawler->filter('a[href="/tag/1-php"]')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);        
    }
}
