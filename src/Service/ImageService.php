<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Vote;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\VoteRepository;
use App\Request\CreateImageRequest;
use App\Request\AddVoteRequest;
use App\Request\HomeRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class ImageService
{
    private const PAGELIMIT = 9;

    private ImageRepository $imageRepository;
    private VoteRepository $voteRepository;
    private CategoryRepository $categoryRepository;

    public function __construct(
        private LoggerInterface $logger,
        private EntityManagerInterface $manager,
        private PaginatorInterface $paginator,
        private Security $security
    )
    {
        $this->categoryRepository = $this->manager->getRepository(Category::class);
        $this->imageRepository = $this->manager->getRepository(Image::class);
        $this->voteRepository = $this->manager->getRepository(Vote::class);
    }

    public function createNew(CreateImageRequest $request) : void
    {
        $image = $request->MapTo();
        $image->setUser($this->security->getUser());
        $this->manager->persist($image);
        $this->manager->flush();
    }

    public function getOneByUuid(Uuid $uuid): Image|null
    {
        return $this->imageRepository->findOneBy(['uuid' => $uuid]);
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
//            $this->logger->notice("---------------------");
//            $this->logger->notice($request->getType());
//            $this->logger->notice("---------------------");
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

    public function countAll(): int
    {
        return $this->imageRepository->countAll();
    }

    public function markVotedByUser(array $imageItems, UserInterface|null $user) : iterable
    {
        if($user === null)
            return $imageItems;

        $userVotes = new ArrayCollection($this->voteRepository->getUserVotesIds($user));
        $userVotesIds = $userVotes->map(function (Vote $vote) {
           return [$vote->getImage()->getId(),$vote->getReaction()];
        })->toArray();

        $items = new ArrayCollection($imageItems);

        return $items->map(function (Image $image) use ($userVotesIds) {
            foreach ($userVotesIds as $data) {
                if($image->getId() == $data[0])
                {
                    $image->setIsLikedByUser($data[1]);
                    break;
                }
            }
            return $image;
        })->toArray();
    }
}