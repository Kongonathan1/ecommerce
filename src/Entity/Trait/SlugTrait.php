<?php
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

Trait SlugTrait
{
    #[ORM\Column(length: 150)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }
}