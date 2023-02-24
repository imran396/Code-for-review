<?php
/**
 * Describe fields and their properties for soap documentation and wsdl for entity-maker of LotItem.
 *
 * There is no $accountId field, because we don't allow to set or change li.account_id externally.
 *
 * SAM-8837: Lot item entity maker module structural adjustments for v3-5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 29, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Dto;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemMakerInputMeta
 * @package Sam\EntityMaker\LotItem
 */
class LotItemMakerInputMeta extends CustomizableClass
{
    /**
     * @var string Item deleted, not accessible via UpdateItem
     * @soap-type-hint bool
     */
    public $active;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $additionalBpInternet;
    /**
     * @var string Auction.Id
     * @group Auction sold properties, depending on Namespace
     * @soap-type-hint int
     */
    public $auctionSoldId;
    /**
     * @var string Auction.SaleNo
     * @group Auction sold properties, depending on Namespace
     * @soap-type-hint int
     */
    public $auctionSoldName;
    /**
     * @var string AuctionSyncKey
     * @group Auction sold properties, depending on Namespace
     */
    public $auctionSoldSyncKey;
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
     * @var string
     * @soap-type-hint bool
     */
    public $buyNowSelectQuantityEnabled;
    /**
     * For optional per auction buyer's premium. One Premium range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Premium[]
     */
    public $buyersPremiums;
    /**
     * List category names
     * @var \Sam\EntityMaker\Base\Data\Category
     */
    public $categories;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $changes;
    /**
     * @var string User.Id
     * @group Consignor properties, depending on Namespace
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $consignorId;
    /**
     * @var string User.Username
     * @group Consignor properties, depending on Namespace
     * @soap-required-conditionally
     */
    public $consignorName;
    /**
     * @var string UserSyncKey
     * @group Consignor properties, depending on Namespace
     * @soap-required-conditionally
     */
    public $consignorSyncKey;
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
     * @soap-type-hint float
     */
    public $cost;
    /**
     * DateSold UTC date time
     * @var string yyyy-mm-dd hh:mm:ss
     */
    public $dateSold;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $description;
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
     * @var string Required if a lot has won status (sold, received)
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $hammerPrice;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $highEstimate;
    /**
     * @var string
     * @soap-type-hint int
     */
    public $id;
    /**
     * List of image names/URLs
     * @var \Sam\EntityMaker\Base\Data\Image
     */
    public $images;
    /**
     * @var string
     * @soap-type-hint float
     */
    public $increment;
    /**
     * For optional per lot increments. One Increment range needs to start at 0!
     * @var \Sam\EntityMaker\Base\Data\Increment[]
     */
    public $increments;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $internetBid;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $itemFullNum;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint int
     */
    public $itemNum;
    /**
     * @var string
     */
    public $itemNumExt;
    /**
     * @var string Location.Id or Location.Name
     */
    public $location;
    /**
     * @var \Sam\EntityMaker\Base\Data\Field[]
     */
    public $lotCustomFields;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $lotItemTaxArr;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $lowEstimate;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $name;
    /**
     * @var string No tax out of state
     * @soap-type-hint bool
     * @soap-default-value Y
     */
    public $noTaxOos;
    /**
     * @var string
     */
    public $notes;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $onlyTaxBp;
    /**
     * @var string
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
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $replacementPrice;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $reservePrice;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $returned;
    /**
     * @var string
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $salesTax;
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
     * @var \Sam\EntityMaker\Base\Data\Location
     */
    public $specificLocation;
    /**
     * @var string Required for timed auction when reverse is false, noBidding is false
     * @soap-required-conditionally
     * @soap-type-hint float
     */
    public $startingBid;
    /**
     * @var string LotItemSyncKey
     */
    public $syncKey;
    /**
     * @var string
     */
    public $syncNamespaceId;
    /**
     * @var string <a href="/api/soap12?op=CountriesAndStates">2 character country Code</a>
     */
    public $taxDefaultCountry;
    /**
     * @var string
     * @soap-type-hint bool
     */
    public $taxExempt;
    /**
     * List of tax states
     * @var \Sam\EntityMaker\Base\Data\State
     */
    public $taxStates;
    /**
     * @var string
     * @soap-required-conditionally
     */
    public $warranty;
    /**
     * @var string User.Id
     * @group Winning bidder properties, depending on Namespace
     * @soap-type-hint int
     */
    public $winningBidderId;
    /**
     * @var string User.Username
     * @group Winning bidder properties, depending on Namespace
     */
    public $winningBidderName;
    /**
     * @var string UserSyncKey
     * @group Winning bidder properties, depending on Namespace
     */
    public $winningBidderSyncKey;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
