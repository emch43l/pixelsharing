<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Image;
use App\Request\HomeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class ImageService
{
    private const PAGELIMIT = 3;
    public function __construct(
        private EntityManagerInterface $manager,
        private PaginatorInterface $paginator
    )
    {

    }

    public function getImages(HomeRequest $request) : PaginationInterface
    {
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