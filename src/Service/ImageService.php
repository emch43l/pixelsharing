<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Image;
use App\RequestDataTemplate\HomeTemplate;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ImageService
{
    private const PAGELIMIT = 3;
    public function __construct(
        private EntityManagerInterface $manager,
        private PaginatorInterface $paginator
    )
    {

    }

    public function getImages(HomeTemplate $request) : PaginationInterface
    {
        $imagesQuery = null;

        $cat_Repo = $this->manager->getRepository(Category::class);
        $img_Repo = $this->manager->getRepository(Image::class);

        $category = $cat_Repo->findOneBy(['uuid' => $request->getCategory()]);

        if($request->getCategory() === null) {
            $imagesQuery = $img_Repo->getAll();
        } else {
            $imagesQuery = $img_Repo->findByCategory($category);
        }

        return  $this->paginator->paginate(
            $imagesQuery,
            $request->getPage(),
            self::PAGELIMIT
        );
    }
}