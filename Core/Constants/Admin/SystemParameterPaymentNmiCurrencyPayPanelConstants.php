<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 12, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentNmiCurrencyPayPanelConstants
 */
class SystemParameterPaymentNmiCurrencyPayPanelConstants
{
    public const CID_TXT_NMI_USERNAME = 'ipf175';
    public const CID_TXT_NMI_PASSWORD = 'ipf176';
    public const CID_RAD_NMI_MODE = 'ipf177';
    public const CID_CHK_NMI_VAULT = 'ipf178';
    public const CID_LST_NMI_VAULT_OPTION = 'ipf185';
    public const CID_CHK_NMI_AUTH_USE = 'ipf179';
    public const CID_CHK_CC_PAYMENT_NMI = 'ipf180';
    public const CID_CHK_ACH_PAYMENT_NMI = 'ipf181';
    public const CID_BTN_NMI_EDIT = 'ipf182';
    public const CID_LBL_NMI_USERNAME = 'ipf183';
    public const CID_LBL_NMI_PASSWORD = 'ipf184';
    public const CID_CHK_NO_AUTO_AUTH_TRANSACTION_NMI = 'ipf205';

    public const PANEL_TO_PROPERTY_MAP = [
        self::CID_TXT_NMI_USERNAME => Constants\Setting::NMI_USERNAME,
        self::CID_TXT_NMI_PASSWORD => Constants\Setting::NMI_PASSWORD,
        self::CID_RAD_NMI_MODE => Constants\Setting::NMI_MODE,
        self::CID_CHK_NMI_VAULT => Constants\Setting::NMI_VAULT,
        self::CID_LST_NMI_VAULT_OPTION => Constants\Setting::NMI_VAULT_OPTION,
        self::CID_CHK_CC_PAYMENT_NMI => Constants\Setting::CC_PAYMENT_NMI,
        self::CID_CHK_ACH_PAYMENT_NMI => Constants\Setting::ACH_PAYMENT_NMI,
    ];

    public const CLASS_BLK_VAULT_OPTION = 'vault-options';
}
