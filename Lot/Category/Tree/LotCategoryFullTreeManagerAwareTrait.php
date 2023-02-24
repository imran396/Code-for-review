<?php

namespace Sam\Lot\Category\Tree;


/**
 * Trait LotCategoryFullTreeManagerAwareTrait
 * @package Sam\Lot\Category\Tree
 */
trait LotCategoryFullTreeManagerAwareTrait
{

    /**
     * @var LotCategoryFullTreeManager|null
     */
    protected ?LotCategoryFullTreeManager $lotCategoryFullTreeManager = null;


    /**
     * @return LotCategoryFullTreeManager
     */
    protected function getLotCategoryFullTreeManager(): LotCategoryFullTreeManager
    {
        if ($this->lotCategoryFullTreeManager === null) {
            $this->lotCategoryFullTreeManager = LotCategoryFullTreeManager::new();
        }
        return $this->lotCategoryFullTreeManager;
    }


    /**
     * @param LotCategoryFullTreeManager $lotCategoryFullTreeManager
     * @return static
     * @internal
     */
    public function setLotCategoryFullTreeManager(LotCategoryFullTreeManager $lotCategoryFullTreeManager): static
    {
        $this->lotCategoryFullTreeManager = $lotCategoryFullTreeManager;
        return $this;
    }
}
