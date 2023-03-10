<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CreateCategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_ADMIN')]
#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager)
    {

    }

    public function list(Request $request) : Response
    {

    }

    #[Route('/create','app_category_create')]
    public function addCategory(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CreateCategoryFormType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $repo = $this->manager->getRepository(CategoryRepository::class);
            $repo->save($category,true);

            return $this->render('category/createCategory.html.twig',[
                'form' => $form->createView()
            ]);
        }

        return $this->render('category/createCategory.html.twig',[
            'form' => $form->createView()
        ]);
    }
}