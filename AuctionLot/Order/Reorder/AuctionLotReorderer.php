<?php
/**
 * SAM-5654 Auction lot reorderer
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dev 25, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\AuctionLot\Order\Reorder;

use Auction;
use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\Load\AuctionLotReorderingLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Uses for reordering lots in auction when some options or lots changed
 *
 * Class AuctionLotReorderer
 * @package Sam\AuctionLot\Order\Reorder
 */
class AuctionLotReorderer extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotReorderingLoaderCreateTrait;

    /** @var array */
    protected array $resultInfo = [];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Set auction lots order field (auction_lot_item.order) as
     * if lots are ordered corresponding to auction lot order options
     *
     * @param Auction $auction
     * @param int $editorUserId
     * @return void
     */
    public function reorder(Auction $auction, int $editorUserId): void
    {
        $this->resultInfo = [];
        $newOrderNum = 1;
        $auctionLots = $this->createAuctionLotReorderingLoader()->yieldAuctionLots($auction, 200);
        foreach ($auctionLots as $auctionLot) {
            $this->applyOrderNum($auctionLot, $newOrderNum, $editorUserId);
            $newOrderNum++;
        }
        log_info('Reorder lots for auction' . composeSuffix(['a' => $auction->Id]));
    }

    /**
     * Update AuctionLotItem->Order value and save
     * @param AuctionLotItem $auctionLot
     * @param int $newOrderNum
     * @param int $editorUserId
     * @return AuctionLotItem
     */
    protected function applyOrderNum(AuctionLotItem $auctionLot, int $newOrderNum, int $editorUserId): AuctionLotItem
    {
        if ($auctionLot->Order !== $newOrderNum) { // for performance optimization
            $auctionLot->Order = $newOrderNum;
            $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
        }
        $this->resultInfo[$auctionLot->Id] = $newOrderNum;
        return $auctionLot;
    }

    /**
     * Return ordering result data array of ali.id => ali.order.
     * Need for unit test.
     * @return array
     */
    public function getResultInfo(): array
    {
        return $this->resultInfo;
    }
}
