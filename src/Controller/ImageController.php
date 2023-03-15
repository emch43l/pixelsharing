<?php

namespace App\Controller;

use App\Form\Request\CreateImageRequestType;
use App\Request\CreateImageRequest;
use App\Service\ImageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route('image')]
class ImageController extends AbstractController
{
    public function __construct(private ImageService $imageService)
    {

    }
    #[Route('/create', name: 'app_image')]
    public function index(Request $request): Response
    {
        $imageRequest = new CreateImageRequest();

        $form = $this->createForm(CreateImageRequestType::class,$imageRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->imageService->createNew($imageRequest);
        }

        return $this->render('image/index.html.twig', [
            'error' => $form->getErrors(),
            'user' => $this->getUser(),
            'form' => $form->createView()
        ]);
    }
}
