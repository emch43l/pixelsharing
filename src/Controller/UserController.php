<?php

namespace App\Controller;

use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService)
    {

    }
    #[IsGranted('ROLE_USER')]
    #[Route('/user', name: 'app_user')]
    public function index(Request $request): Response
    {
        $paginator = $this->userService->getUserImages($this->getUser(), $request);
        return $this->render('user/index.html.twig', [
            'paginator' => $paginator
        ]);
    }
}
