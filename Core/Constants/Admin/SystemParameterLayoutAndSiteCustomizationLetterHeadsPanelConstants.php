<?php
/**
 * SAM-9914: Move sections' logic to separate Panel classes at Manage settings system parameters layout and site customization page (/admin/manage-system-parameter/layout-and-site-customization)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 25, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLayoutAndSiteCustomizationLetterHeadsPanelConstants
 */
class SystemParameterLayoutAndSiteCustomizationLetterHeadsPanelConstants
{

    public const CID_FLA_INVOICE_LOGO = 'scf32';
    public const CID_FLA_SETTLEMENT_LOGO = 'scf33';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_FLA_INVOICE_LOGO => Constants\Setting::INVOICE_LOGO,
        self::CID_FLA_SETTLEMENT_LOGO => Constants\Setting::SETTLEMENT_LOGO,
    ];
}
