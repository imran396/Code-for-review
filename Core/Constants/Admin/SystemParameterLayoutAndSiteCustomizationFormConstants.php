<?php
/**
 * SAM-4696 : Page constants
 * https://bidpath.atlassian.net/browse/SAM-4696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/24/19
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 =415 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterLayoutAndSiteCustomizationFormConstants
 */
class SystemParameterLayoutAndSiteCustomizationFormConstants
{
    public const CID_BTN_SAVE_CHANGES = 'scf1';
    public const CID_BTN_CANCEL_CHANGES = 'scf46';
    public const CID_TXT_LOT_ITEM_TEMPLATE = 'scf48';
    public const CID_TXT_RTB_TEMPLATE = 'scf49';
    public const CID_TXT_EXP_SECT = 'scf55';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_LOOK_AND_FEEL = 'pnlSystemParameterLayoutAndSiteCustomizationLookAndFeel';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_SEO_SETTINGS = 'pnlSystemParameterLayoutAndSiteCustomizationSeoSettings';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_LETTER_HEADS = 'pnlSystemParameterLayoutAndSiteCustomizationLetterHeads';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_PUBLIC_MAIN_MENU = 'pnlSystemParameterLayoutAndSiteCustomizationPublicMainMenu';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_NUMBER_FORMATTING = 'pnlSystemParameterLayoutAndSiteCustomizationNumberFormatting';
    public const CID_PNL_SYSTEM_PARAMETER_LAYOUT_AND_SITE_CUSTOMIZATION_FAVICON = 'pnlSystemParameterLayoutAndSiteCustomizationFavicon';


    public const FORM_TO_PROPERTY_MAP = [
        self::CID_TXT_LOT_ITEM_TEMPLATE => Constants\Setting::LOT_ITEM_DETAIL_TEMPLATE,
        self::CID_TXT_RTB_TEMPLATE => Constants\Setting::RTB_DETAIL_TEMPLATE,
    ];

    public const CLASS_BLK_LEG_ALL = 'legall';
    public const CLASS_BLK_LEGEND = 'legend_div';
    public const CLASS_LNK_CLOSE = 'close';
    public const CLASS_LNK_OPEN = 'open';
}
