<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ImageFixture extends Fixture implements DependentFixtureInterface
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $cat1 = $this->getReference(CategoryFixtures::NATURE_CATEGORY);
        $cat2 = $this->getReference(CategoryFixtures::CITY_CATEGORY);
        $cat3 = $this->getReference(CategoryFixtures::ANIMAL_CATEGORY);

        $user = $this->getReference(UserFixtures::ADMIN_USER);

        $categories = [
            $cat1,
            $cat2,
            $cat3
        ];

        for($i = 0; $i < 20; $i++) {
            $image = new Image();
            $image->setUser($user);
            $image->setCategory($categories[array_rand($categories)]);
            $image->setImageName('sampleImage');
            $manager->persist($image);
        }

        $manager->flush();

    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}