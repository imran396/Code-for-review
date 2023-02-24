<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSeo;

trait SettingSeoReadRepositoryCreateTrait
{
    protected ?SettingSeoReadRepository $settingSeoReadRepository = null;

    protected function createSettingSeoReadRepository(): SettingSeoReadRepository
    {
        return $this->settingSeoReadRepository ?: SettingSeoReadRepository::new();
    }

    /**
     * @param SettingSeoReadRepository $settingSeoReadRepository
     * @return static
     * @internal
     */
    public function setSettingSeoReadRepository(SettingSeoReadRepository $settingSeoReadRepository): static
    {
        $this->settingSeoReadRepository = $settingSeoReadRepository;
        return $this;
    }
}
