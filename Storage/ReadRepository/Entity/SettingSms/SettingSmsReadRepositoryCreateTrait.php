<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\SettingSms;

trait SettingSmsReadRepositoryCreateTrait
{
    protected ?SettingSmsReadRepository $settingSmsReadRepository = null;

    protected function createSettingSmsReadRepository(): SettingSmsReadRepository
    {
        return $this->settingSmsReadRepository ?: SettingSmsReadRepository::new();
    }

    /**
     * @param SettingSmsReadRepository $settingSmsReadRepository
     * @return static
     * @internal
     */
    public function setSettingSmsReadRepository(SettingSmsReadRepository $settingSmsReadRepository): static
    {
        $this->settingSmsReadRepository = $settingSmsReadRepository;
        return $this;
    }
}
