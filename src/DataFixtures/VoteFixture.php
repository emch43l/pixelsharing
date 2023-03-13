<?php

namespace App\DataFixtures;

use App\Entity\Vote;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class VoteFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (ImageFixture::getReferences() as $value) {
            $image = $this->getReference($value);
            for($i = 0; $i < 10; $i ++) {
                $vote = new Vote();
                $vote->setImage($image);
                $vote->setReaction(!(rand(0,1) == 0));
                $vote->setUser(
                    $this->getReference(UserFixtures::ADMIN_USER)
                );
                $manager->persist($vote);
            }
        }

        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
          UserFixtures::class,
          ImageFixture::class
        ];
    }
}