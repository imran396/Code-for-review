<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\MySearchCategory;

trait MySearchCategoryWriteRepositoryAwareTrait
{
    protected ?MySearchCategoryWriteRepository $mySearchCategoryWriteRepository = null;

    protected function getMySearchCategoryWriteRepository(): MySearchCategoryWriteRepository
    {
        if ($this->mySearchCategoryWriteRepository === null) {
            $this->mySearchCategoryWriteRepository = MySearchCategoryWriteRepository::new();
        }
        return $this->mySearchCategoryWriteRepository;
    }

    /**
     * @param MySearchCategoryWriteRepository $mySearchCategoryWriteRepository
     * @return static
     * @internal
     */
    public function setMySearchCategoryWriteRepository(MySearchCategoryWriteRepository $mySearchCategoryWriteRepository): static
    {
        $this->mySearchCategoryWriteRepository = $mySearchCategoryWriteRepository;
        return $this;
    }
}
