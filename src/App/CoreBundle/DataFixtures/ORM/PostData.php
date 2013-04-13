<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\Post;

class PostData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $post_php = new Post();
        $post_php->setUser($manager->merge($this->getReference('user')));
        $post_php->setCategory($manager->merge($this->getReference('category_php')));
        $post_php->setTitle('PHP - Introduction');
        $post_php->setBody(
            'PHP is a general-purpose scripting language originally designed for web development to produce dynamic web pages. For this purpose, PHP code is embedded into the HTML source document and interpreted by a web server with a PHP processor module, which generates the web page document. It also has evolved to include a command-line interface capability and can be used in standalone graphical applications.[2] PHP can be deployed on most web servers and as a standalone interpreter, on almost every operating system and platform free of charge.[3] A competitor to Microsoft\'s Active Server Pages (ASP) server-side script engine[4] and similar languages, PHP is installed on more than 20 million websites and 1 million web servers.[5]'
        );
        $post_php->addTag($manager->merge($this->getReference('tag_php')));

        $post_symfony = new Post();
        $post_symfony->setUser($manager->merge($this->getReference('user')));
        $post_symfony->setCategory($manager->merge($this->getReference('category_symfony')));
        $post_symfony->setTitle('Symfony - Introduction');
        $post_symfony->setBody(
            'Symfony is a web application framework written in PHP which follows the model-view-controller (MVC) paradigm. Released under the MIT license, Symfony is free software. The symfony-project.com website launched on October 18, 2005.[1]

Symfony should not be confused with Symphony CMS, the Open Source XML/XSLT content management system.'
        );
        $post_symfony->addTag($manager->merge($this->getReference('tag_php')));
        $post_symfony->addTag($manager->merge($this->getReference('tag_symfony')));

        $manager->persist($post_php);
        $manager->persist($post_symfony);
        $manager->flush();

        $this->addReference('post_php', $post_php);
        $this->addReference('post_symfony', $post_symfony);
    }

    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}
