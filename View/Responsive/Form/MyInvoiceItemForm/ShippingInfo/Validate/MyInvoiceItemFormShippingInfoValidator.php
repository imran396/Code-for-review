<?php
/**
 * SAM-11027: Stacked Tax. Public My Invoice pages. Save user data before CC charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Validate;


use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Common\MyInvoiceItemFormShippingInfoInput as Input;
use Sam\View\Responsive\Form\MyInvoiceItemForm\ShippingInfo\Validate\MyInvoiceItemFormShippingInfoValidationResult as Result;

class MyInvoiceItemFormShippingInfoValidator extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();

        if (trim($input->contactType) === '') {
            $result->addError(Result::ERR_REQUIRED_CONTACT_TYPE);
        }

        if (trim($input->shippingFirstName) === '') {
            $result->addError(Result::ERR_REQUIRED_FIRST_NAME);
        }

        if (trim($input->shippingLastName) === '') {
            $result->addError(Result::ERR_REQUIRED_LAST_NAME);
        }

        if (trim($input->shippingCountryCode) === '') {
            $result->addError(Result::ERR_REQUIRED_CONTACT_TYPE);
        }

        if (trim($input->shippingAddress) === '') {
            $result->addError(Result::ERR_REQUIRED_ADDRESS);
        }

        $addressChecker = AddressChecker::new();
        if (
            $addressChecker->isUsa($input->shippingCountryCode)
            && !$input->shippingState
        ) {
            $result->addError(Result::ERR_INVALID_US_STATE);
        }

        if (
            $addressChecker->isCanada($input->shippingCountryCode)
            && !$input->shippingState
        ) {
            $result->addError(Result::ERR_INVALID_CANADA_STATE);
        }

        if (
            $addressChecker->isMexico($input->shippingCountryCode)
            && !$input->shippingState
        ) {
            $result->addError(Result::ERR_INVALID_MEXICO_STATE);
        }

        if (
            !$addressChecker->isCountryWithStates($input->shippingCountryCode)
            && trim($input->shippingState) === ''
        ) {
            $result->addError(Result::ERR_REQUIRED_CUSTOM_STATE);
        }

        if (trim($input->shippingCity) === '') {
            $result->addError(Result::ERR_REQUIRED_CITY);
        }

        if (trim($input->shippingZip) === '') {
            $result->addError(Result::ERR_REQUIRED_ZIP_CODE);
        }

        if ($result->hasError()) {
            return $result;
        }

        return $result->addSuccess(Result::OK_SUCCESS_VALIDATION);
    }
}
