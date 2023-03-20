<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Request\HomeRequestType;
use App\Form\Request\PaginationRequestType;
use App\Request\HomeRequest;
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
        $template = new HomeRequest();
        $form = $this->createForm(HomeRequestType::class,$template);
        $form->submit($request->query->all());

        $paginator = $this->imageService->getImages($template);
        $images = $this->imageService->markVotedByUser($paginator->getItems(), $this->getUser());
        $paginator->setItems($images);

        return $this->render('home/index.html.twig', [
            'total_uploads' => $this->imageService->countAll(),
            'paginator' => $paginator,
            'current_category' => $template->getCategory(),
            'user' => $this->getUser(),
            'categories' => $this->manager->getRepository(Category::class)->findAll(),
            'controller_name' => 'HomeController',
        ]);
    }
}
