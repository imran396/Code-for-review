<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\PublicMainMenuItem;

trait PublicMainMenuItemWriteRepositoryAwareTrait
{
    protected ?PublicMainMenuItemWriteRepository $publicMainMenuItemWriteRepository = null;

    protected function getPublicMainMenuItemWriteRepository(): PublicMainMenuItemWriteRepository
    {
        if ($this->publicMainMenuItemWriteRepository === null) {
            $this->publicMainMenuItemWriteRepository = PublicMainMenuItemWriteRepository::new();
        }
        return $this->publicMainMenuItemWriteRepository;
    }

    /**
     * @param PublicMainMenuItemWriteRepository $publicMainMenuItemWriteRepository
     * @return static
     * @internal
     */
    public function setPublicMainMenuItemWriteRepository(PublicMainMenuItemWriteRepository $publicMainMenuItemWriteRepository): static
    {
        $this->publicMainMenuItemWriteRepository = $publicMainMenuItemWriteRepository;
        return $this;
    }
}
