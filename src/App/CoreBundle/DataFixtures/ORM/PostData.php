<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\CoreBundle\Entity\Post;

class PostData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($manager) {
        $post_php = new Post();
        $post_php->setUser($manager->merge($this->getReference('user')));
        $post_php->setCategory($manager->merge($this->getReference('category_php')));
        $post_php->setTitle('PHP - Introduction');
        $post_php->setBody('PHP (sigle de PHP: Hypertext Preprocessor3) est un langage de scripts libre4 principalement utilisé pour produire des pages Web dynamiques via un serveur HTTP3, mais pouvant également fonctionner comme n\'importe quel langage interprété de façon locale, en exécutant les programmes en ligne de commande. PHP est un langage impératif disposant depuis la version 5 de fonctionnalités de modèle objet complètes5. En raison de la richesse de sa bibliothèque, on désigne parfois PHP comme une plate-forme plus qu\'un simple langage.');
        $post_php->addTag($manager->merge($this->getReference('tag_php')));

        $post_symfony = new Post();
        $post_symfony->setUser($manager->merge($this->getReference('user')));
        $post_symfony->setCategory($manager->merge($this->getReference('category_symfony')));
        $post_symfony->setTitle('Symfony - Introduction');
        $post_symfony->setBody('Symfony est un framework MVC libre écrit en PHP 5. En tant que framework, il facilite et accélère le développement de sites et d\'applications Internet et Intranet.');
        $post_symfony->addTag($manager->merge($this->getReference('tag_php')));
        $post_symfony->addTag($manager->merge($this->getReference('tag_symfony')));

        $manager->persist($post_php);
        $manager->persist($post_symfony);
        $manager->flush();
    }

    public function getOrder() {
        return 5; // the order in which fixtures will be loaded
    }

}
