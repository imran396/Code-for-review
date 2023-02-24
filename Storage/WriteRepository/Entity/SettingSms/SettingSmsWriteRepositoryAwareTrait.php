<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSms;

trait SettingSmsWriteRepositoryAwareTrait
{
    protected ?SettingSmsWriteRepository $settingSmsWriteRepository = null;

    protected function getSettingSmsWriteRepository(): SettingSmsWriteRepository
    {
        if ($this->settingSmsWriteRepository === null) {
            $this->settingSmsWriteRepository = SettingSmsWriteRepository::new();
        }
        return $this->settingSmsWriteRepository;
    }

    /**
     * @param SettingSmsWriteRepository $settingSmsWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSmsWriteRepository(SettingSmsWriteRepository $settingSmsWriteRepository): static
    {
        $this->settingSmsWriteRepository = $settingSmsWriteRepository;
        return $this;
    }
}
