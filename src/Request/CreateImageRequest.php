<?php

namespace App\Request;

use App\Entity\Category;
use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class CreateImageRequest
{
    private ?string $title = null;

    private ?UploadedFile $image = null;

    private ?Category $category = null;

    public function MapTo() : Image
    {
        if($this->category === null || $this->title === null || $this->image === null)
        {
            throw new \InvalidArgumentException();
        }
        $image = new Image();
        $image->setTitle($this->title);
        $image->setCategory($this->category);
        $image->setImageFile($this->image);

        return $image;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return File|null
     */
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    /**
     * @param File|null $file
     */
    public function setImage(?UploadedFile $file): void
    {
        $this->image = $file;
    }

    /**
     * @return Uuid|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Uuid|null $category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }
}