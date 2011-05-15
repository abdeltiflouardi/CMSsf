<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use App\CoreBundle\Entity\User;

class UserData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($manager) {
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword('admin');

        $encoder = new MessageDigestPasswordEncoder('md5');

        $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());

        $user->setPassword($password);
        $user->setTeam($manager->merge($this->getReference('team')));
        $manager->persist($user);
        $manager->flush();

        $this->addReference('user', $user);
    }

    public function getOrder() {
        return 3; // the order in which fixtures will be loaded
    }

}
