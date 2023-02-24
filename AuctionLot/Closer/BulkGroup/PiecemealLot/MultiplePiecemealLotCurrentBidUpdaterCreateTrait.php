<?php

namespace Sam\AuctionLot\Closer\BulkGroup\PiecemealLot;

/**
 * Trait MultiplePiecemealLotCurrentBidUpdaterCreateTrait
 * @package
 */
trait MultiplePiecemealLotCurrentBidUpdaterCreateTrait
{
    /**
     * @var MultiplePiecemealLotCurrentBidUpdater|null
     */
    protected ?MultiplePiecemealLotCurrentBidUpdater $multiplePiecemealLotCurrentBidUpdater = null;

    /**
     * @return MultiplePiecemealLotCurrentBidUpdater
     */
    protected function createMultiplePiecemealLotCurrentBidUpdater(): MultiplePiecemealLotCurrentBidUpdater
    {
        return $this->multiplePiecemealLotCurrentBidUpdater ?: MultiplePiecemealLotCurrentBidUpdater::new();
    }

    /**
     * @param MultiplePiecemealLotCurrentBidUpdater $multiplePiecemealLotCurrentBidUpdater
     * @return static
     * @internal
     */
    public function setMultiplePiecemealLotCurrentBidUpdater(MultiplePiecemealLotCurrentBidUpdater $multiplePiecemealLotCurrentBidUpdater): static
    {
        $this->multiplePiecemealLotCurrentBidUpdater = $multiplePiecemealLotCurrentBidUpdater;
        return $this;
    }
}
