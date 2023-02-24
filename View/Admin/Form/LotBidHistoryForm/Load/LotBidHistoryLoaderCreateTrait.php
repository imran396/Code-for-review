<?php

namespace Sam\View\Admin\Form\LotBidHistoryForm\Load;

/**
 * Trait LotBidHistoryLoaderCreateTrait
 * @package Sam\View\Admin\Form\LotBidHistoryForm
 */
trait LotBidHistoryLoaderCreateTrait
{
    protected ?LotBidHistoryLoader $lotBidHistoryLoader = null;

    /**
     * @return LotBidHistoryLoader
     */
    protected function createLotBidHistoryLoader(): LotBidHistoryLoader
    {
        return $this->lotBidHistoryLoader ?: LotBidHistoryLoader::new();
    }

    /**
     * @param LotBidHistoryLoader $lotBidHistoryLoader
     * @return $this
     * @internal
     */
    public function setLotBidHistoryLoader(LotBidHistoryLoader $lotBidHistoryLoader): static
    {
        $this->lotBidHistoryLoader = $lotBidHistoryLoader;
        return $this;
    }
}
