<?php

namespace App\RequestDataTemplate;

use Symfony\Component\Uid\Uuid;

class HomeTemplate
{
    public ?Uuid $category = null;

    public ?int $page = null;

    /**
     * @return Uuid|null
     */
    public function getCategory(): Uuid|null
    {
        return $this->category;
    }

    /**
     * @param Uuid $category
     */
    public function setCategory(Uuid $category): void
    {
        $this->category = $category;
    }

    /**
     * @return int|null
     */
    public function getPage(): int|null
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }


}