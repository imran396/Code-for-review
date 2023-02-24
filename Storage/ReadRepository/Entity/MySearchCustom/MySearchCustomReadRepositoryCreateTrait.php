<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MySearchCustom;

trait MySearchCustomReadRepositoryCreateTrait
{
    protected ?MySearchCustomReadRepository $mySearchCustomReadRepository = null;

    protected function createMySearchCustomReadRepository(): MySearchCustomReadRepository
    {
        return $this->mySearchCustomReadRepository ?: MySearchCustomReadRepository::new();
    }

    /**
     * @param MySearchCustomReadRepository $mySearchCustomReadRepository
     * @return static
     * @internal
     */
    public function setMySearchCustomReadRepository(MySearchCustomReadRepository $mySearchCustomReadRepository): static
    {
        $this->mySearchCustomReadRepository = $mySearchCustomReadRepository;
        return $this;
    }
}
