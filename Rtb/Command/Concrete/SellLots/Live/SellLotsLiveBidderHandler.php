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

namespace Sam\Rtb\Command\Concrete\SellLots\Live;

use AuctionLotItem;
use LotItem;
use Sam\Rtb\Command\Concrete\SellLots\Base\AbstractBidderHandler;
use Sam\Rtb\Command\Concrete\SellLots\Live\Internal\HandlerHelperCreateTrait;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelperAwareTrait;
use Sam\Rtb\Live\HelpersAwareTrait;

/**
 * Class Command
 * @package Sam\Rtb
 */
class SellLotsLiveBidderHandler extends AbstractBidderHandler
{
    use HandlerHelperCreateTrait;
    use HelpersAwareTrait;
    use LiveRtbCommandHelperAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Implements next lot detection for live auction.
     * Return running lot if no next lots were found.
     * @param AuctionLotItem $currentAuctionLot
     * @return LotItem|null
     */
    protected function findNextLotItem(AuctionLotItem $currentAuctionLot): ?LotItem
    {
        $handlerHelper = $this->createHandlerHelper();
        $nextAuctionLot = $handlerHelper->findNextAuctionLot($this->getRtbCurrent(), $currentAuctionLot);
        $nextLotItem = $handlerHelper->findNextLotItemByAuctionLot($nextAuctionLot, $this->getLotItem());
        $this->isLastLot = !$nextAuctionLot;
        return $nextLotItem;
    }
}
