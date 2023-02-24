<?php
/**
 * SAM-10664: Refactoring of settings system parameters storage - Move constants
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class SettingRtb
 * @package Sam\Core\Constants
 */
class SettingRtb
{
    // Buy Now Restriction for Live (setting_rtb.buy_now_restriction)
    public const BNLR_AUCTION_STARTED = 'AS';
    public const BNLR_LOT_STARTED = 'LS';
    /** @var string[] */
    public const BUY_NOW_LIVE_RESTRICTIONS = [self::BNLR_AUCTION_STARTED, self::BNLR_LOT_STARTED];

    public const DIP_NO_IMAGE_PREVIEW = 0;
    public const DIP_DEFAULT_IMAGE_PREVIEW = 1;
    public const DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_BIDDERS = 2;
    public const DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_VIEWERS = 3;

    public const DIP_PREVIEW_ENABLED_OPTIONS = [
        self::DIP_DEFAULT_IMAGE_PREVIEW,
        self::DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_BIDDERS,
        self::DIP_THUMBNAIL_IMAGE_PREVIEW_FOR_VIEWERS
    ];
}
