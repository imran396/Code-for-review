<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 13, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentCurrenciesPanelConstants
 */
class SystemParameterPaymentCurrenciesPanelConstants
{
    public const CID_DTG_CURRENCIES = 'ipf11';
    public const CID_BTN_CURRENCY_ADD = 'ipf12';
    public const CID_TXT_CURRENCY_NAME = 'ipf13';
    public const CID_TXT_CURRENCY_SIGN = 'ipf14';
    public const CID_TXT_CURRENCY_EX_RATE = 'ipf15';
    public const CID_TXT_CURRENCY_CODE = 'ipf102';
    public const CID_BTN_CURRENCY_SAVE = 'ipf16';
    public const CID_BTN_CURRENCY_CANCEL = 'ipf17';
    public const CID_CHK_MULTI_CURRENCY = 'ipf64';
    public const CID_CURRENCY_BTN_E_TPL = '%sbCurre%s';
    public const CID_CURRENCY_BTN_D_TPL = '%sbCurrd%s';
    public const CID_CURRENCY_BTN_M_TPL = '%sbCurrm%s';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_CHK_MULTI_CURRENCY => Constants\Setting::MULTI_CURRENCY
    ];

    public const CLASS_BTN_EDIT_LINK = 'editlink';
    public const CLASS_BLK_CHECKBOX = 'checkBox';
    public const CLASS_BLK_CHECKMARK = 'checkmark';
}
