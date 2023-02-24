<?php
/**
 * SAM-5631: Refactor lot modification bidder notifier
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 10, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Modify\NotifyBidder;

/**
 * Trait LotBidderNotifierCreateTrait
 * @package Sam\Lot\Modify\NotifyBidder
 */
trait LotBidderNotifierCreateTrait
{
    /**
     * @var LotBidderNotifier|null
     */
    protected ?LotBidderNotifier $lotBidderNotifier = null;

    /**
     * @return LotBidderNotifier
     */
    protected function createLotBidderNotifier(): LotBidderNotifier
    {
        return $this->lotBidderNotifier ?: LotBidderNotifier::new();
    }

    /**
     * @param LotBidderNotifier $lotBidderNotifier
     * @return $this
     * @internal
     * @noinspection PhpUnused
     */
    public function setLotBidderNotifier(LotBidderNotifier $lotBidderNotifier): static
    {
        $this->lotBidderNotifier = $lotBidderNotifier;
        return $this;
    }
}
