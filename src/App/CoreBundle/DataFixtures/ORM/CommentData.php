<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use App\CoreBundle\Entity\Comment;

class CommentData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($manager) {
        $comment = new Comment();
        $comment->setComment('Removed items: \'register_globals\', \'safe_mode\', \'allow_call_time_pass_reference\', session_register(), session_unregister() and session_is_registered() functions

New features: traits, array dereferencing, closure $this support, JsonSerializable interface, "<?=" no longer requires \'short_open_tag\' set to ON');
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
