<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and paymeynt page (/admin/manage-system-parameter/invoicing-and-payment)
 * SAM-6422: Refactoring each admin sections' logic into panel classes (class <className> extends QPanel)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Oct 16, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants\Admin;

/**
 * Class SystemParameterPaymentCreditCardsPanelConstants
 */
class SystemParameterPaymentCreditCardsPanelConstants
{
    public const CID_BTN_CC_ADD = 'ipf25';
    public const CID_DTG_CREDIT_CARDS = 'ipf26';
    public const CID_TXT_CC_NAME = 'ipf27';
    public const CID_TXT_CC_SURCHARGE = 'ipf123';
    public const CID_TXT_MAIN_CC_SURCHARGE = 'ipf208';
    public const CID_BTN_CC_SAVE = 'ipf28';
    public const CID_BTN_CC_CANCEL = 'ipf29';
    public const CID_CC_BTN_E_TPL = '%sbCce%s';
    public const CID_CC_BTN_D_TPL = '%sbCcd%s';
}
