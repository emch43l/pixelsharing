<?php

namespace App\Service;

use App\Entity\User;
use App\Form\Repository\ImageRepository;
use App\Form\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    const MAX_IMAGES = 12;
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PaginatorInterface $paginator,
        private readonly ImageRepository $imageRepository
    )
    {

    }

    public function getUserImages(User $user, Request $request): PaginationInterface
    {
        $page = intval($request->get('page','1'));
        $page = ($page < 1) ? 1 : $page;

        return $this->paginator->paginate(
            $this->imageRepository->findByUserQuery($user),
            $page,
            self::MAX_IMAGES
        );
    }

    public function getUserByUsername(string $username): User
    {
        if(($user = $this->userRepository->getUserByUsername($username)) !== null)
            return $user;
        throw new NotFoundHttpException();
    }

}