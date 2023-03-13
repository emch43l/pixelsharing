<?php

namespace App\Request;

use Symfony\Component\Uid\Uuid;

class HomeRequest extends PaginationRequest
{
    public ?Uuid $category = null;

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

}