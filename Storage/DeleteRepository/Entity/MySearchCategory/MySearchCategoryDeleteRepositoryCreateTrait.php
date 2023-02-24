<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\MySearchCategory;

trait MySearchCategoryDeleteRepositoryCreateTrait
{
    protected ?MySearchCategoryDeleteRepository $mySearchCategoryDeleteRepository = null;

    protected function createMySearchCategoryDeleteRepository(): MySearchCategoryDeleteRepository
    {
        return $this->mySearchCategoryDeleteRepository ?: MySearchCategoryDeleteRepository::new();
    }

    /**
     * @param MySearchCategoryDeleteRepository $mySearchCategoryDeleteRepository
     * @return static
     * @internal
     */
    public function setMySearchCategoryDeleteRepository(MySearchCategoryDeleteRepository $mySearchCategoryDeleteRepository): static
    {
        $this->mySearchCategoryDeleteRepository = $mySearchCategoryDeleteRepository;
        return $this;
    }
}
