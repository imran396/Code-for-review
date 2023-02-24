<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\SettingSeo;

trait SettingSeoWriteRepositoryAwareTrait
{
    protected ?SettingSeoWriteRepository $settingSeoWriteRepository = null;

    protected function getSettingSeoWriteRepository(): SettingSeoWriteRepository
    {
        if ($this->settingSeoWriteRepository === null) {
            $this->settingSeoWriteRepository = SettingSeoWriteRepository::new();
        }
        return $this->settingSeoWriteRepository;
    }

    /**
     * @param SettingSeoWriteRepository $settingSeoWriteRepository
     * @return static
     * @internal
     */
    public function setSettingSeoWriteRepository(SettingSeoWriteRepository $settingSeoWriteRepository): static
    {
        $this->settingSeoWriteRepository = $settingSeoWriteRepository;
        return $this;
    }
}
