<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Vote;
use App\Repository\ImageRepository;
use App\Repository\VoteRepository;
use App\Request\AddVoteRequest;
use App\Request\HomeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Security;

class ImageService
{
    private const PAGELIMIT = 18;

    private ImageRepository $imageRepository;
    private VoteRepository $voteRepository;

    public function __construct(
        private EntityManagerInterface $manager,
        private PaginatorInterface $paginator,
        private Security $security
    )
    {
        $this->imageRepository = $this->manager->getRepository(Image::class);
        $this->voteRepository = $this->manager->getRepository(Vote::class);
    }

    public function addVote(AddVoteRequest $request) : bool
    {
        $user = $this->security->getUser();

        $image = $this->imageRepository->findOneBy(['uuid' => $request->getImage()]);
        if($image === null)
            return false;

        $existingVote = $this->voteRepository->getVoteByImageAndUser(
            $image,$user
        );

        if($existingVote !== null)
        {
            $existingVote->setReaction($request->getType());
            $this->manager->persist($existingVote);
        }
        else
        {
            $vote = new Vote();
            $vote->setUser($user);
            $vote->setReaction($request->getType());
            $image->addVote($vote);
        }

        $this->manager->persist($image);
        $this->manager->flush();

        return true;
    }

    public function getImages(HomeRequest $request) : PaginationInterface
    {
        $cat_Repo = $this->manager->getRepository(Category::class);
        $img_Repo = $this->manager->getRepository(Image::class);

        if($request->getCategory() === null) {
            $imagesQuery = $img_Repo->getAll();
        } else {
            $category = $cat_Repo->findOneBy(['uuid' => $request->getCategory()]);
            if($category === null) {
                $imagesQuery = [];
            } else {
                $imagesQuery = $img_Repo->findByCategory($category);
            }
        };

        return  $this->paginator->paginate(
            $imagesQuery,
            $request->getPage(),
            self::PAGELIMIT
        );
    }
}