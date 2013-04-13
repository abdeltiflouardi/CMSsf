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
        $this->assertGreaterThan(
            0,
            $crawler->filter('div:contains("Bad credentials")')->count()
        );

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

        // 9 - Forgotten Password (validate page)
        $crawler = $client->request('GET', '/signin');
        $link = $crawler->selectLink('forgotten password')->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('label:contains("Email")')->count() > 0);

        // 10 - Forgotten Password (submit empty data)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('li:contains("This value should not be blank")')->count() > 0);

        // 11 - Forgotten Password (submit invalidate email)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array('forgottenpassword[email]' => 'test'));
        $this->assertTrue($crawler->filter('li:contains("This value is not a valid email address")')->count() > 0);

        // 12 - Forgotten Password (submit invalidate email)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form, array('forgottenpassword[email]' => 'test@test.tld'));
        $this->assertTrue($crawler->filter('div:contains("Email not found")')->count() > 0);

        // 13 - Activate account (invalid email)
        $crawler = $client->request('GET', '/activate/test@test.tld');
        $this->assertTrue($crawler->filter('div:contains("Account not exists")')->count() > 0);

        // 14 - Activate account (valid email)
        $crawler = $client->request('GET', '/activate/user@dom.tld');
        $this->assertTrue($crawler->filter('div:contains("Profile activated")')->count() > 0);

        // 15 - InitPassword (valid email)
        $crawler = $client->request('GET', '/init-password/user@dom.tld');
        $this->assertTrue($crawler->filter('label:contains("Old password")')->count() > 0);

        // 16 - InitPassword (Sumbit empty data)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($crawler->filter('li:contains("This value should not be blank")')->count() > 0);

        // 17 - InitPassword (Submit old password error)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit(
            $form,
            array(
                'initpassword[oldPassword]' => 'testtest',
                'initpassword[newPassword][New_Password]' => 'testtest',
                'initpassword[newPassword][Confirm]' => 'testtest',
            )
        );
        $this->assertTrue($crawler->filter('div:contains("Old password not valid")')->count() > 0);

        // 18 - InitPassword (Submit short password)
        $form = $crawler->selectButton('Send')->form();
        $crawler = $client->submit(
            $form,
            array(
                'initpassword[oldPassword]' => 'test',
                'initpassword[newPassword][New_Password]' => 'test',
                'initpassword[newPassword][Confirm]' => 'test',
            )
        );
        $this->assertTrue($crawler->filter('li:contains("This value is too short.")')->count() > 0);

        // 19 - InitPassword (invalid email)
        $crawler = $client->request('GET', '/init-password/test@test.tld');
        $this->assertTrue($crawler->filter('div:contains("User not found")')->count() > 0);

        // 20 - Profile
        $crawler = $client->request('GET', '/signin');
        $form = $crawler->selectButton('Signin')->form();
        $crawler = $client->submit($form, array('signin[login]' => 'admin', 'signin[password]' => 'administrator'));
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('small:contains("(admin)")')->count() > 0);

        // 21 - Edit profile
        $link = $crawler->selectLink('Edit profile')->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('label:contains("Email")')->count() > 0);

        // 22 - My comments
        $link = $crawler->selectLink('My comments')->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('th:contains("Id")')->count() > 0);

        // 23 - My posts
        $link = $crawler->selectLink('My posts')->link();
        $crawler = $client->click($link);
        $this->assertTrue($crawler->filter('th:contains("Id")')->count() > 0);

        // 24 - Logout
        $link = $crawler->filter('a[href="/logout"]')->eq(0)->link();
        $crawler = $client->click($link);
        $crawler = $client->followRedirect();
        $this->assertTrue($crawler->filter('a:contains("Connexion")')->count() > 0);
    }
}
