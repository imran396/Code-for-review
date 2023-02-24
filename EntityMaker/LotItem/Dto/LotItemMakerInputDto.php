<?php
/**
 * Data Transfer Object for passing input data for Lot entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 * SAM-4015: Auction Lot and Lot Item Entity Makers
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Dec 7, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Dto;

use Sam\EntityMaker\Base\Data\Range;
use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * Class LotItemMakerDto
 * @package Sam\EntityMaker\LotItem
 * @property string|null $active
 * @property string|null $additionalBpInternet
 * @property string|null $auctionSoldId
 * @property string|null $auctionSoldName // ?
 * @property string|null $auctionSoldSyncKey // ?
 * @property string|null $bpRangeCalculation
 * @property string|null $bpRule
 * @property string|null $buyNowSelectQuantityEnabled
 * @property string|null $buyersPremiumString raw data formatted in string, parsing required
 * @property string[]|null $buyersPremiumDataRows data in array structure
 * @property int[]|null $categoriesIds // not in meta (Moved from service fields)
 * @property string[]|null $categoriesNames // not in meta (Moved from service fields)
 * @property string|null $categories // ?
 * @property string|null $changes
 * @property string|null $consignorId
 * @property string|null $consignorName
 * @property string|null $consignorSyncKey
 * @property string|int|null $consignorCommissionId
 * @property Range[]|null $consignorCommissionRanges
 * @property string|null $consignorCommissionCalculationMethod
 * @property string|int|null $consignorSoldFeeId
 * @property Range[]|null $consignorSoldFeeRanges
 * @property string|int|null $consignorSoldFeeCalculationMethod
 * @property string|null $consignorSoldFeeReference
 * @property string|int|null $consignorUnsoldFeeId
 * @property Range[]|null $consignorUnsoldFeeRanges
 * @property string|null $consignorUnsoldFeeCalculationMethod
 * @property string|null $consignorUnsoldFeeReference
 * @property string|null $cost
 * @property string|null $dateSold
 * @property string|null $description
 * @property string|null $fbOgDescription
 * @property string|null $fbOgImageUrl
 * @property string|null $fbOgTitle
 * @property string|null $hammerPrice
 * @property string|null $highEstimate
 * @property string|int|null $id
 * @property mixed $images // ?
 * @property array<float[]>|null $increments
 * @property string|null $internetBid
 * @property string|null $itemFullNum
 * @property string|null $itemNum
 * @property string|null $itemNumExt
 * @property string|null $location
 * @property mixed $lotCustomFields // ?
 * @property string|null $lotItemTaxArr
 * @property string|null $lowEstimate
 * @property string|null $name
 * @property string|null $noTaxOos
 * @property mixed $notes // ?
 * @property string|null $onlyTaxBp
 * @property string|null $quantity
 * @property string|null $quantityDigits
 * @property string|null $quantityXMoney
 * @property string|null $replacementPrice
 * @property string|null $reservePrice
 * @property string|null $returned
 * @property string|null $salesTax
 * @property string|null $seoMetaDescription
 * @property string|null $seoMetaKeywords
 * @property string|null $seoMetaTitle
 * @property object|null $specificLocation
 * @property string|null $startingBid
 * @property string|null $syncKey // ?
 * @property string|null $syncNamespaceId // ?
 * @property string|null $taxDefaultCountry
 * @property string|null $taxExempt
 * @property array|null $taxStates
 * @property string|null $warranty
 * @property string|null $winningBidderId
 * @property string|null $winningBidderName
 * @property string|null $winningBidderSyncKey // ?
 * @property string|null $hpTaxSchemaId
 * @property string|null $bpTaxSchemaId
 */
class LotItemMakerInputDto extends InputDto
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
