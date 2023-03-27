<?php

namespace App\Form\Repository;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function save(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Query
     */
    public function findByCategoryQuery(Category $category): Query
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.Category = :category')
            ->setParameter('category', $category)
            ->orderBy('i.imageName', 'ASC')
            ->getQuery();
    }

    public function findByUserQuery(User $user): Query
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.User = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }

    public function getAllQuery(): Query
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.updatedAt','DESC')
            ->getQuery();
    }

//    public function findOneBySomeField($value): ?Image
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
