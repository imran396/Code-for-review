<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\Location;

trait LocationReadRepositoryCreateTrait
{
    protected ?LocationReadRepository $locationReadRepository = null;

    protected function createLocationReadRepository(): LocationReadRepository
    {
        return $this->locationReadRepository ?: LocationReadRepository::new();
    }

    /**
     * @param LocationReadRepository $locationReadRepository
     * @return static
     * @internal
     */
    public function setLocationReadRepository(LocationReadRepository $locationReadRepository): static
    {
        $this->locationReadRepository = $locationReadRepository;
        return $this;
    }
}
