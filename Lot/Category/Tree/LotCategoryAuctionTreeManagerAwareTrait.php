<?php

namespace Sam\Lot\Category\Tree;


/**
 * Trait LotCategoryAuctionTreeManagerAwareTrait
 * @package Sam\Lot\Category\Tree
 */
trait LotCategoryAuctionTreeManagerAwareTrait
{

    /**
     * @var LotCategoryAuctionTreeManager|null
     */
    protected ?LotCategoryAuctionTreeManager $lotCategoryAuctionTreeManager = null;


    /**
     * @return LotCategoryAuctionTreeManager
     */
    protected function getLotCategoryAuctionTreeManager(): LotCategoryAuctionTreeManager
    {
        if ($this->lotCategoryAuctionTreeManager === null) {
            $this->lotCategoryAuctionTreeManager = LotCategoryAuctionTreeManager::new();
        }
        return $this->lotCategoryAuctionTreeManager;
    }


    /**
     * @param LotCategoryAuctionTreeManager $lotCategoryAuctionTreeManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotCategoryAuctionTreeManager(LotCategoryAuctionTreeManager $lotCategoryAuctionTreeManager): static
    {
        $this->lotCategoryAuctionTreeManager = $lotCategoryAuctionTreeManager;
        return $this;
    }
}
