<?php
/**
 * Data Transfer Object for passing input data for Auction Lot entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
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

use Sam\EntityMaker\Base\Data\Range;
use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\AuctionLot
 * @property string|int|null $auctionId // not in meta (Moved from service fields)
 * @property string|null $bulkControl
 * @property string|null $bulkWinBidDistribution
 * @property string|null $bestOffer
 * @property string|null $buyNowAmount
 * @property string|null $buyNowSelectQuantityEnabled
 * @property string[]|null $categoriesNames // not in meta
 * @property string|null $consignorCommissionId
 * @property array<Range|string>|null $consignorCommissionRanges
 * @property string|null $consignorCommissionCalculationMethod
 * @property string|null $consignorSoldFeeId
 * @property array<Range|string>|null $consignorSoldFeeRanges
 * @property string|null $consignorSoldFeeCalculationMethod
 * @property string|null $consignorSoldFeeReference
 * @property string|null $consignorUnsoldFeeId
 * @property array<Range|string>|null $consignorUnsoldFeeRanges
 * @property string|null $consignorUnsoldFeeCalculationMethod
 * @property string|null $consignorUnsoldFeeReference
 * @property string|null $generalNote
 * @property string|null $endPrebiddingDate
 * @property string|null $featured
 * @property string|int|null $id
 * @property string|null $listingOnly
 * @property string|null $lotFullNum
 * @property string|int|null $lotItemId // not in meta (Moved from service fields)
 * @property string|null $lotNum
 * @property string|null $lotNumExt
 * @property string|null $lotNumPrefix
 * @property string|null $lotStatus
 * @property string|int|null $lotStatusId
 * @property string|null $noBidding
 * @property string|null $noteToClerk
 * @property string|null $quantity
 * @property string|null $quantityDigits
 * @property string|null $quantityXMoney
 * @property string|null $order
 * @property string|null $lotGroup
 * @property string|null $seoUrl
 * @property string|null $startBiddingDate
 * @property string|null $startClosingDate
 * @property string|null $publishDate
 * @property string|null $timezone
 * @property string|null $syncKey
 * @property string|null $syncNamespaceId
 * @property string|null $termsAndConditions
 * @property string|null $trackCode
 * @property string|null $unpublishDate
 * @property string|null $hpTaxSchemaId
 * @property string|null $bpTaxSchemaId
 */
class AuctionLotMakerInputDto extends InputDto
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
