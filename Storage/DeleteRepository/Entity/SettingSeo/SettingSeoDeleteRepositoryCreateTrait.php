<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\SettingSeo;

trait SettingSeoDeleteRepositoryCreateTrait
{
    protected ?SettingSeoDeleteRepository $settingSeoDeleteRepository = null;

    protected function createSettingSeoDeleteRepository(): SettingSeoDeleteRepository
    {
        return $this->settingSeoDeleteRepository ?: SettingSeoDeleteRepository::new();
    }

    /**
     * @param SettingSeoDeleteRepository $settingSeoDeleteRepository
     * @return static
     * @internal
     */
    public function setSettingSeoDeleteRepository(SettingSeoDeleteRepository $settingSeoDeleteRepository): static
    {
        $this->settingSeoDeleteRepository = $settingSeoDeleteRepository;
        return $this;
    }
}
