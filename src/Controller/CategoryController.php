<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CreateCategoryFormType;
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

    #[Route('/','app_category_index')]
    public function list(Request $request) : Response
    {
        return $this->render('category/listCategory.html.twig', [
           'categories' => $this->manager->getRepository(Category::class)->findAll()
        ]);
    }

    #[Route('/create','app_category_create')]
    public function addCategory(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CreateCategoryFormType::class,$category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $repo = $this->manager->getRepository(Category::class);
            $repo->save($category,true);

            return $this->render('category/createCategory.html.twig',[
                'form' => $form->createView()
            ]);
        }

        return $this->render('category/createCategory.html.twig',[
            'error' => $form->getErrors(true,true),
            'form' => $form->createView()
        ]);
    }

    #[Route('/create','app_category_edit')]
    public function editCategory(Request $request) : Response
    {
        return $this->render('category/createCategory.html.twig',[
        ]);
    }
}