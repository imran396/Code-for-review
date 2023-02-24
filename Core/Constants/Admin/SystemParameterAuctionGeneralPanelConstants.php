<?php
/**
 * SAM-10007: Move sections' logic to separate Panel classes at Manage settings system parameters auction page (/admin/manage-system-parameter/auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 28, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAuctionGeneralPanelConstants
 */
class SystemParameterAuctionGeneralPanelConstants
{
    public const CID_CHK_ONLY_ONE_REG_EMAIL = 'scf109';
    public const CID_CHK_SHOW_LOW_ESTIMATE = 'scf36';
    public const CID_CHK_SHOW_HIGH_ESTIMATE = 'scf37';
    public const CID_CHK_SHOW_COUNTDOWN_SECONDS = 'scf44';
    public const CID_CHK_CONFIRM_TIMED_BID = 'aof7';
    public const CID_TXT_CONFIRM_TIMED_BID_TEXT = 'aof8';
    public const CID_TXT_BID_TRACK_CODE = 'aof14';
    public const CID_CHK_GA_BID_TRACK = 'aof17';
    public const CID_CHK_PLACE_BID_REQUIRE_CC = 'aof52';
    public const CID_LST_ON_AUCTION_REGISTRATION = 'aof63';
    public const CID_TXT_ON_AUCTION_REGISTRATION_AMOUNT = 'aof64';
    public const CID_TXT_ON_AUCTION_REGISTRATION_EXPIRES = 'aof65';
    public const CID_CHK_ALLOW_MULTIBIDS = 'aof108';
    public const CID_CHK_CONFIRM_MULTIBIDS = 'aof70';
    public const CID_TXT_CONFIRM_MULTIBIDS = 'aof71';
    public const CID_CHK_REQUIRE_ON_INC_BIDS = 'scf104';
    public const CID_CHK_INLINE_BID_CONFIRM = 'scf106';
    public const CID_CHK_DISPLAY_ITEM_NUM = 'spbf70';
    public const CID_RAD_BIDDER_INFO = 'scf47';
    public const CID_CHK_ALL_USER_REQUIRE_CC_AUTH = 'scf66';
    public const CID_TXT_BLACKLIST_PHRASE = 'scf99';
    public const CID_RAD_AUCTION_DOMAIN_MODE = 'aof72';
    public const CID_CHK_FORCE_MAIN_ACCOUNT_DOMAIN_MODE = 'uof122';
    public const CID_RAD_EXECUTE_MODE = 'c6';
    public const CID_CHK_REQUIRE_REENTER_CC = 'c7';
    public const CID_CHK_ENABLE_SECOND_CHANCE = 'aof10';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_ONLY_ONE_REG_EMAIL => Constants\Setting::ONLY_ONE_REG_EMAIL,
        self::CID_CHK_SHOW_LOW_ESTIMATE => Constants\Setting::SHOW_LOW_EST,
        self::CID_CHK_SHOW_HIGH_ESTIMATE => Constants\Setting::SHOW_HIGH_EST,
        self::CID_CHK_SHOW_COUNTDOWN_SECONDS => Constants\Setting::SHOW_COUNTDOWN_SECONDS,
        self::CID_CHK_CONFIRM_TIMED_BID => Constants\Setting::CONFIRM_TIMED_BID,
        self::CID_TXT_CONFIRM_TIMED_BID_TEXT => Constants\Setting::CONFIRM_TIMED_BID_TEXT,
        self::CID_TXT_BID_TRACK_CODE => Constants\Setting::BID_TRACKING_CODE,
        self::CID_CHK_GA_BID_TRACK => Constants\Setting::GA_BID_TRACKING,
        self::CID_CHK_PLACE_BID_REQUIRE_CC => Constants\Setting::PLACE_BID_REQUIRE_CC,
        self::CID_LST_ON_AUCTION_REGISTRATION => Constants\Setting::ON_AUCTION_REGISTRATION,
        self::CID_TXT_ON_AUCTION_REGISTRATION_AMOUNT => Constants\Setting::ON_AUCTION_REGISTRATION_AMOUNT,
        self::CID_TXT_ON_AUCTION_REGISTRATION_EXPIRES => Constants\Setting::ON_AUCTION_REGISTRATION_EXPIRES,
        self::CID_CHK_ALLOW_MULTIBIDS => Constants\Setting::ALLOW_MULTIBIDS,
        self::CID_CHK_CONFIRM_MULTIBIDS => Constants\Setting::CONFIRM_MULTIBIDS,
        self::CID_TXT_CONFIRM_MULTIBIDS => Constants\Setting::CONFIRM_MULTIBIDS_TEXT,
        self::CID_CHK_REQUIRE_ON_INC_BIDS => Constants\Setting::REQUIRE_ON_INC_BIDS,
        self::CID_CHK_INLINE_BID_CONFIRM => Constants\Setting::INLINE_BID_CONFIRM,
        self::CID_CHK_DISPLAY_ITEM_NUM => Constants\Setting::DISPLAY_ITEM_NUM,
        self::CID_RAD_BIDDER_INFO => Constants\Setting::DISPLAY_BIDDER_INFO,
        self::CID_CHK_ALL_USER_REQUIRE_CC_AUTH => Constants\Setting::ALL_USER_REQUIRE_CC_AUTH,
        self::CID_TXT_BLACKLIST_PHRASE => Constants\Setting::BLACKLIST_PHRASE,
        self::CID_RAD_AUCTION_DOMAIN_MODE => Constants\Setting::AUCTION_DOMAIN_MODE,
        self::CID_CHK_FORCE_MAIN_ACCOUNT_DOMAIN_MODE => Constants\Setting::FORCE_MAIN_ACCOUNT_DOMAIN_MODE,
        self::CID_RAD_EXECUTE_MODE => Constants\Setting::ON_AUCTION_REGISTRATION_AUTO,
        self::CID_CHK_REQUIRE_REENTER_CC => Constants\Setting::REQUIRE_REENTER_CC,
        self::CID_CHK_ENABLE_SECOND_CHANCE => Constants\Setting::ENABLE_SECOND_CHANCE,
    ];

    public const CLASS_BLK_CHECKBOX = 'checkBox';
    public const CLASS_BLK_CHECKMARK = 'checkmark';
    public const CLASS_BLK_CONFIRM_TIMED = 'confirm-timed';
    public const CLASS_BLK_MULTI_BIDS = 'multi-bids';
    public const CLASS_BLK_ON_AUCTION_REGISTRATION = 'on-auction-registration';
    public const CLASS_CHK_EDIT_LINK = 'editlink';
}
