<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\Tag;

class TagData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {
        $tag_php = new Tag();
        $tag_php->setName('php');

        $tag_symfony = new Tag();
        $tag_symfony->setName('symfony');

        $manager->persist($tag_php);
        $manager->persist($tag_symfony);
        $manager->flush();

        $this->addReference('tag_php', $tag_php);
        $this->addReference('tag_symfony', $tag_symfony);
    }

    public function getOrder() {
        return 4; // the order in which fixtures will be loaded
    }

}
