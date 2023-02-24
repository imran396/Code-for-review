<?php
/**
 * SAM-10934: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Multiple Invoice Items validation and save (#invoice-save-2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Invoice\Common\Validate\InvoiceExistenceCheckerAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Common\InvoiceItemFormMultipleItemEditInput as Input;
use Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Validate\InvoiceItemFormMultipleItemEditValidationResult as Result;

/**
 * Class InvoiceItemFormMultipleItemEditValidator
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditMultipleItem\Validate
 */
class InvoiceItemFormMultipleItemEditValidator extends CustomizableClass
{
    use InvoiceExistenceCheckerAwareTrait;
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
        $result = Result::new()->construct();

        $invoice = $input->invoice;
        $invoiceNo = trim($input->invoiceNo);
        if (!$invoiceNo) {
            $result->addError(Result::ERR_INVOICE_NO_REQUIRED);
        }

        $invoiceNo = Cast::toInt($invoiceNo, Constants\Type::F_INT_POSITIVE);
        if (!$invoiceNo) {
            $result->addError(Result::ERR_INVOICE_NO_INVALID);
        }
        if (
            $invoiceNo
            && $invoiceNo !== $invoice->InvoiceNo
            && $this->getInvoiceExistenceChecker()
                ->existByInvoiceNo($invoiceNo, $invoice->AccountId, [$invoice->Id], true)
        ) {
            $result->addError(Result::ERR_INVOICE_NO_EXISTS);
        }

        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        foreach ($input->invoiceItems as $invoiceItemInput) {
            $hammerPriceInput = $nf->removeFormat($invoiceItemInput->hammerPriceFormatted);
            if (
                $hammerPriceInput !== ''
                && !NumberValidator::new()->isRealPositiveOrZero($hammerPriceInput)
            ) {
                $result->addRowError(Result::ERR_HAMMER_PRICE_INVALID, $invoiceItemInput->invoiceItemId);
            }

            $buyersPremiumInput = $nf->removeFormat($invoiceItemInput->buyersPremiumFormatted);
            if (
                $buyersPremiumInput !== ''
                && !NumberValidator::new()->isRealPositiveOrZero($buyersPremiumInput)
            ) {
                $result->addRowError(Result::ERR_BUYERS_PREMIUM_INVALID, $invoiceItemInput->invoiceItemId);
            }

            $taxPercentInput = $nf->removeFormat($invoiceItemInput->salesTaxFormatted);
            if (
                $taxPercentInput !== ''
                && !NumberValidator::new()->isRealPositiveOrZero($taxPercentInput)
            ) {
                $result->addRowError(Result::ERR_SALES_TAX_PERCENT_INVALID, $invoiceItemInput->invoiceItemId);
            }
        }

        $taxChargesRateInput = $nf->removeFormat($input->taxChargesRateFormatted);
        if (
            $taxChargesRateInput !== ''
            && !NumberValidator::new()->isRealPositiveOrZero($taxChargesRateInput)
        ) {
            $result->addError(Result::ERR_TAX_CHARGES_RATE_INVALID);
        }

        $taxFeesRateInput = $nf->removeFormat($input->taxFeesRateFormatted);
        if (
            $taxFeesRateInput !== ''
            && !NumberValidator::new()->isRealPositiveOrZero($taxFeesRateInput)
        ) {
            $result->addError(Result::ERR_TAX_FEES_RATE_INVALID);
        }

        return $result;
    }

}
