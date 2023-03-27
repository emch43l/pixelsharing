<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Vote;
use App\Form\Repository\CategoryRepository;
use App\Form\Repository\ImageRepository;
use App\Form\Repository\VoteRepository;
use App\Request\AddVoteRequest;
use App\Request\CreateImageRequest;
use App\Request\HomeRequest;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
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

    public function createNew(Image $image) : void
    {
        $image->setUser($this->security->getUser());
        $this->imageRepository->save($image,true);
    }

    public function getOneByUuid(Uuid $uuid): Image
    {
        $image = $this->imageRepository->findOneBy(['uuid' => $uuid]);
        if($image === null)
            throw new NotFoundHttpException();
        return $this->markVotedByUser([$image],$this->security->getUser())[0];
    }

    public function update(Image $image): void
    {
        $this->imageRepository->save($image,true);
    }

    public function addVote(AddVoteRequest $request) : void
    {
        $user = $this->security->getUser();

        $image = $this->imageRepository->findOneBy(['uuid' => $request->getImage()]);
        if($image === null)
            throw new NotFoundHttpException();

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

        $this->imageRepository->save($image,true);
    }

    public function getImages(HomeRequest $request) : PaginationInterface
    {
        $imagesQuery = [];

        if($request->getCategory() === null) {
            $imagesQuery = $this->imageRepository->getAllQuery();
        } else {
            $category = $this->categoryRepository->findOneBy(['uuid' => $request->getCategory()]);
            if($category !== null) {
                $imagesQuery = $this->imageRepository->findByCategoryQuery($category);
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
        return $this->imageRepository->count([]);
    }

    public function markVotedByUser(array $imageItems, User|null $user): array
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

    public function addComment(Image $image, User $user, Comment $comment): void
    {
       $comment->setUser($user);
       $comment->setImage($image);
       $comment->setDate((new \DateTimeImmutable()));
       $this->manager->persist($comment);
       $this->manager->flush();
    }
}