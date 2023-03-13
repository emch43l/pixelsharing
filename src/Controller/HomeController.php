<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\HomeType;
use App\RequestDataTemplate\HomeTemplate;
use App\Service\ImageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $manager,
        private ImageService $imageService
    )
    {

    }

    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        $images = [];
        $template = new HomeTemplate();
        $form = $this->createForm(HomeType::class,$template);
        $form->submit($request->query->all());

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $this->imageService->getImages($template);
            $images = $data->getItems();
        }

        return $this->render('home/index.html.twig', [
            'images' => $images,
            'user' => $this->getUser(),
            'categories' => $this->manager->getRepository(Category::class)->findAll(),
            'controller_name' => 'HomeController',
        ]);
    }
}
