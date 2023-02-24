<?php
/**
 * SAM-6481: Add bidding options on the admin - auction - lots - added lots table
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec. 23, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Admin\PlaceBid;

use Auction;
use InvalidArgumentException;
use Sam\Application\Controller\Admin\PlaceBid\LiveOrHybrid\LiveOrHybridPlaceBidHandlerCreateTrait;
use Sam\Application\Controller\Admin\PlaceBid\Timed\TimedPlaceBidHandlerCreateTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class is responsible to detect and construct applicable place bid command handler
 *
 * Class PlaceBidHandlerProvider
 * @package Sam\Application\Controller\Admin\PlaceBid
 */
class PlaceBidHandlerProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LiveOrHybridPlaceBidHandlerCreateTrait;
    use TimedPlaceBidHandlerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect and construct appropriate place bid command handler for the auction
     *
     * @param int $auctionId
     * @return PlaceBidHandlerInterface
     */
    public function detectForAuction(int $auctionId): PlaceBidHandlerInterface
    {
        if ($this->loadAuction($auctionId)->isTimed()) {
            $handler = $this->createTimedPlaceBidHandler();
        } else {
            $handler = $this->createLiveOrHybridPlaceBidHandler();
        }
        return $handler;
    }

    /**
     * @param int $auctionId
     * @return Auction
     */
    protected function loadAuction(int $auctionId): Auction
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            throw new InvalidArgumentException('Available auction not found ' . composeSuffix(['a' => $auctionId]));
        }
        return $auction;
    }
}
