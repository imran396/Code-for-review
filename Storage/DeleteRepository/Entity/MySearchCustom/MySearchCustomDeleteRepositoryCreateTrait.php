<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearchCustom;

trait MySearchCustomDeleteRepositoryCreateTrait
{
    protected ?MySearchCustomDeleteRepository $mySearchCustomDeleteRepository = null;

    protected function createMySearchCustomDeleteRepository(): MySearchCustomDeleteRepository
    {
        return $this->mySearchCustomDeleteRepository ?: MySearchCustomDeleteRepository::new();
    }

    /**
     * @param MySearchCustomDeleteRepository $mySearchCustomDeleteRepository
     * @return static
     * @internal
     */
    public function setMySearchCustomDeleteRepository(MySearchCustomDeleteRepository $mySearchCustomDeleteRepository): static
    {
        $this->mySearchCustomDeleteRepository = $mySearchCustomDeleteRepository;
        return $this;
    }
}
