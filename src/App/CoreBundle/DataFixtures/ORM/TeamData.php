<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\Team;

class TeamData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $team = new Team();
        $team->setName('Administrators');
        $team->setRole('ROLE_ADMIN');

        $team_moderate = new Team();
        $team_moderate->setName('Moderators');
        $team_moderate->setRole('ROLE_MODERATE');

        $team_user = new Team();
        $team_user->setName('Users');
        $team_user->setRole('ROLE_USER');

        $manager->persist($team);
        $manager->persist($team_moderate);
        $manager->persist($team_user);

        $manager->flush();

        $this->addReference('team', $team);
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
