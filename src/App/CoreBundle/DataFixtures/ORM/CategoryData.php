<?php
namespace App\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\CoreBundle\Entity\Category;

class CategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load($manager)
    {
        $category_php = new Category();
        $category_php->setName('PHP');
	
        $category_symfony = new Category();
        $category_symfony->setName('Symfony');
	
        $manager->persist($category_php);
	$manager->persist($category_symfony);
        $manager->flush();

        $this->addReference('category_php', $category_php);
        $this->addReference('category_symfony', $category_symfony);
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
