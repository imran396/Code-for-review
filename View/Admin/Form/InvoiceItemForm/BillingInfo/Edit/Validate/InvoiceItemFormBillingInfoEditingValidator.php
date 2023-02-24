<?php
/**
 * SAM-10898: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Billing Info and Shipping Info management
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

namespace Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Validate;

use Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Common\InvoiceItemFormBillingInfoEditingInput as Input;
use Sam\Core\Email\Validate\EmailAddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Admin\Form\InvoiceItemForm\BillingInfo\Edit\Validate\InvoiceItemFormBillingInfoEditingValidationResult as Result;

class InvoiceItemFormBillingInfoEditingValidator extends CustomizableClass
{
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
     * @return InvoiceItemFormBillingInfoEditingValidationResult
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
        return $result;
    }

}
