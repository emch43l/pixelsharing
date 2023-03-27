<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserStatistics;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_USER = 'admin_user';
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user,'zaq1@WSX')
        );
        $user->setRoles(['ROLE_ADMIN','ROLE_USER']);

        $userStats = new UserStatistics();
        $userStats->setTotalActions(0);
        $userStats->setTotalShares(0);
        $userStats->setUser($user);
        $user->setUserStatistics($userStats);

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ADMIN_USER,$user);
    }
}
