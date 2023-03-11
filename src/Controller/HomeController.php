<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class HomeController extends AbstractController
{

    public function __construct(private EntityManagerInterface $manager)
    {

    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'user' => $this->getUser(),
            'categories' => $this->manager->getRepository(Category::class)->findAll(),
            'controller_name' => 'HomeController',
        ]);
    }
}
