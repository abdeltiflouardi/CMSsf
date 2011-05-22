<?php

namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\CoreBundle\Entity\Category;

class CategoryData extends AbstractFixture implements OrderedFixtureInterface {

    public function load($manager) {
        $category_prog = new Category();
	// $category_prog->setParent(0);
        $category_prog->setName('Programmation');
	$category_prog->setPosition(1);

        $category_web = new Category();
	// $category_web->setParent(0);
        $category_web->setName('Web');
	$category_web->setPosition(2);

	$category_soft = new Category();
	// $cateogry_soft->setParent(0);
	$category_soft->setName('Logiciels');
	$category_soft->setPosition(3);

	$category_hard = new Category();
	// $category_hard->setParent(0);
	$category_hard->setName('Matériels');
	$category_hard->setPosition(4);

        $manager->persist($category_prog);
        $manager->persist($category_web);
	$manager->persist($category_soft);
	$manager->persist($category_hard);
        $manager->flush();

	$category_php = new Category();
	$category_php->setParent($category_web);
	$category_php->setName('PHP');
	$category_php->setPosition(1);

	$category_symfony = new Category();
	$category_symfony->setParent($category_web);
	$category_symfony->setName('Symfony');
	$category_symfony->setPosition(2);

	$manager->persist($category_php);
	$manager->persist($category_symfony);
	$manager->flush();

        $this->addReference('category_php', $category_php);
        $this->addReference('category_symfony', $category_symfony);
    }

    public function getOrder() {
        return 1; // the order in which fixtures will be loaded
    }

}
