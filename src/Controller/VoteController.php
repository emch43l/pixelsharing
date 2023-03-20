<?php

namespace App\Controller;

use App\Form\Request\AddVoteRequestType;
use App\Request\AddVoteRequest;
use App\Service\ImageService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('vote')]
#[IsGranted('ROLE_USER')]
class VoteController extends AbstractController
{
    public function __construct(private ImageService $imageService)
    {

    }
    #[Route('/add','app_vote_add')]
    public function addVote(Request $request)
    {
        $voteRequest = new AddVoteRequest();

        $form = $this->createForm(AddVoteRequestType::class,$voteRequest);
        $form->submit($request->request->all());

        if($form->isSubmitted() && $form->isValid())
        {
            if($this->imageService->addVote($voteRequest) === true) {
                return $this->json([],Response::HTTP_OK);
            } else {
                return $this->json([],Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(
            ['submitted' => $form->getErrors()
            ],Response::HTTP_BAD_REQUEST
        );
    }
}