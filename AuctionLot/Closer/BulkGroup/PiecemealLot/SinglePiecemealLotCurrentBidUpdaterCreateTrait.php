<?php

namespace Sam\AuctionLot\Closer\BulkGroup\PiecemealLot;

/**
 * Trait SinglePiecemealLotCurrentBidUpdaterCreateTrait
 * @package
 */
trait SinglePiecemealLotCurrentBidUpdaterCreateTrait
{
    /**
     * @var SinglePiecemealLotCurrentBidUpdater|null
     */
    protected ?SinglePiecemealLotCurrentBidUpdater $singlePiecemealLotCurrentBidUpdater = null;

    /**
     * @return SinglePiecemealLotCurrentBidUpdater
     */
    protected function createSinglePiecemealLotCurrentBidUpdater(): SinglePiecemealLotCurrentBidUpdater
    {
        return $this->singlePiecemealLotCurrentBidUpdater ?: SinglePiecemealLotCurrentBidUpdater::new();
    }

    /**
     * @param SinglePiecemealLotCurrentBidUpdater $singlePiecemealLotCurrentBidUpdater
     * @return static
     * @internal
     */
    public function setSinglePiecemealLotCurrentBidUpdater(SinglePiecemealLotCurrentBidUpdater $singlePiecemealLotCurrentBidUpdater): static
    {
        $this->singlePiecemealLotCurrentBidUpdater = $singlePiecemealLotCurrentBidUpdater;
        return $this;
    }
}
