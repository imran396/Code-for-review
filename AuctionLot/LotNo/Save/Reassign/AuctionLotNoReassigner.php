<?php
/**
 * SAM-5653 Auction lot no reassigner
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

namespace Sam\AuctionLot\LotNo\Save\Reassign;

use Auction;
use AuctionLotItem;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Order\Reorder\AuctionLotReordererAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\AuctionLotItem\AuctionLotItemWriteRepositoryAwareTrait;

/**
 * Class AuctionLotNoReassigner
 * @package Sam\AuctionLot\LotNo\Save\Reassign
 */
class AuctionLotNoReassigner extends CustomizableClass
{
    use AuctionLotItemWriteRepositoryAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use AuctionLotReordererAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Re-order lots and set auction lot numbers same as value of order number
     *
     * @param Auction $auction
     * @param int $editorUserId
     */
    public function reassign(Auction $auction, int $editorUserId): void
    {
        $this->getAuctionLotReorderer()->reorder($auction, $editorUserId);
        $loader = $this->getAuctionLotLoader()
            ->filterLotStatusId(Constants\Lot::$availableLotStatuses);
        foreach ($loader->yieldByAuctionId($auction->Id) as $auctionLot) {
            $this->applyLotNum($auctionLot, $auctionLot->Order, $editorUserId);
        }
        log_info('Reassign lot num for auction' . composeSuffix(['a' => $auction->Id]));
    }

    /**
     * @param AuctionLotItem $auctionLot
     * @param int $lotNum
     * @param int $editorUserId
     */
    private function applyLotNum(AuctionLotItem $auctionLot, int $lotNum, int $editorUserId): void
    {
        $auctionLot->LotNum = $lotNum;
        $auctionLot->LotNumExt = '';
        $auctionLot->LotNumPrefix = '';
        $this->getAuctionLotItemWriteRepository()->saveWithModifier($auctionLot, $editorUserId);
    }
}
