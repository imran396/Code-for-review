<?php

namespace Sam\Lot\Category\Order;

/**
 * Trait LotCategoryOrdererAwareTrait
 * @package Sam\Lot\Category\Order
 */
trait LotCategoryOrdererAwareTrait
{
    /**
     * @var LotCategoryOrderer|null
     */
    protected ?LotCategoryOrderer $lotCategoryOrderer = null;

    /**
     * @return LotCategoryOrderer
     */
    protected function getLotCategoryOrderer(): LotCategoryOrderer
    {
        if ($this->lotCategoryOrderer === null) {
            $this->lotCategoryOrderer = LotCategoryOrderer::new();
        }
        return $this->lotCategoryOrderer;
    }

    /**
     * @param LotCategoryOrderer $lotCategoryOrderer
     * @return static
     * @internal
     */
    public function setLotCategoryOrderer(LotCategoryOrderer $lotCategoryOrderer): static
    {
        $this->lotCategoryOrderer = $lotCategoryOrderer;
        return $this;
    }
}
