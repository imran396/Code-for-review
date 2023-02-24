<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearchCustom;

trait MySearchCustomWriteRepositoryAwareTrait
{
    protected ?MySearchCustomWriteRepository $mySearchCustomWriteRepository = null;

    protected function getMySearchCustomWriteRepository(): MySearchCustomWriteRepository
    {
        if ($this->mySearchCustomWriteRepository === null) {
            $this->mySearchCustomWriteRepository = MySearchCustomWriteRepository::new();
        }
        return $this->mySearchCustomWriteRepository;
    }

    /**
     * @param MySearchCustomWriteRepository $mySearchCustomWriteRepository
     * @return static
     * @internal
     */
    public function setMySearchCustomWriteRepository(MySearchCustomWriteRepository $mySearchCustomWriteRepository): static
    {
        $this->mySearchCustomWriteRepository = $mySearchCustomWriteRepository;
        return $this;
    }
}
