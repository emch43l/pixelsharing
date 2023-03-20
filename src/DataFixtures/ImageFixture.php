<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ImageFixture extends Fixture implements DependentFixtureInterface
{

    public const IMAGES_REFERENCE_PREFIX = 'image-';
    public const IMAGES_NUMBER = 20;

    public static function getReferences()
    {
        for($i = 0; $i < self::IMAGES_NUMBER; $i++) {
            yield self::IMAGES_REFERENCE_PREFIX.$i;
        }
    }

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

        foreach (self::getReferences() as $value) {
            $image = new Image();
            $image->setUser($user);
            $image->setTitle('sample title 123');
            $image->setCategory($categories[array_rand($categories)]);
            $image->setImageName('test.979a2049.jpg');
            $this->addReference($value,$image);
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