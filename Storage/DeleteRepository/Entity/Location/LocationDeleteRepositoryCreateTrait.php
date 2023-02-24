<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\Location;

trait LocationDeleteRepositoryCreateTrait
{
    protected ?LocationDeleteRepository $locationDeleteRepository = null;

    protected function createLocationDeleteRepository(): LocationDeleteRepository
    {
        return $this->locationDeleteRepository ?: LocationDeleteRepository::new();
    }

    /**
     * @param LocationDeleteRepository $locationDeleteRepository
     * @return static
     * @internal
     */
    public function setLocationDeleteRepository(LocationDeleteRepository $locationDeleteRepository): static
    {
        $this->locationDeleteRepository = $locationDeleteRepository;
        return $this;
    }
}
