<?php

namespace App\Service;

use Cassandra\Aggregate;
use Doctrine\ORM\EntityManagerInterface;

class PaginatorService
{
    public function __construct(private EntityManagerInterface $manager)
    {

    }

    public function paginate()
    {

    }

}