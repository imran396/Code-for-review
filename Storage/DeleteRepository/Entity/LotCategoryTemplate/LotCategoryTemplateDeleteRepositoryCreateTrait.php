<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategoryTemplate;

trait LotCategoryTemplateDeleteRepositoryCreateTrait
{
    protected ?LotCategoryTemplateDeleteRepository $lotCategoryTemplateDeleteRepository = null;

    protected function createLotCategoryTemplateDeleteRepository(): LotCategoryTemplateDeleteRepository
    {
        return $this->lotCategoryTemplateDeleteRepository ?: LotCategoryTemplateDeleteRepository::new();
    }

    /**
     * @param LotCategoryTemplateDeleteRepository $lotCategoryTemplateDeleteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryTemplateDeleteRepository(LotCategoryTemplateDeleteRepository $lotCategoryTemplateDeleteRepository): static
    {
        $this->lotCategoryTemplateDeleteRepository = $lotCategoryTemplateDeleteRepository;
        return $this;
    }
}
