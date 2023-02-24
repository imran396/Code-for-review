<?php
/**
 * Calculates and make values and amounts
 *
 * SAM-6527: Rtb refactor SellLots command
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Concrete\SellLots\Base\Internal\Detect;

use AuctionBidder;
use AuctionLotItem;
use BidTransaction;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class ValueDetector
 * @package Sam\Rtb
 */
class ValueDetector extends CustomizableClass
{
    use LotQuantityScaleLoaderCreateTrait;
    use LotRendererAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $auctionLots
     * @return string
     */
    public function buildLotNoList(array $auctionLots): string
    {
        $lotNos = [];
        $lotRenderer = $this->getLotRenderer();
        foreach ($auctionLots as $auctionLot) {
            $lotNos[] = $lotRenderer->renderLotNo($auctionLot) . ' (' . $auctionLot->LotItemId . ')';
        }
        return implode(', ', $lotNos);
    }

    /**
     * Determine if current bid comes from online bidder (not floor)
     * @param AuctionBidder|null $auctionBidder
     * @param BidTransaction $currentBidTransaction
     * @return bool
     */
    public function determineIsInternetBid(
        ?AuctionBidder $auctionBidder,
        BidTransaction $currentBidTransaction
    ): bool {
        $isInternetBid = $auctionBidder && !$currentBidTransaction->FloorBidder;
        return $isInternetBid;
    }

    /**
     * Determine hammer price
     * @param AuctionLotItem $currentAuctionLot
     * @param BidTransaction $currentBidTransaction
     * @return float
     */
    public function determineHammerPrice(
        AuctionLotItem $currentAuctionLot,
        BidTransaction $currentBidTransaction
    ): float {
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($currentAuctionLot->LotItemId, $currentAuctionLot->AuctionId);
        return $currentAuctionLot->multiplyQuantityEffectively($currentBidTransaction->Bid, $quantityScale);
    }

}
