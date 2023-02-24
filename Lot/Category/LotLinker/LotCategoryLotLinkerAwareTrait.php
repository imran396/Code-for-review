<?php

namespace Sam\Lot\Category\LotLinker;

/**
 * Trait LotCategoryLotLinkerAwareTrait
 * @package Sam\Lot\Category\LotLinker
 */
trait LotCategoryLotLinkerAwareTrait
{
    /**
     * @var LotCategoryLotLinker|null
     */
    protected ?LotCategoryLotLinker $lotCategoryLotLinker = null;

    /**
     * @return LotCategoryLotLinker
     */
    protected function getLotCategoryLotLinker(): LotCategoryLotLinker
    {
        if ($this->lotCategoryLotLinker === null) {
            $this->lotCategoryLotLinker = LotCategoryLotLinker::new();
        }
        return $this->lotCategoryLotLinker;
    }

    /**
     * @param LotCategoryLotLinker $lotCategoryLotLinker
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCategoryLotLinker(LotCategoryLotLinker $lotCategoryLotLinker): static
    {
        $this->lotCategoryLotLinker = $lotCategoryLotLinker;
        return $this;
    }
}
