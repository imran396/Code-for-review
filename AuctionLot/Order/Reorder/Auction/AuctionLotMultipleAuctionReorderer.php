<?php
/**
 * SAM-5658: Multiple Auction Reorderer for lots
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 27, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder\Auction;

use Auction;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Stores auction reordering queue and processes it
 *
 * Class AuctionLotMultipleAuctionReorderer
 */
class AuctionLotMultipleAuctionReorderer extends CustomizableClass
{
    use AuctionLotReordererAwareTrait;

    private static array $auctionReorderingQueue = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Reorder auction lots if order related fields was changed
     * @param int $editorUserId
     * @return void
     */
    public function reorderQueued(int $editorUserId): void
    {
        foreach ($this->getReorderingQueue() as $auction) {
            $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
        }

        $this->clearReorderingQueue();
    }

    /**
     * @param Auction $auction
     */
    public function addAuctionToQueue(Auction $auction): void
    {
        if (!$this->isAuctionQueued($auction)) {
            self::$auctionReorderingQueue[$auction->Id] = $auction;
        }
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    public function isAuctionQueued(Auction $auction): bool
    {
        return array_key_exists($auction->Id, self::$auctionReorderingQueue);
    }

    /**
     * @return array
     */
    private function getReorderingQueue(): array
    {
        return self::$auctionReorderingQueue;
    }

    /**
     * @return  void
     */
    private function clearReorderingQueue(): void
    {
        self::$auctionReorderingQueue = [];
    }
}
