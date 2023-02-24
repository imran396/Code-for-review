<?php
/**
 * SAM-11831: Stacked Tax: Validation is missing at billing email and billing/shipping phone/fax number at invoice edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 10, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\PhoneNumber\PhoneNumberHelperAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\ShippingInfoEditingInput as Input;
use Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate\ShippingInfoEditingValidationResult as Result;

/**
 * Class ShippingInfoEditingValidator
 * @package Sam\View\Admin\Form\InvoiceEditForm\ShippingInfoPanel\Edit\Validate
 */
class ShippingInfoEditingValidator extends CustomizableClass
{
    use PhoneNumberHelperAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Input $input
     * @return Result
     */
    public function validate(Input $input): Result
    {
        $result = Result::new()->construct();

        if (
            $input->shippingPhone
            && !$this->getPhoneNumberHelper()->isValid($input->shippingPhone)
        ) {
            $result->addWarning(Result::WARN_PHONE_FORMAT);
        }

        if (
            $input->shippingFax
            && !$this->getPhoneNumberHelper()->isValid($input->shippingFax)
        ) {
            $result->addWarning(Result::WARN_FAX_FORMAT);
        }

        return $result;
    }
}
