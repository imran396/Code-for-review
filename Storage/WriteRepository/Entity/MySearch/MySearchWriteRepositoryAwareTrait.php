<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearch;

trait MySearchWriteRepositoryAwareTrait
{
    protected ?MySearchWriteRepository $mySearchWriteRepository = null;

    protected function getMySearchWriteRepository(): MySearchWriteRepository
    {
        if ($this->mySearchWriteRepository === null) {
            $this->mySearchWriteRepository = MySearchWriteRepository::new();
        }
        return $this->mySearchWriteRepository;
    }

    /**
     * @param MySearchWriteRepository $mySearchWriteRepository
     * @return static
     * @internal
     */
    public function setMySearchWriteRepository(MySearchWriteRepository $mySearchWriteRepository): static
    {
        $this->mySearchWriteRepository = $mySearchWriteRepository;
        return $this;
    }
}
