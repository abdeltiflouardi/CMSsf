<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use App\CoreBundle\Entity\Comment;

class CommentData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($manager) {
        $comment = new Comment();
        $comment->setComment('programmes en ligne de commande. PHP est un langage impératif disposant depuis la version 5 de fonctionnalités de modèle objet complètes');
        $comment->setPost($manager->merge($this->getReference('post_php')));
        $comment->setUser($manager->merge($this->getReference('user')));
        
        $manager->persist($comment);
        $manager->flush();

        $this->addReference('comment', $comment);
    }

    public function getOrder() {
        return 6; // the order in which fixtures will be loaded
    }

}
