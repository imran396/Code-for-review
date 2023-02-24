<?php
/**
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Hybrid;

use AuctionLotItem;
use LotItem;
use Sam\Rtb\Command\Concrete\SellLots\Hybrid\Internal\HandlerHelperCreateTrait;
use Sam\Rtb\Hybrid\HelpersAwareTrait;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelperAwareTrait;

/**
 * Class SellLot
 * @package Sam\Rtb
 */
class SellLotsHybridBidderHandler extends \Sam\Rtb\Command\Concrete\SellLots\Base\AbstractBidderHandler
{
    use HandlerHelperCreateTrait;
    use HelpersAwareTrait;
    use HybridRtbCommandHelperAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function execute(): void
    {
        parent::execute();
        $this->getTimeoutHelper()->updateLotEndDate($this->getRtbCurrent(), $this->detectModifierUserId());
        $this->getResponseHelper()->applyAdditionalInfo($this);
    }

    /**
     * Implements next lot detection for hybrid auction.
     * Return null if no lots were found.
     * @param AuctionLotItem $currentAuctionLot
     * @return LotItem|null
     */
    protected function findNextLotItem(AuctionLotItem $currentAuctionLot): ?LotItem
    {
        $lotItem = $this->createHandlerHelper()->findNextLotItem(
            $this->getRtbCurrent(),
            $currentAuctionLot,
            $this->getRtbCommandHelper()
        );
        $this->isLastLot = !$lotItem;
        return $lotItem;
    }
}
