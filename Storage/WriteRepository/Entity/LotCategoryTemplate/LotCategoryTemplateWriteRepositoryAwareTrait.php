<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryTemplate;

trait LotCategoryTemplateWriteRepositoryAwareTrait
{
    protected ?LotCategoryTemplateWriteRepository $lotCategoryTemplateWriteRepository = null;

    protected function getLotCategoryTemplateWriteRepository(): LotCategoryTemplateWriteRepository
    {
        if ($this->lotCategoryTemplateWriteRepository === null) {
            $this->lotCategoryTemplateWriteRepository = LotCategoryTemplateWriteRepository::new();
        }
        return $this->lotCategoryTemplateWriteRepository;
    }

    /**
     * @param LotCategoryTemplateWriteRepository $lotCategoryTemplateWriteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryTemplateWriteRepository(LotCategoryTemplateWriteRepository $lotCategoryTemplateWriteRepository): static
    {
        $this->lotCategoryTemplateWriteRepository = $lotCategoryTemplateWriteRepository;
        return $this;
    }
}
