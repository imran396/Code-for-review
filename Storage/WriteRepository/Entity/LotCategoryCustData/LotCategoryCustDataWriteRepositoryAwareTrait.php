<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\WriteRepository\Entity\LotCategoryCustData;

trait LotCategoryCustDataWriteRepositoryAwareTrait
{
    protected ?LotCategoryCustDataWriteRepository $lotCategoryCustDataWriteRepository = null;

    protected function getLotCategoryCustDataWriteRepository(): LotCategoryCustDataWriteRepository
    {
        if ($this->lotCategoryCustDataWriteRepository === null) {
            $this->lotCategoryCustDataWriteRepository = LotCategoryCustDataWriteRepository::new();
        }
        return $this->lotCategoryCustDataWriteRepository;
    }

    /**
     * @param LotCategoryCustDataWriteRepository $lotCategoryCustDataWriteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryCustDataWriteRepository(LotCategoryCustDataWriteRepository $lotCategoryCustDataWriteRepository): static
    {
        $this->lotCategoryCustDataWriteRepository = $lotCategoryCustDataWriteRepository;
        return $this;
    }
}
