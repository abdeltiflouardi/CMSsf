<?php
namespace App\WebBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        // 1 - Home
        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());
        
        // 2 - Category
        $crawler = $client->request('GET', '/web/2');
        $link = $crawler->filter('a:contains("Web")')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // 3 - Sub-category
        $crawler = $client->request('GET', '/web/2');
        $link = $crawler->filter('a:contains("PHP")')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // 4 - Slug
        $crawler = $client->request('GET', '/php/5');
        $link = $crawler->filter('a[href="/tag/1-php"]')->eq(1)->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // 5 - Search
        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Search')->form();
        $crawler = $client->submit($form, array('q' => 'php'));
        $this->assertTrue($crawler->filter('div:contains("Created by")')->count() > 0);
        
        // 6 - Signin
        $crawler = $client->request('GET', '/signin');
        $form = $crawler->selectButton('Signin')->form();
        $crawler = $client->submit($form, array('signin[login]' => 'admin'));
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('div:contains("The presented password cannot be empty.")')->count() > 0);
        
        // 7 - Signin
        $crawler = $client->request('GET', '/signin');
        $form = $crawler->selectButton('Signin')->form();
        $crawler = $client->submit($form, array('signin[login]' => ''));
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('div:contains("Bad credentials")')->count() > 0);        
        
        // 8 - Signup
        $crawler = $client->request('GET', '/signin');
        $form = $crawler->selectButton('Signup')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('li:contains("This value should not be blank")')->count() > 0);     
        
        // 9 - Forgotten Password (page)
        $crawler = $client->request('GET', '/signin');
        $link = $crawler->selectLink('forgotten password')->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('label:contains("Email")')->count() > 0);
        
        // 10 - Forgotten Password (submit)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('li:contains("This value should not be blank")')->count() > 0);        
        
        /**
         *  @TODO list
         *     - Validate
         *     - InitPassword
         *     - Profile
         *     - My Posts +(Edit/Delete)
         *     - My Comments +(Edit/Delete)
         *     - Sign out
         */
    }
}
