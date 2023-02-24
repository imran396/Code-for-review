<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\ReadRepository\Entity\LotCategoryCustData;

trait LotCategoryCustDataReadRepositoryCreateTrait
{
    protected ?LotCategoryCustDataReadRepository $lotCategoryCustDataReadRepository = null;

    protected function createLotCategoryCustDataReadRepository(): LotCategoryCustDataReadRepository
    {
        return $this->lotCategoryCustDataReadRepository ?: LotCategoryCustDataReadRepository::new();
    }

    /**
     * @param LotCategoryCustDataReadRepository $lotCategoryCustDataReadRepository
     * @return static
     * @internal
     */
    public function setLotCategoryCustDataReadRepository(LotCategoryCustDataReadRepository $lotCategoryCustDataReadRepository): static
    {
        $this->lotCategoryCustDataReadRepository = $lotCategoryCustDataReadRepository;
        return $this;
    }
}
