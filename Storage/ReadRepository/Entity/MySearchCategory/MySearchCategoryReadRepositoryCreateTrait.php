<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\MySearchCategory;

trait MySearchCategoryReadRepositoryCreateTrait
{
    protected ?MySearchCategoryReadRepository $mySearchCategoryReadRepository = null;

    protected function createMySearchCategoryReadRepository(): MySearchCategoryReadRepository
    {
        return $this->mySearchCategoryReadRepository ?: MySearchCategoryReadRepository::new();
    }

    /**
     * @param MySearchCategoryReadRepository $mySearchCategoryReadRepository
     * @return static
     * @internal
     */
    public function setMySearchCategoryReadRepository(MySearchCategoryReadRepository $mySearchCategoryReadRepository): static
    {
        $this->mySearchCategoryReadRepository = $mySearchCategoryReadRepository;
        return $this;
    }
}
