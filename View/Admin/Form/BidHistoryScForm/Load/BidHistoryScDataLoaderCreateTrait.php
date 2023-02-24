<?php
/**
 * Bid History Sc Data Loader Create Trait
 *
 * SAM-5937: Refactor bid history sc page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BidHistoryScForm\Load;

/**
 * Trait BidHistoryScDataLoaderCreateTrait
 */
trait BidHistoryScDataLoaderCreateTrait
{
    protected ?BidHistoryScDataLoader $bidHistoryScDataLoader = null;

    /**
     * @return BidHistoryScDataLoader
     */
    protected function createBidHistoryScDataLoader(): BidHistoryScDataLoader
    {
        return $this->bidHistoryScDataLoader ?: BidHistoryScDataLoader::new();
    }

    /**
     * @param BidHistoryScDataLoader $bidHistoryScDataLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setBidHistoryScDataLoader(BidHistoryScDataLoader $bidHistoryScDataLoader): static
    {
        $this->bidHistoryScDataLoader = $bidHistoryScDataLoader;
        return $this;
    }
}
