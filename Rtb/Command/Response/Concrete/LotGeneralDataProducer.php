<?php
/**
 * SAM-6459: Rtbd response - lot data producers
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Rtb\Command\Response\Concrete;

use Auction;
use AuctionLotItem;
use Sam\Core\Service\CustomizableClass;
use LotItem;
use RtbCurrent;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\Core\Constants;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class LotNameDataProducer
 * @package Sam\Rtb\Command\Response\Concrete
 */
class LotGeneralDataProducer extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
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
     * @param RtbCurrent $rtbCurrent
     * @return array = ]
     *  Constants\Rtb::RES_GROUP_ID => ?int,
     *  Constants\Rtb::RES_HIGH_ESTIMATE => ?float,
     *  Constants\Rtb::RES_IS_INTERNET_BID => bool,
     *  Constants\Rtb::RES_IS_QUANTITY_X_MONEY => bool,
     *  Constants\Rtb::RES_LISTING_ONLY => int, // TODO: make bool
     *  Constants\Rtb::RES_LOT_ITEM_ID => int,
     *  Constants\Rtb::RES_LOT_NAME => string,
     *  Constants\Rtb::RES_LOT_NO => string,
     *  Constants\Rtb::RES_LOW_ESTIMATE => ?float,
     *  Constants\Rtb::RES_QUANTITY => int,
     *  Constants\Rtb::RES_RESERVE_PRICE => ?float,
     *  Constants\Rtb::RES_SEO_URL => string,
     */
    public function produceData(RtbCurrent $rtbCurrent): array
    {
        $lotItem = $this->getLotItemLoader()->load($rtbCurrent->LotItemId);
        $auction = $this->getAuctionLoader()->load($rtbCurrent->AuctionId);
        $auctionLot = $this->getAuctionLotLoader()->load($rtbCurrent->LotItemId, $rtbCurrent->AuctionId);
        $data = $this->composeData($lotItem, $auction, $auctionLot);
        return $data;
    }

    /**
     * @param LotItem|null $lotItem
     * @param Auction|null $auction
     * @param AuctionLotItem|null $auctionLot
     * @return array
     */
    public function composeData(?LotItem $lotItem, ?Auction $auction, ?AuctionLotItem $auctionLot): array
    {
        $groupId = null;
        $isInternetBid = false;
        $isLotListing = false;
        $isQuantityXMoney = false;
        $lotHighEstimate = null;
        $lotItemId = '';
        $lotLowEstimate = null;
        $lotName = '';
        $lotNo = '';
        $lotReservePrice = null;
        $quantity = null;
        $seoUrl = '';

        if ($lotItem && $auction) {
            $isInternetBid = $lotItem->InternetBid;
            $lotHighEstimate = $lotItem->HighEstimate;
            $lotItemId = $lotItem->Id;
            $lotLowEstimate = $lotItem->LowEstimate;
            $lotName = $this->getLotRenderer()->makeName($lotItem->Name, $auction->TestAuction);
            $lotReservePrice = $lotItem->ReservePrice;
            if ($auctionLot) {
                $groupId = $auctionLot->GroupId;
                $isLotListing = $auctionLot->ListingOnly;
                $isQuantityXMoney = $auctionLot->QuantityXMoney;
                $lotNo = $this->getLotRenderer()->renderLotNo($auctionLot);
                $quantity = $auctionLot->Quantity;
                $seoUrl = $auctionLot->SeoUrl;
            }
        }
        $data[Constants\Rtb::RES_GROUP_ID] = $groupId;
        $data[Constants\Rtb::RES_HIGH_ESTIMATE] = $lotHighEstimate;
        $data[Constants\Rtb::RES_IS_INTERNET_BID] = $isInternetBid;
        $data[Constants\Rtb::RES_IS_QUANTITY_X_MONEY] = $isQuantityXMoney;
        $data[Constants\Rtb::RES_LISTING_ONLY] = $isLotListing;
        $data[Constants\Rtb::RES_LOT_ITEM_ID] = $lotItemId;
        $data[Constants\Rtb::RES_LOT_NAME] = $lotName;
        $data[Constants\Rtb::RES_LOT_NO] = $lotNo;
        $data[Constants\Rtb::RES_LOW_ESTIMATE] = $lotLowEstimate;
        $data[Constants\Rtb::RES_QUANTITY] = $quantity;
        $data[Constants\Rtb::RES_RESERVE_PRICE] = $lotReservePrice;
        $data[Constants\Rtb::RES_SEO_URL] = $seoUrl;
        return $data;
    }
}
