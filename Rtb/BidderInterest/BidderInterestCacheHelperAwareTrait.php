<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2/27/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\BidderInterest;

/**
 * Trait BidderInterestCacheHelperAwareTrait
 * @package Sam\Rtb\BidderInterest
 */
trait BidderInterestCacheHelperAwareTrait
{
    /**
     * @var BidderInterestCacheHelper|null
     */
    protected ?BidderInterestCacheHelper $bidderInterestCacheHelper = null;

    /**
     * @return BidderInterestCacheHelper
     */
    protected function getBidderInterestCacheHelper(): BidderInterestCacheHelper
    {
        if ($this->bidderInterestCacheHelper === null) {
            $this->bidderInterestCacheHelper = BidderInterestCacheHelper::new();
        }
        return $this->bidderInterestCacheHelper;
    }

    /**
     * @param BidderInterestCacheHelper $bidderInterestCacheHelper
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setBidderInterestCacheHelper(BidderInterestCacheHelper $bidderInterestCacheHelper): static
    {
        $this->bidderInterestCacheHelper = $bidderInterestCacheHelper;
        return $this;
    }
}
