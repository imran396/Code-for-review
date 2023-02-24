<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearch;

trait MySearchDeleteRepositoryCreateTrait
{
    protected ?MySearchDeleteRepository $mySearchDeleteRepository = null;

    protected function createMySearchDeleteRepository(): MySearchDeleteRepository
    {
        return $this->mySearchDeleteRepository ?: MySearchDeleteRepository::new();
    }

    /**
     * @param MySearchDeleteRepository $mySearchDeleteRepository
     * @return static
     * @internal
     */
    public function setMySearchDeleteRepository(MySearchDeleteRepository $mySearchDeleteRepository): static
    {
        $this->mySearchDeleteRepository = $mySearchDeleteRepository;
        return $this;
    }
}
