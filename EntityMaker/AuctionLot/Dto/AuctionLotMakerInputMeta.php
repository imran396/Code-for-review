<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of AuctionLot.
 *
 * SAM-8839: Auction Lot entity-maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Nov 17, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\AuctionLot
 */
class AuctionLotMakerInputMeta extends CustomizableClass
{
    /**
     * @var string MASTER|AuctionLotItem.LotNumberPreFix+AuctionLotItem.LotNumber+AuctionLotItem.LotNumberExt
     * @group Timed online lot attributes
     */
    public $bulkControl;
    /**
     * @var string MASTER|EQUALLY|WINNING
     * @group Timed online lot attributes
     * @soap-default-value MASTER
     */
    public $bulkWinBidDistribution;
    /**
     * @var string
     * @group Timed online lot attributes
     * @soap-type-hint bool
     */
    public $bestOffer;
    /**
     * @var string Required if buyNowSelectQuantityEnabled is true
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $buyNowAmount;
    /**
     * @var string
     * @group Timed online lot attributes
     * @soap-type-hint bool
     */
    public $buyNowSelectQuantityEnabled;
    /**
     * @var int
     */
    public $consignorCommissionId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorCommissionRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorCommissionCalculationMethod;
    /**
     * @var int
     */
    public $consignorSoldFeeId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorSoldFeeRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorSoldFeeCalculationMethod;
    /**
     * @var string zero|hammer_price|starting_bid|reserve_price|max_bid|current_bid|low_estimate|high_estimate|cost|replacement_price|custom_field:Int
     */
    public $consignorSoldFeeReference;
    /**
     * @var int
     */
    public $consignorUnsoldFeeId;
    /**
     * @var \Sam\EntityMaker\Base\Data\Range[]
     */
    public $consignorUnsoldFeeRanges;
    /**
     * @var string sliding|tiered
     */
    public $consignorUnsoldFeeCalculationMethod;
    /**
     * @var string zero|hammer_price|starting_bid|reserve_price|max_bid|current_bid|low_estimate|high_estimate|cost|replacement_price|custom_field:Int
     */
    public $consignorUnsoldFeeReference;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $generalNote;
    /**
     * @var string yyyy-mm-dd hh:mm
     * @group Live or Hybrid lot attributes
     */
    public $endPrebiddingDate;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $featured;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $listingOnly;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $lotFullNum;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotNum;
    /**
     * @var string
     */
    public $lotNumExt;
    /**
     * @var string
     */
    public $lotNumPrefix;
    /**
     * @var string Unassigned|Active|Unsold|Sold|Deleted|Received
     */
    public $lotStatus;
    /**
     * @var string AuctionLotItem.LotStatusId
     * @soap-type-hint int
     */
    public $lotStatusId;
    /**
     * @var string
     * @group Timed online lot attributes
     * @soap-type-hint bool
     */
    public $noBidding;
    /**
     * @var string
     */
    public $noteToClerk;
    /**
     * @var string Required depending on settings > system parameters > admin options > inventory page configuration or if buyNowSelectQuantityEnabled is true
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $quantity;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $quantityDigits;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $quantityXMoney;
    /**
     * @var string
     * @soap-type-hint int
     * @soap-default-value 0
     */
    public $order;
    /**
     * @var string
     * @group Live or Hybrid lot attributes
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotGroup;
    /**
     * @var string
     */
    public $seoUrl;
    /**
     * @var string yyyy-mm-dd hh:mm
     */
    public $startBiddingDate;
    /**
     * @var string yyyy-mm-dd hh:mm Required for timed auctions when extendAll is false
     * @group Timed online lot attributes
     * @soap-required-conditionally
     */
    public $startClosingDate;
    /**
     * @var string yyyy-mm-dd hh:mm
     */
    public $publishDate;
    /**
     * @var string <a href="/api/soap12?op=Timezone">Timezone</a>.Location Required if at least one date field is assigned
     * @group Timed online lot attributes
     * @soap-required-conditionally
     */
    public $timezone;
    /**
     * @var string AuctionLotItemSyncKey
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string
     */
    public $termsAndConditions;
    /**
     * @var string
     */
    public $trackCode;
    /**
     * @var string yyyy-mm-dd hh:mm
     */
    public $unpublishDate;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
