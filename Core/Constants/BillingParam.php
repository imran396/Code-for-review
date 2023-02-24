<?php
/**
 * SAM-8963: Create constants for billing transaction parameters
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Constants;

/**
 * Class BillingParam
 * @package Sam\Core\Constants
 */
class BillingParam
{
    public const ACCOUNT_ID = 'AccountId';
    public const BILLING_ADDRESS = 'BAddress';
    public const BILLING_ADDRESS_2 = 'BAddress2';
    public const BILLING_ADDRESS_3 = 'BAddress3';
    public const BILLING_CITY = 'BCity';
    public const BILLING_COMPANY_NAME = 'BCompName';
    public const BILLING_COUNTRY = 'BCountry';
    public const BILLING_EMAIL = 'BEmail';
    public const BILLING_FAX = 'BFax';
    public const BILLING_FIRST_NAME = 'BFirstName';
    public const BILLING_LAST_NAME = 'BLastName';
    public const BILLING_PHONE = 'BPhone';
    public const BILLING_STATE = 'BState';
    public const BILLING_ZIP = 'BZip';
    public const CC_CODE = 'CCCode';
    public const CC_EXP_MONTH = 'CCExpMonth';
    public const CC_EXP_YEAR = 'CCExpYear';
    public const CC_NUMBER = 'CCNumber';
    public const CC_NUMBER_HASH = 'CCNumberHash';
    public const CC_TYPE = 'CCType';
    public const SHIPPING_ADDRESS = 'SAddress';
    public const SHIPPING_ADDRESS_2 = 'SAddress2';
    public const SHIPPING_ADDRESS_3 = 'SAddress3';
    public const SHIPPING_CITY = 'SCity';
    public const SHIPPING_COMPANY_NAME = 'SCompName';
    public const SHIPPING_COUNTRY = 'SCountry';
    public const SHIPPING_FAX = 'SFax';
    public const SHIPPING_FIRST_NAME = 'SFirstName';
    public const SHIPPING_LAST_NAME = 'SLastName';
    public const SHIPPING_PHONE = 'SPhone';
    public const SHIPPING_STATE = 'SState';
    public const SHIPPING_ZIP = 'SZip';
    public const AUTH_CAPTURE_CC_NUMBER = 'ccnum';
    public const AUTH_CAPTURE_CC_EXP = 'expiration';
    public const AUTH_CAPTURE_CC_CODE = 'ccv';
}
