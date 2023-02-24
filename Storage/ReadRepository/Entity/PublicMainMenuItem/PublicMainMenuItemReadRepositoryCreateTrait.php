<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\PublicMainMenuItem;

trait PublicMainMenuItemReadRepositoryCreateTrait
{
    protected ?PublicMainMenuItemReadRepository $publicMainMenuItemReadRepository = null;

    protected function createPublicMainMenuItemReadRepository(): PublicMainMenuItemReadRepository
    {
        return $this->publicMainMenuItemReadRepository ?: PublicMainMenuItemReadRepository::new();
    }

    /**
     * @param PublicMainMenuItemReadRepository $publicMainMenuItemReadRepository
     * @return static
     * @internal
     */
    public function setPublicMainMenuItemReadRepository(PublicMainMenuItemReadRepository $publicMainMenuItemReadRepository): static
    {
        $this->publicMainMenuItemReadRepository = $publicMainMenuItemReadRepository;
        return $this;
    }
}
