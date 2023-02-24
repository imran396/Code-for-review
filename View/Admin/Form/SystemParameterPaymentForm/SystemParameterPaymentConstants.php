<?php
/**
 * SAM-6914: Move sections' logic to separate Panel classes at Manage settings system parameters invoicing and payment page (/admin/manage-system-parameter/invoicing-and-payment)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Yura Vakulenko
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           11-10, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */


namespace Sam\View\Admin\Form\SystemParameterPaymentForm;


/**
 * Class SystemParameterPaymentConstants
 * @package Sam\View\Admin\Form\SystemParameterPaymentForm
 */
class SystemParameterPaymentConstants
{
    // Constants for Credit Cards
    public const ORD_CREDIT_CARD_NAME = 'name';
    public const ORD_CREDIT_CARD_SURCHARGE = 'surcharge';
    public const ORD_CREDIT_CARD_MAIN_SURCHARGE = 'main_surcharge';
    public const ORD_CREDIT_CARD_DEFAULT = 'id';

    // Constants for Currencies
    public const ORD_CURRENCY_NAME = 'name';
    public const ORD_CURRENCY_SIGN = 'sign';
    public const ORD_CURRENCY_EX_RATE = 'ex_rate';
    public const ORD_CURRENCY_CODE = 'code';
    public const ORD_CURRENCY_DEFAULT = self::ORD_CURRENCY_NAME;
}
