<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryTemplate;

trait LotCategoryTemplateReadRepositoryCreateTrait
{
    protected ?LotCategoryTemplateReadRepository $lotCategoryTemplateReadRepository = null;

    protected function createLotCategoryTemplateReadRepository(): LotCategoryTemplateReadRepository
    {
        return $this->lotCategoryTemplateReadRepository ?: LotCategoryTemplateReadRepository::new();
    }

    /**
     * @param LotCategoryTemplateReadRepository $lotCategoryTemplateReadRepository
     * @return static
     * @internal
     */
    public function setLotCategoryTemplateReadRepository(LotCategoryTemplateReadRepository $lotCategoryTemplateReadRepository): static
    {
        $this->lotCategoryTemplateReadRepository = $lotCategoryTemplateReadRepository;
        return $this;
    }
}
