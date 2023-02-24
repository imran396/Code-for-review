<?php
/**
 * SAM-10477: Reject assigning both BP rules on the same level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class BuyersPremiumPanelConstants
 * @package Sam\Core\Constants\Admin
 */
class BuyersPremiumPanelConstants
{
    public const CID_BTN_REMOVE_TPL = 'bbrem%s';
    public const CID_LST_MODE_TPL = 'lbm%s';
    public const CID_TXT_FIXED_AMOUNT_TPL = 'tbf%s';
    public const CID_TXT_PERCENT_TPL = 'tbp%s';
    public const CID_TXT_START_AMOUNT_TPL = 'tbsa%s';
    public const CID_LST_CALCULATION_METHOD = 'bpp1';
    public const CID_TXT_ADDITIONAL_BP_INTERNET = 'bbp2';
    public const CID_DTG_RANGE = 'bpp3';
    public const CID_BTN_ADD_ROW = 'bpp4';
}
