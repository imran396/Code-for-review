<?php

namespace Sam\Lot\Category\Delete;

/**
 * Trait LotCategoryDeleterAwareTrait
 * @package Sam\Lot\Category\Delete
 */
trait LotCategoryDeleterAwareTrait
{
    /**
     * @var LotCategoryDeleter|null
     */
    protected ?LotCategoryDeleter $lotCategoryDeleter = null;

    /**
     * @return LotCategoryDeleter
     */
    protected function getLotCategoryDeleter(): LotCategoryDeleter
    {
        if ($this->lotCategoryDeleter === null) {
            $this->lotCategoryDeleter = LotCategoryDeleter::new();
        }
        return $this->lotCategoryDeleter;
    }

    /**
     * @param LotCategoryDeleter $lotCategoryDeleter
     * @return static
     * @internal
     */
    public function setLotCategoryDeleter(LotCategoryDeleter $lotCategoryDeleter): static
    {
        $this->lotCategoryDeleter = $lotCategoryDeleter;
        return $this;
    }
}
