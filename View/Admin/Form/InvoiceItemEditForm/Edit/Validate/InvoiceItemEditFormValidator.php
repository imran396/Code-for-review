<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Validate\Number\NumberValidator;
use Sam\Tax\StackedTax\Schema\Validate\TaxSchemaExistenceCheckerCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Dto\InvoiceItemEditFormInput;
use Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate\InvoiceItemEditFormValidationResult as Result;

/**
 * Class InvoiceItemEditFormValidator
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate
 */
class InvoiceItemEditFormValidator extends CustomizableClass
{
    use NumberFormatterAwareTrait;
    use TaxSchemaExistenceCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(InvoiceItemEditFormInput $input): Result
    {
        $invoiceAccountId = $input->invoiceAccountId;

        $result = Result::new()->construct(
            Constants\Lot::LOT_QUANTITY_MAX_PRECISION,
            Constants\Lot::LOT_QUANTITY_MAX_INTEGER_DIGITS,
            (int)$input->quantityDigits
        );

        $this->getNumberFormatter()->constructForInvoice($invoiceAccountId);

        $hammerPrice = $this->getNumberFormatter()->removeFormat($input->hammerPrice);
        if (!NumberValidator::new()->isRealPositiveOrZero($hammerPrice)) {
            $result->addError(Result::ERR_HAMMER_PRICE_INVALID);
        }

        $buyersPremium = $this->getNumberFormatter()->removeFormat($input->buyersPremium);
        if (!NumberValidator::new()->isRealPositiveOrZero($buyersPremium)) {
            $result->addError(Result::ERR_BUYERS_PREMIUM_INVALID);
        }

        $result = $this->checkQuantity($input, $result);

        if ($input->hpTaxSchemaId) {
            $isFound = $this->createTaxSchemaExistenceChecker()->existById($input->hpTaxSchemaId, $invoiceAccountId, Constants\StackedTax::AS_HAMMER_PRICE);
            if (!$isFound) {
                $result->addError(Result::ERR_HP_TAX_SCHEMA_INVALID);
            }
        }

        if ($input->bpTaxSchemaId) {
            $isFound = $this->createTaxSchemaExistenceChecker()->existById($input->bpTaxSchemaId, $invoiceAccountId, Constants\StackedTax::AS_BUYERS_PREMIUM);
            if (!$isFound) {
                $result->addError(Result::ERR_BP_TAX_SCHEMA_INVALID);
            }
        }

        return $result;
    }

    protected function checkQuantity(InvoiceItemEditFormInput $input, Result $result): Result
    {
        if (!$input->quantity) {
            return $result;
        }

        $quantity = $this->getNumberFormatter()->removeFormat($input->quantity);
        if (!NumberValidator::new()->isRealPositive($quantity)) {
            $result = $result->addError(Result::ERR_QUANTITY_INVALID);
            return $result;
        }

        [$integerPart, $fractionalPart] = explode('.', $quantity . '.');
        $fractionalPart = rtrim($fractionalPart, '0');
        $integerPartLength = strlen($integerPart);
        $fractionalPartLength = strlen($fractionalPart);

        if ($integerPartLength + $fractionalPartLength > Constants\Lot::LOT_QUANTITY_MAX_PRECISION) {
            $result = $result->addError(Result::ERR_QUANTITY_INVALID_PRECISION,);
            return $result;
        }

        if ($integerPartLength > Constants\Lot::LOT_QUANTITY_MAX_INTEGER_DIGITS) {
            $result->addError(Result::ERR_QUANTITY_TOO_BIG,);
            return $result;
        }

        $quantityScale = (int)$input->quantityDigits;
        if ($fractionalPartLength > $quantityScale) {
            $result->addError(Result::ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH);
        }
        return $result;
    }
}
