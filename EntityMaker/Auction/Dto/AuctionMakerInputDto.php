<?php
/**
 * Data transfer object for passing input data for Auction entity creating/updating/validating
 * Dto does not have to accurately describe the fields of the entity, it describes the incoming data from the external interface
 *
 * SAM-10375: Input DTO adjustments and fixes for v3-7
 * SAM-8840: Auction entity-maker module structural adjustments for v3-5
 * SAM-4241 Auction Entity Maker
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           May 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Dto;

use Sam\EntityMaker\Base\Data\Range;
use Sam\EntityMaker\Base\Dto\InputDto;

/**
 * @package Sam\EntityMaker\Auction
 * @property string|null $aboveReserve
 * @property string|null $aboveStartingBid
 * @property string|null $absenteeBidsDisplay
 * @property string|null $additionalBpInternet
 * @property string[]|null $additionalCurrencies
 * @property string|null $allowBiddingDuringStartGap
 * @property string|null $allowForceBid
 * @property string|null $auctionAuctioneerId
 * @property string|null $auctionCatalogAccess
 * @property string|null $auctionCustomFields
 * @property string|null $auctionHeldIn
 * @property string|null $auctionInfoAccess
 * @property string|null $auctionInfoLink
 * @property string|null $auctionStatus
 * @property string|null $auctionStatusId
 * @property string|null $auctionType
 * @property string|null $auctionVisibilityAccess
 * @property string|null $authorizationAmount
 * @property string|null $autoPopulateEmptyLotNum
 * @property string|null $autoPopulateLotFromCategory
 * @property string|null $biddingConsoleAccessDate
 * @property string|null $biddingPaused
 * @property string|null $blacklistPhrase
 * @property string|null $bpRangeCalculation
 * @property string|null $bpRule
 * @property string|int|null $bpRuleId
 * @property string|null $bpTaxSchemaId
 * @property string|null $buyersPremiumString
 * @property array|null $buyersPremiumDataRows
 * @property string|null $ccThresholdDomestic
 * @property string|null $ccThresholdInternational
 * @property string|null $clerkingStyle
 * @property string|null $clerkingStyleId
 * @property string|null $concatenateLotOrderColumns
 * @property string|null $consignorCommissionCalculationMethod
 * @property string|int|null $consignorCommissionId
 * @property Range[]|null $consignorCommissionRanges
 * @property string|null $consignorSoldFeeCalculationMethod
 * @property string|int|null $consignorSoldFeeId
 * @property Range[]|null $consignorSoldFeeRanges
 * @property string|null $consignorSoldFeeReference
 * @property string|null $consignorUnsoldFeeCalculationMethod
 * @property string|int|null $consignorUnsoldFeeId
 * @property Range[]|null $consignorUnsoldFeeRanges
 * @property string|null $consignorUnsoldFeeReference
 * @property string|null $currency
 * @property string|null $currencyId
 * @property string|int|null $dateAssignmentStrategy
 * @property string|null $defaultLotPeriod
 * @property string|null $defaultLotPostalCode
 * @property string|null $description
 * @property string|null $email
 * @property string|null $endPrebiddingDate
 * @property string|null $endRegisterDate
 * @property string|null $eventId
 * @property string|null $eventLocation
 * @property string|null $eventLocationId
 * @property string|null $eventType
 * @property string|null $eventTypeId
 * @property string|null $excludeClosedLots
 * @property string|null $extendAll
 * @property string|null $extendFromCurrentTime
 * @property string|null $extendTime
 * @property string|null $fbOgDescription
 * @property string|null $fbOgImageUrl
 * @property string|null $fbOgTitle
 * @property string|null $hideUnsoldLots
 * @property string|null $hpTaxSchemaId
 * @property string|int|null $id
 * @property string|null $image
 * @property array|null $increments
 * @property string|null $invoiceLocation
 * @property string|null $invoiceLocationId
 * @property string|null $invoiceNotes
 * @property string|null $listingOnly
 * @property string|null $liveEndDate
 * @property string|null $liveViewAccess
 * @property string|null $location
 * @property string|null $locationId
 * @property string|null $lotBiddingHistoryAccess
 * @property string|null $lotBiddingInfoAccess
 * @property string|null $lotDetailsAccess
 * @property string|null $lotOrderPrimaryCustField
 * @property string|null $lotOrderPrimaryCustFieldId
 * @property string|null $lotOrderPrimaryIgnoreStopWords
 * @property string|null $lotOrderPrimaryType
 * @property string|null $lotOrderQuaternaryCustField
 * @property string|null $lotOrderQuaternaryCustFieldId
 * @property string|null $lotOrderQuaternaryIgnoreStopWords
 * @property string|null $lotOrderQuaternaryType
 * @property string|null $lotOrderSecondaryCustField
 * @property string|null $lotOrderSecondaryCustFieldId
 * @property string|null $lotOrderSecondaryIgnoreStopWords
 * @property string|null $lotOrderSecondaryType
 * @property string|null $lotOrderTertiaryCustField
 * @property string|null $lotOrderTertiaryCustFieldId
 * @property string|null $lotOrderTertiaryIgnoreStopWords
 * @property string|null $lotOrderTertiaryType
 * @property string|null $lotStartGapTime
 * @property string|null $lotStartingBidAccess
 * @property string|null $lotWinningBidAccess
 * @property string|null $lotsPerInterval
 * @property string|null $manualBidderApprovalOnly
 * @property string|null $maxOutstanding
 * @property string|null $name
 * @property string|null $nextBidButton
 * @property string|null $noLowerMaxbid
 * @property string|null $notShowUpcomingLots
 * @property string|null $notifyAbsenteeBidders
 * @property string|null $notifyXLots
 * @property string|null $notifyXMinutes
 * @property string|null $onlyOngoingLots
 * @property string|null $parcelChoice
 * @property string|null $paymentTrackingCode
 * @property string|null $postAucImportPremium
 * @property string|null $publishDate
 * @property string|null $published
 * @property string|null $requireLotChangeConfirmation
 * @property string|null $reserveMetNotice
 * @property string|null $reserveNotMetNotice
 * @property string|null $reverse
 * @property string|null $rtbdName
 * @property string|null $saleFullNo
 * @property string|null $saleGroup
 * @property string|null $saleNum
 * @property string|null $saleNumExt
 * @property string|null $servicesTaxSchemaId
 * @property string|null $seoMetaDescription
 * @property string|null $seoMetaKeywords
 * @property string|null $seoMetaTitle
 * @property string|null $shippingInfo
 * @property string|null $simultaneous
 * @property object|null $specificEventLocation
 * @property object|null $specificInvoiceLocation
 * @property string|null $staggerClosing
 * @property string|null $startBiddingDate
 * @property string|null $startClosingDate
 * @property string|null $startRegisterDate
 * @property string|null $streamDisplay
 * @property string|null $streamDisplayValue
 * @property string|null $suggestedStartingBid
 * @property string|null $syncKey
 * @property string|null $syncNamespaceId
 * @property string|null $takeMaxBidsUnderReserve
 * @property string|null $taxDefaultCountry
 * @property string|null $taxPercent
 * @property array|null $taxStates;
 * @property string|null $termsAndConditions
 * @property string|null $testAuction
 * @property string|null $textMsgNotification
 * @property string|null $timezone
 * @property string|null $unpublishDate
 * @property string|null $updateAuctionItemDate
 * @property string|null $wavebidAuctionGuid
 */
class AuctionMakerInputDto extends InputDto
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
