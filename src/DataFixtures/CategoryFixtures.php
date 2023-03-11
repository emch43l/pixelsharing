<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cat1 = new Category();
        $cat1->setName('Natura');

        $cat2 = new Category();
        $cat2->setName('Miasta');

        $cat3 = new Category();
        $cat3->setName('Zwierzęta');

        $manager->persist($cat1);
        $manager->persist($cat2);
        $manager->persist($cat3);

        $manager->flush();
    }
}