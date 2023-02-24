<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\PublicMainMenuItem;

trait PublicMainMenuItemDeleteRepositoryCreateTrait
{
    protected ?PublicMainMenuItemDeleteRepository $publicMainMenuItemDeleteRepository = null;

    protected function createPublicMainMenuItemDeleteRepository(): PublicMainMenuItemDeleteRepository
    {
        return $this->publicMainMenuItemDeleteRepository ?: PublicMainMenuItemDeleteRepository::new();
    }

    /**
     * @param PublicMainMenuItemDeleteRepository $publicMainMenuItemDeleteRepository
     * @return static
     * @internal
     */
    public function setPublicMainMenuItemDeleteRepository(PublicMainMenuItemDeleteRepository $publicMainMenuItemDeleteRepository): static
    {
        $this->publicMainMenuItemDeleteRepository = $publicMainMenuItemDeleteRepository;
        return $this;
    }
}
