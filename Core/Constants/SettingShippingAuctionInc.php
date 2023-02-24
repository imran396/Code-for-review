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
 * Class SettingShippingAuctionInc
 * @package Sam\Core\Constants
 */
class SettingShippingAuctionInc
{
    // AucIncMethod
    public const AIM_SINGLE_SELLER = 'SS';
    public const AIM_UNLIMITED_SELLER = 'XS';
    /** @var string[] */
    public const AUC_INC_METHOD_NAMES = [
        self::AIM_SINGLE_SELLER => 'SingleSeller',
        self::AIM_UNLIMITED_SELLER => 'UnlimitedSeller',
    ];
}
