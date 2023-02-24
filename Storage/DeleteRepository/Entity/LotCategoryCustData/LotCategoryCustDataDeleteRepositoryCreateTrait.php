<?php

/**
 * This file is auto-generated.
 */

namespace Sam\Storage\DeleteRepository\Entity\LotCategoryCustData;

trait LotCategoryCustDataDeleteRepositoryCreateTrait
{
    protected ?LotCategoryCustDataDeleteRepository $lotCategoryCustDataDeleteRepository = null;

    protected function createLotCategoryCustDataDeleteRepository(): LotCategoryCustDataDeleteRepository
    {
        return $this->lotCategoryCustDataDeleteRepository ?: LotCategoryCustDataDeleteRepository::new();
    }

    /**
     * @param LotCategoryCustDataDeleteRepository $lotCategoryCustDataDeleteRepository
     * @return static
     * @internal
     */
    public function setLotCategoryCustDataDeleteRepository(LotCategoryCustDataDeleteRepository $lotCategoryCustDataDeleteRepository): static
    {
        $this->lotCategoryCustDataDeleteRepository = $lotCategoryCustDataDeleteRepository;
        return $this;
    }
}
