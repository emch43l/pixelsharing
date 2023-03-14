<?php

namespace App\Request;

use Symfony\Component\Uid\Uuid;

class AddVoteRequest
{
    public ?Uuid $image = null;

    public ?bool $type = null;

    /**
     * @return bool|null
     */
    public function getType(): ?bool
    {
        return $this->type;
    }

    /**
     * @param bool|null $type
     */
    public function setType(?bool $type): void
    {
        $this->type = $type;
    }

    /**
     * @return Uuid|null
     */
    public function getImage(): ?Uuid
    {
        return $this->image;
    }

    /**
     * @param Uuid|null $image
     */
    public function setImage(?Uuid $image): void
    {
        $this->image = $image;
    }
}