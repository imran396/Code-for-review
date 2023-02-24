<?php
/**
 * SAM-10996: Stacked Tax. New Invoice Edit page: Invoiced user billing and shipping sections
 * SAM-11831: Stacked Tax: Validation is missing at billing email and billing/shipping phone/fax number at invoice edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate;

use Sam\PhoneNumber\PhoneNumberHelperAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\BillingInfoEditingInput as Input;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit\Validate\BillingInfoEditingValidationResult as Result;

/**
 * Class BillingInfoEditingValidator
 * @package Sam\View\Admin\Form\InvoiceEditForm\BillingInfoPanel\Edit
 */
class BillingInfoEditingValidator extends CustomizableClass
{
    use PhoneNumberHelperAwareTrait;

    /**
     * Class instantiation method
     * @return $this
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
            $input->billingEmail
            && !EmailAddressChecker::new()->isEmail($input->billingEmail)
        ) {
            $result->addError(Result::ERR_BILLING_EMAIL_FORMAT);
        }

        if (
            $input->billingPhone
            && !$this->getPhoneNumberHelper()->isValid($input->billingPhone)
        ) {
            $result->addWarning(Result::WARN_PHONE_FORMAT);
        }

        if (
            $input->billingFax
            && !$this->getPhoneNumberHelper()->isValid($input->billingFax)
        ) {
            $result->addWarning(Result::WARN_FAX_FORMAT);
        }

        return $result;
    }

}
