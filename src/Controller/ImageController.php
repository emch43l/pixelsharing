<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Form\AddCommentFormType;
use App\Form\CreateImageFormType;
use App\Form\EditImageFormType;
use App\Request\CreateImageRequest;
use App\Request\EditImageRequest;
use App\Service\ImageService;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('image')]
class ImageController extends AbstractController
{
    public function __construct(
        private ImageService $imageService,
        private UserService $userService
    )
    {

    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edit/{uuid}',name: 'app_image_edit')]
    public function edit_image(Request $request, Uuid $uuid): Response
    {
        $image = $this->imageService->getOneByUuid($uuid);
        if($image->getUser() !== $this->getUser())
            throw new NotFoundHttpException();

        $form = $this->createForm(EditImageFormType::class,$image);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->imageService->update($image);
            return $this->redirectToRoute('app_image_view',[
                'uuid' => $image->getUuid()
            ]);
        }

        return $this->render('image/editImage.html.twig',[
            'error' => $form->getErrors(true,true),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{username}',name: 'app_image_user_view')]
    public function user_view(Request $request, string $username) : Response
    {
        $user = $this->userService->getUserByUsername($username);
        $paginator = $this->userService->getUserImages($user,$request);

        return $this->render('image/userImages.html.twig', [
            'paginator' => $paginator,
            'user' => $user
        ]);
    }

    #[Route('/view/{uuid}', name: 'app_image_view', methods: ['GET','POST'])]
    public function view(Request $request, Uuid $uuid): Response
    {
        $image = $this->imageService->getOneByUuid($uuid);

        $comment = new Comment();
        $form = $this->createForm(AddCommentFormType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $this->imageService->addComment($image,$this->getUser(),$comment);

            return $this->render('image/view.html.twig',[
                'comment_count' => $image->getComments()->count(),
                'image' => $image,
                'form' => $form->createView()
            ]);
        }

        return $this->render('image/view.html.twig',[
            'comment_count' => $image->getComments()->count(),
            'image' => $image,
            'form' => $form->createView()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/create', name: 'app_image')]
    public function create(Request $request): Response
    {
        $imageRequest = new Image();

        $form = $this->createForm(CreateImageFormType::class,$imageRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->imageService->createNew($imageRequest);
        }

        return $this->render('image/index.html.twig', [
            'error' => $form->getErrors(true,true),
            'form' => $form->createView()
        ]);
    }
}
