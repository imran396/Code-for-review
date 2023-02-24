<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MySearch;

trait MySearchReadRepositoryCreateTrait
{
    protected ?MySearchReadRepository $mySearchReadRepository = null;

    protected function createMySearchReadRepository(): MySearchReadRepository
    {
        return $this->mySearchReadRepository ?: MySearchReadRepository::new();
    }

    /**
     * @param MySearchReadRepository $mySearchReadRepository
     * @return static
     * @internal
     */
    public function setMySearchReadRepository(MySearchReadRepository $mySearchReadRepository): static
    {
        $this->mySearchReadRepository = $mySearchReadRepository;
        return $this;
    }
}
