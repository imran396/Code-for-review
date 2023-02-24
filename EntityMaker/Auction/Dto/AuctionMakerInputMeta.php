<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of Auction.
 *
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

use Sam\Core\Service\CustomizableClass;

/**
 * @package Sam\EntityMaker\Auction
 */
class AuctionMakerInputMeta extends CustomizableClass
{
    /**
     * @var string
     * @soap-type-hint bool
     * @group For live / hybrid auctions only
     */
    public $aboveReserve;
    /**
     * @var string
     * @soap-type-hint bool
     * @group For live / hybrid auctions only
     */
    public $aboveStartingBid;
    /**
     * @var string DoNotDisplay|NumberOfAbsentee|NumberOfAbsenteeLink|NumberOfAbsenteeHigh
     * @group For live / hybrid auctions only
     * @soap-default-value DoNotDisplay
     */
    public $absenteeBidsDisplay;
    /**
     * @var string
     * @group For live / hybrid auctions only
     * @soap-type-hint float
     */
    public $additionalBpInternet;
    /**
     * List of additional currencies
     * @var \Sam\EntityMaker\Base\Data\Currency
     */
    public $additionalCurrencies;
    /**
     * @var string
     * @group For hybrid auctions only
     * @soap-default-value Y
     * @soap-type-hint bool
     */
    public $allowBiddingDuringStartGap;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $allowForceBid;
    /**
     * @var string AuctionAuctioneer.Id
     * @soap-type-hint int
     */
    public $auctionAuctioneerId;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $auctionCatalogAccess;
    /**
     * @var \Sam\EntityMaker\Base\Data\Field[]
     */
    public $auctionCustomFields;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     */
    public $auctionHeldIn;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $auctionInfoAccess;
    /**
     * @var string
     */
    public $auctionInfoLink;
    /**
     * @var string Active|Started|Paused|Closed|Archived|Deleted Paused only relevant for live auctions
     */
    public $auctionStatus;
    /**
     * @var string 1|2|6|3|5|4
     * @soap-type-hint int
     */
    public $auctionStatusId;
    /**
     * @var string
     * @soapExcluded
     */
    public $auctionType;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $auctionVisibilityAccess;
    /**
     * @var string For per auction AUTH/CAPT on registration
     * @soap-type-hint float
     */
    public $authorizationAmount;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $autoPopulateEmptyLotNum;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $autoPopulateLotFromCategory;
    /**
     * @var string yyyy-mm-dd hh:mm:ss Required for live/hybrid auctions
     * @group For live / hybrid auctions only
     * @soap-required-conditionally
     */
    public $biddingConsoleAccessDate;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $biddingPaused;
    /**
     * @var string
     */
    public $blacklistPhrase;
    /**
     * @var string sliding|tiered
     * @soap-default-value sliding
     */
    public $bpRangeCalculation;
    /**
     * @var string BP Rule short name
     */
    public $bpRule;
    /**
     * @var int
     */
    public $bpTaxSchemaId;
    /**
     * For optional per auction buyer's premium. One Premium range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Premium[]
     */
    public $buyersPremiums;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $ccThresholdDomestic;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $ccThresholdInternational;
    /**
     * @var string Simple|Advanced
     * @group For live auctions only
     */
    public $clerkingStyle;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $concatenateLotOrderColumns;
    /**
     * @var string Currency.Name
     */
    public $currency;
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
     * @group For timed auctions only
     * @soap-type-hint int
     */
    public $defaultLotPeriod;
    /**
     * @var string 5 character postal code
     */
    public $defaultLotPostalCode;
    /**
     * @var string
     */
    public $description;
    /**
     * @var string Per auction email contacts, comma separated
     */
    public $email;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     * @group For live / hybrid auctions only
     */
    public $endPrebiddingDate;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     */
    public $endRegisterDate;
    /**
     * @var string Per auction Sms
     */
    public $eventId;
    /**
     * @var string Location.Name
     */
    public $eventLocation;
    /**
     * @var string Location.Id
     * @soap-type-hint int
     */
    public $eventLocationId;
    /**
     * @var string Ongoing|Scheduled
     * @group For timed auctions only
     */
    public $eventType;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $excludeClosedLots;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $extendAll;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $extendFromCurrentTime;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $extendTime;
    /**
     * @var string
     */
    public $fbOgDescription;
    /**
     * @var string
     */
    public $fbOgImageUrl;
    /**
     * @var string
     */
    public $fbOgTitle;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $hideUnsoldLots;
    /**
     * @var int
     */
    public $hpTaxSchemaId;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * @var string
     */
    public $image;
    /**
     * For optional per auction increments. One Increment range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Increment[]
     */
    public $increments;
    /**
     * @var string
     */
    public $invoiceNotes;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $listingOnly;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $liveViewAccess;
    /**
     * @var string yyyy-mm-dd hh:mm:ss Required for live auctions
     * @soap-required-conditionally
     * @group For live auctions only
     */
    public $liveEndDate;
    /**
     * @var string Location.Name
     * @deprecated
     */
    public $location;
    /**
     * @var string Location.Id
     * @soap-type-hint int
     * @deprecated
     */
    public $locationId;
    /**
     * @var string Location.Name
     */
    public $invoiceLocation;
    /**
     * @var string Location.Id
     * @soap-type-hint int
     */
    public $invoiceLocationId;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $lotBiddingHistoryAccess;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $lotBiddingInfoAccess;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $lotDetailsAccess;
    /**
     * @var string 1|2|3|4|5
     * @soap-default-value 1
     * @soap-type-hint int
     */
    public $lotOrderPrimaryType;
    /**
     * @var string Required if lot order primary type is a custom field
     * @soap-required-conditionally
     */
    public $lotOrderPrimaryCustField;
    /**
     * @var string Required if lot order primary type is a custom field id
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotOrderPrimaryCustFieldId;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $lotOrderPrimaryIgnoreStopWords;
    /**
     * @var string 0|1|2|3|4|5
     * @soap-default-value 0
     * @soap-type-hint int
     */
    public $lotOrderSecondaryType;
    /**
     * @var string Required if lot order secondary type is a custom field
     * @soap-required-conditionally
     */
    public $lotOrderSecondaryCustField;
    /**
     * @var string Required if lot order secondary type is a custom field id
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotOrderSecondaryCustFieldId;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $lotOrderSecondaryIgnoreStopWords;
    /**
     * @var string 0|1|2|3|4|5
     * @soap-default-value 0
     * @soap-type-hint int
     */
    public $lotOrderTertiaryType;
    /**
     * @var string Required if lot order tertiary type is a custom field
     * @soap-required-conditionally
     */
    public $lotOrderTertiaryCustField;
    /**
     * @var string Required if lot order tertiary type is a custom field id
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotOrderTertiaryCustFieldId;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $lotOrderTertiaryIgnoreStopWords;
    /**
     * @var string 0|1|2|3|4|5
     * @soap-default-value 0
     * @soap-type-hint int
     */
    public $lotOrderQuaternaryType;
    /**
     * @var string Required if lot order quaternary type is a custom field
     * @soap-required-conditionally
     */
    public $lotOrderQuaternaryCustField;
    /**
     * @var string Required if lot order quaternary type is a custom field
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotOrderQuaternaryCustFieldId;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $lotOrderQuaternaryIgnoreStopWords;
    /**
     * @var string
     * @group For hybrid auctions only
     * @soap-default-value 30
     * @soap-type-hint int
     */
    public $lotStartGapTime;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $lotStartingBidAccess;
    /**
     * @var string VISITOR|USER|BIDDER|ADMIN
     * @soap-default-value VISITOR
     */
    public $lotWinningBidAccess;
    /**
     * @var string Required for time auctions if staggerClosing > 0
     * @group For timed auctions only
     * @soap-default-value 1
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $lotsPerInterval;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $manualBidderApprovalOnly;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $maxOutstanding;
    /**
     * @var string Required
     * @soap-required
     */
    public $name;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $nextBidButton;
    /**
     * @var string
     * @group For live / hybrid auctions only
     * @soap-type-hint bool
     */
    public $noLowerMaxbid;
    /**
     * @var string
     * @group For live / hybrid auctions only
     * @soap-type-hint bool
     */
    public $notifyAbsenteeBidders;
    /**
     * @var string
     * @group For live / hybrid auctions only
     */
    public $notifyXLots;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint int
     */
    public $notifyXMinutes;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $notShowUpcomingLots;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $onlyOngoingLots;
    /**
     * @var string
     * @group For live / hybrid auctions only
     * @soap-type-hint bool
     */
    public $parcelChoice;
    /**
     * @var string
     */
    public $paymentTrackingCode;
    /**
     * @var string %
     * @soap-type-hint float
     */
    public $postAucImportPremium;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     */
    public $publishDate;
    /**
     * @var string Deprecated (Use publishDate instead)
     * @soap-type-hint bool
     */
    public $published;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $requireLotChangeConfirmation;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $reserveNotMetNotice;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $reserveMetNotice;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $reverse;
    /**
     * @var string
     * @group For live / hybrid auctions only
     */
    public $rtbdName;
    /**
     * @var string
     */
    public $saleFullNo;
    /**
     * @var string
     */
    public $saleGroup;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $saleNum;
    /**
     * @var string
     */
    public $saleNumExt;
    /**
     * @var string
     */
    public $seoMetaDescription;
    /**
     * @var string
     */
    public $seoMetaKeywords;
    /**
     * @var string
     */
    public $seoMetaTitle;
    /**
     * @var int
     */
    public $servicesTaxSchemaId;
    /**
     * @var string
     */
    public $shippingInfo;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $simultaneous;
    /**
     * @var \Sam\EntityMaker\Base\Data\Location
     */
    public $specificEventLocation;
    /**
     * @var \Sam\EntityMaker\Base\Data\Location
     */
    public $specificInvoiceLocation;
    /**
     * @var string 0|1|2|5|10|15|20|25|30|35|40|45|50|55|60
     * @group For timed auctions only
     * @soap-type-hint int
     */
    public $staggerClosing;
    /**
     * @var string yyyy-mm-dd hh:mm:ss live/hybrid auctions are scheduled to start and timed auctions start closing. Empty for ongoing timed auctions
     */
    public $startClosingDate;
    /**
     * @var string <a href="/api/soap12?op=Timezone">Timezone</a>.Location Required if at least one date field is assigned
     * @soap-required-conditionally
     */
    public $timezone;
    /**
     * @var string Audio|Video
     * @group For live / hybrid auctions only
     */
    public $streamDisplay;
    /**
     * @var string
     * @group For live / hybrid auctions only
     */
    public $streamDisplayValue;
    /**
     * @var string yyyy-mm-dd hh:mm:ss Required for timed, scheduled auction
     * @soap-required-conditionally
     */
    public $startBiddingDate;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     */
    public $startRegisterDate;
    /**
     * @var string
     * @group For live / hybrid auctions only
     * @soap-type-hint bool
     */
    public $suggestedStartingBid;
    /**
     * @var string AuctionSyncKey
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $takeMaxBidsUnderReserve;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     */
    public $taxDefaultCountry;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $taxPercent;
    /**
     * List of tax states
     * @var \Sam\EntityMaker\Base\Data\State
     */
    public $taxStates;
    /**
     * @var string
     */
    public $termsAndConditions;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $testAuction;
    /**
     * @var string Per auction Sms
     */
    public $textMsgNotification;
    /**
     * @var string 1|2 1 - Apply auction's dates to items, 2 - Apply item's dates to auction
     * @group For timed auctions only
     * @soap-default-value 1
     * @soap-type-hint int
     */
    public $dateAssignmentStrategy;
    /**
     * @var string Available for Scheduled and DateAssignmentStrategy = 1
     * @group For timed auctions only
     * @soap-type-hint bool
     */
    public $updateAuctionItemDate;
    /**
     * @var string yyyy-mm-dd hh:mm:ss
     */
    public $unpublishDate;
    /**
     * @var string
     */
    public $wavebidAuctionGuid;
    /**
     * @var int
     */
    protected $clerkingStyleId;
    /**
     * @var int
     */
    protected $currencyId;
    /**
     * @var int
     */
    protected $eventTypeId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
