<?php
/**
 * SAM-10007: Move sections' logic to separate Panel classes at Manage settings system parameters auction page (/admin/manage-system-parameter/auction)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 01, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterAuctionRegistrationPanelConstants
 */
class SystemParameterAuctionRegistrationPanelConstants
{
    public const CID_CHK_ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS = 'scf110';
    public const CID_CHK_HIDE_BIDDER_NUMBER = 'scf81';
    public const CID_CHK_REG_USE_HIGH_BIDDER = 'scf82';
    public const CID_CHK_REG_CONFIRM_AUTO_APPROVE = 'scf83';
    public const CID_LST_REG_REMINDER_EMAIL = 'scf103';
    public const CID_CHK_REG_CONFIRM_PAGE = 'scf108';
    public const CID_CHK_CONFIRM_ADDRESS_SALE = 'uof120';
    public const CID_CHK_CONFIRM_TERMS_AND_CONDITIONS_SALE = 'uof121';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS => Constants\Setting::ALLOW_MANUAL_BIDDER_FOR_FLAGGED_BIDDERS,
        self::CID_CHK_HIDE_BIDDER_NUMBER => Constants\Setting::HIDE_BIDDER_NUMBER,
        self::CID_CHK_REG_USE_HIGH_BIDDER => Constants\Setting::REG_USE_HIGH_BIDDER,
        self::CID_CHK_REG_CONFIRM_AUTO_APPROVE => Constants\Setting::REG_CONFIRM_AUTO_APPROVE,
        self::CID_LST_REG_REMINDER_EMAIL => Constants\Setting::REG_REMINDER_EMAIL,
        self::CID_CHK_REG_CONFIRM_PAGE => Constants\Setting::REG_CONFIRM_PAGE,
        self::CID_CHK_CONFIRM_ADDRESS_SALE => Constants\Setting::CONFIRM_ADDRESS_SALE,
        self::CID_CHK_CONFIRM_TERMS_AND_CONDITIONS_SALE => Constants\Setting::CONFIRM_TERMS_AND_CONDITIONS_SALE,
    ];
}
