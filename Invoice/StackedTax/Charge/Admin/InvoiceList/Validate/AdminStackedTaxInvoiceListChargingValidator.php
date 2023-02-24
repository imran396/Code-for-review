<?php
/**
 * SAM-11525: Stacked Tax. Actions at the Admin Invoice List page. Extract general validation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate\AdminStackedTaxInvoiceListChargingValidationResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate\Internal\Load\DataProviderCreateTrait;

/**
 * Class AdminStackedTaxInvoiceListChargingValidator
 * @package Sam\Invoice\StackedTax\Charge\Admin\InvoiceList\Validate
 */
class AdminStackedTaxInvoiceListChargingValidator extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @return Result
     */
    public function validate(int $invoiceId): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();

        $invoice = $dataProvider->loadInvoice($invoiceId, true);
        if (!$invoice) {
            return $result->addError(Result::ERR_INVOICE_NOT_FOUND);
        }

        $result->setInvoice($invoice);

        if ($invoice->isDeleted()) {
            return $result->addError(Result::ERR_INVOICE_DELETED);
        }

        if ($invoice->isCanceled()) {
            return $result->addError(Result::ERR_INVOICE_CANCELED);
        }

        if (Floating::lteq($invoice->calcRoundedBalanceDue(), 0.)) {
            return $result->addError(Result::ERR_INVOICE_BALANCE_DUE_ZERO);
        }

        $isOperable = $dataProvider->isOperable($invoiceId);
        if (!$isOperable) {
            return $result->addError(Result::ERR_INVOICE_NOT_OPERABLE);
        }

        return $result;
    }

}
