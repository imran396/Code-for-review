<?php
/**
 * SAM-10452: Decouple HelpersAwareTrait to rtb modules for v3-7
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Helper\Base;

use Auction;
use InvalidArgumentException;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\Rtb\Command\Helper\Hybrid\HybridRtbCommandHelper;
use Sam\Rtb\Command\Helper\Live\LiveRtbCommandHelper;

class RtbCommandHelperFactory extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createByRtbCurrent(RtbCurrent $rtbCurrent, bool $isReadOnlyDb = false): AbstractRtbCommandHelper
    {
        return $this->createByAuctionId($rtbCurrent->AuctionId, $isReadOnlyDb);
    }

    public function createByAuctionId(int $auctionId, bool $isReadOnlyDb = false): AbstractRtbCommandHelper
    {
        $auction = $this->getAuctionLoader()->load($auctionId, $isReadOnlyDb);
        if (!$auction) {
            throw CouldNotFindAuction::withId($auctionId);
        }

        return $this->createByAuction($auction);
    }

    public function createByAuction(Auction $auction): AbstractRtbCommandHelper
    {
        if ($auction->isLive()) {
            return LiveRtbCommandHelper::new();
        }

        if ($auction->isHybrid()) {
            return HybridRtbCommandHelper::new();
        }

        $message = sprintf("Cannot create RTB Command Helper for auction type \"%s\"", $auction->AuctionType);
        throw new InvalidArgumentException($message);
    }
}
