<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common\InvoiceItemFormInvoiceEditInput as Input;
use Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate\InvoiceItemFormInvoiceEditValidationResult as Result;

/**
 * Class InvoiceItemFormInvoiceEditingValidator
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Validate
 */
class InvoiceItemFormInvoiceEditValidator extends CustomizableClass
{
    use NumberFormatterAwareTrait;

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
        $nf = $this->getNumberFormatter()->constructForInvoice($input->invoice->AccountId);

        $result = Result::new()->construct();
        $shipping = $nf->removeFormat($input->shippingAmountFormatted);
        if (
            $input->shippingAmountFormatted !== ''
            && !NumberValidator::new()->isReal($shipping) // allow negative refund
        ) {
            $result->addError(Result::ERR_SHIPPING_AMOUNT_INVALID);
        }

        foreach ($input->payments as $paymentInput) {
            $paymentAmount = $nf->removeFormat($paymentInput->amountFormatted);
            if (
                $paymentInput->amountFormatted !== ''
                && !NumberValidator::new()->isReal($paymentAmount) // allow negative refund
            ) {
                $result->addPaymentError(Result::ERR_PAYMENT_AMOUNT_INVALID, $paymentInput->index);
            }

            if (!$paymentInput->paymentMethodId) {
                $result->addPaymentError(Result::ERR_PAYMENT_METHOD_UNDEFINED, $paymentInput->index);
            }

            if (
                $paymentInput->paymentMethodId === Constants\Payment::PM_CC
                && !$paymentInput->creditCardId
            ) {
                $result->addPaymentError(Result::ERR_CREDIT_CARD_TYPE_UNDEFINED, $paymentInput->index);
            }
        }

        foreach ($input->charges as $chargeInput) {
            $chargeAmount = $nf->removeFormat($chargeInput->amountFormatted);
            if (
                $chargeInput->amountFormatted !== ''
                && !NumberValidator::new()->isReal($chargeAmount) // allow negative refund
            ) {
                $result->addChargeError(Result::ERR_CHARGE_AMOUNT_INVALID, $chargeInput->index);
            }
        }
        return $result;
    }
}
