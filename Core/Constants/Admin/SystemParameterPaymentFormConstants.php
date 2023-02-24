<?php

namespace Sam\Core\Constants\Admin;

use Sam\Core\Constants;

/**
 * Class SystemParameterPaymentFormConstants
 */
class SystemParameterPaymentFormConstants
{
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_PAY_PAL = 'spPaymentPayPal';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_AUTH_NET = 'spPaymentAuthNet';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_PAY_TRACE = 'spPaymentPayTrace';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_EWAY = 'spPaymentEway';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_SMART_PAY = 'spPaymentSmartPay';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_NMI_CURRENCY_PAY = 'spPaymentNmiCurrencyPay';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_OPAYO = 'spPaymentOpayo';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_TRACKING = 'spPaymentTracking';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_CURRENCIES = 'spPaymentCurrencies';
    public const CID_PNL_SYSTEM_PARAMETER_PAYMENT_CREDIT_CARDS = 'spPaymentCreditCards';
    public const CID_BTN_SAVE_CHANGES = 'pf1';
    public const CID_BTN_CANCEL_CHANGES = 'pf62';

    public const CID_AUTHORIZATION_USE = 'CID_AUTHORIZATION_USE';
    public const CID_NO_AUTO_AUTHORIZATION = 'NO_AUTO_AUTHORIZATION';
    public const CID_PAYMENT_FORM = 'SystemParameterPaymentForm'; // Used in JS only. Should be the same as form class name. Generated internally by QCodo (see \QFormBase::RenderBegin).

    public const FORM_TO_PROPERTY_MAP = [
        self::CID_NO_AUTO_AUTHORIZATION => Constants\Setting::NO_AUTO_AUTHORIZATION,
        self::CID_AUTHORIZATION_USE => Constants\Setting::AUTHORIZATION_USE
    ];

    public const CLASS_BLK_LEG_ALL = 'legall';
    public const CLASS_BLK_LEGEND = 'legend_div';
    public const CLASS_LNK_CLOSE = 'close';
    public const CLASS_LNK_OPEN = 'open';
}
