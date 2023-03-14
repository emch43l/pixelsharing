<?php

namespace App\EventListener;

use App\Entity\Image;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

#[AsEntityListener(event: Events::postUpdate ,method: 'updateVotes',entity: Vote::class)]
class VoteUpdater
{
    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $manager
    )
    {

    }


    public function updateVotes(Vote $vote, LifecycleEventArgs $event) : void
    {
        $image = $vote->getImage();
        $votes = $image->getVotes();

        $image->setPositiveVotes(
            $votes->filter(function (Vote $vote) {
                return $vote->getReaction() === true;
            })->count()
        );

        $image->setNegativeVotes(
            $votes->filter(function (Vote $vote) {
                return $vote->getReaction() === false;
            })->count()
        );

        $this->logger->notice("---------------------------------------------------");
        $this->logger->notice("Image: ".$image->getImageName()." updating votes");
        $this->logger->notice("Positive votes number: ".$image->getPositiveVotes());
        $this->logger->notice("Negative votes number: ".$image->getNegativeVotes());
        $this->logger->notice("---------------------------------------------------");

        $this->manager->persist($image);
        $this->manager->flush();
    }
}