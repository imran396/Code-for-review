<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSms;

trait SettingSmsDeleteRepositoryCreateTrait
{
    protected ?SettingSmsDeleteRepository $settingSmsDeleteRepository = null;

    protected function createSettingSmsDeleteRepository(): SettingSmsDeleteRepository
    {
        return $this->settingSmsDeleteRepository ?: SettingSmsDeleteRepository::new();
    }

    /**
     * @param SettingSmsDeleteRepository $settingSmsDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSmsDeleteRepository(SettingSmsDeleteRepository $settingSmsDeleteRepository): static
    {
        $this->settingSmsDeleteRepository = $settingSmsDeleteRepository;
        return $this;
    }
}
