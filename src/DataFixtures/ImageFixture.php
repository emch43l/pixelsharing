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

        $image = new Image();
        $image->setUser($user);
        $image->setCategory($cat1);
        $image->setImageName('sampleImage');

        $image1 = new Image();
        $image1->setUser($user);
        $image1->setCategory($cat2);
        $image1->setImageName('sampleImage');

        $image2 = new Image();
        $image2->setUser($user);
        $image2->setCategory($cat2);
        $image2->setImageName('sampleImage');

        $image3 = new Image();
        $image3->setUser($user);
        $image3->setCategory($cat3);
        $image3->setImageName('sampleImage');

        $image4 = new Image();
        $image4->setUser($user);
        $image4->setCategory($cat3);
        $image4->setImageName('sampleImage');

        $image5 = new Image();
        $image5->setUser($user);
        $image5->setCategory($cat3);
        $image5->setImageName('sampleImage');

        $manager->persist($image);
        $manager->persist($image1);
        $manager->persist($image2);
        $manager->persist($image3);
        $manager->persist($image4);
        $manager->persist($image5);

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