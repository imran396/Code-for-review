<?php
/**
 * SAM-10664: Refactoring of settings system parameters storage - Move constants
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettingAuction
 * @package Sam\Core\Constants
 */
class SettingAuction
{
    // Absentee Bid Display (setting_auction.absentee_bid_display)
    public const ABD_DO_NOT_DISPLAY = 'N';
    public const ABD_NUMBER_OF_ABSENTEE = 'NA';
    public const ABD_NUMBER_OF_ABSENTEE_LINK = 'NL';
    public const ABD_NUMBER_OF_ABSENTEE_HIGH = 'NH';

    /** @var string[] */
    public const ABSENTEE_BID_DISPLAY_SOAP_VALUES = [
        self::ABD_DO_NOT_DISPLAY => 'DoNotDisplay',
        self::ABD_NUMBER_OF_ABSENTEE => 'NumberOfAbsentee',
        self::ABD_NUMBER_OF_ABSENTEE_LINK => 'NumberOfAbsenteeLink',
        self::ABD_NUMBER_OF_ABSENTEE_HIGH => 'NumberOfAbsenteeHigh',
    ];
    /** @var string[] */
    public const ABSENTEE_BID_DISPLAY_OPTIONS = [
        self::ABD_DO_NOT_DISPLAY => "Don't display live absentee bids",
        self::ABD_NUMBER_OF_ABSENTEE => "Display number of bids only",
        self::ABD_NUMBER_OF_ABSENTEE_LINK => "Display number of bids and bid amounts / bidder numbers",
        self::ABD_NUMBER_OF_ABSENTEE_HIGH => "Display current high absentee bid",
    ];

    /** @var string[] */
    public const DISPLAY_DETAILED_BIDS_INFO_MODES = [
        self::ABD_NUMBER_OF_ABSENTEE_LINK,
        self::ABD_NUMBER_OF_ABSENTEE_HIGH,
    ];

    // Shipping info box (setting_auction.shipping_info_box)
    public const SIB_AUCTION = 1;
    public const SIB_AUCTION_LOT = 2;
    public const SIB_LOT = 3;
    public const SIB_DISABLED = 4;

    /** @var string[] */
    public const SHIPPING_INFO_TYPES = [
        self::SIB_AUCTION => 'Auction information',
        self::SIB_AUCTION_LOT => 'Auction information & lot details',
        self::SIB_LOT => 'Lot details',
        self::SIB_DISABLED => 'Disable',
    ];

    // Display bidder info (setting_auction.display_bidder_info)
    public const DBI_NUMBER = 'BN';
    public const DBI_USERNAME = 'UN';
    public const DBI_COMPANY_NAME = 'CN';
    /** @var string[] */
    public const DISPLAY_BIDDER_INFOS = [self::DBI_NUMBER, self::DBI_USERNAME, self::DBI_COMPANY_NAME];
    /** @var string[] */
    public const DISPLAY_BIDDER_INFO_NAMES = [
        self::DBI_NUMBER => 'Bidder #',
        self::DBI_USERNAME => 'Username',
        self::DBI_COMPANY_NAME => 'Company Name',
    ];

    public const AUCTION_LINK_TO_INFO = 1;
    public const AUCTION_LINK_TO_CATALOG = 2;
    public const AUCTION_LINK_TO_FIRST_LOT = 3;

    /** @var string[] */
    public const AUCTION_LINKS_TO_NAMES = [
        self::AUCTION_LINK_TO_INFO => 'Info',
        self::AUCTION_LINK_TO_CATALOG => 'Catalog',
        self::AUCTION_LINK_TO_FIRST_LOT => 'First lot',
    ];

    // Assigned lots restriction (setting_auction.assigned_lots_restriction)
    public const ALR_NONE = 0;
    public const ALR_WARNING = 1;
    public const ALR_BLOCK = 2;
    /** @var int[] */
    public const ASSIGNED_LOTS_RESTRICTIONS = [self::ALR_NONE, self::ALR_WARNING, self::ALR_BLOCK];
    /** @var string[] */
    public const ASSIGNED_LOTS_RESTRICTION_NAMES = [
        self::ALR_NONE => 'None',
        self::ALR_WARNING => 'Warning',
        self::ALR_BLOCK => 'Block Completely',
    ];
}
