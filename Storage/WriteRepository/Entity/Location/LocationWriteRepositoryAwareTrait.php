<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\Location;

trait LocationWriteRepositoryAwareTrait
{
    protected ?LocationWriteRepository $locationWriteRepository = null;

    protected function getLocationWriteRepository(): LocationWriteRepository
    {
        if ($this->locationWriteRepository === null) {
            $this->locationWriteRepository = LocationWriteRepository::new();
        }
        return $this->locationWriteRepository;
    }

    /**
     * @param LocationWriteRepository $locationWriteRepository
     * @return static
     * @internal
     */
    public function setLocationWriteRepository(LocationWriteRepository $locationWriteRepository): static
    {
        $this->locationWriteRepository = $locationWriteRepository;
        return $this;
    }
}
