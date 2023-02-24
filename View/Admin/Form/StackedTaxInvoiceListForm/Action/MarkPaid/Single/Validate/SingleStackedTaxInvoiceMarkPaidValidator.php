<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate\SingleStackedTaxInvoiceMarkPaidValidationResult as Result;

/**
 * Class SingleStackedTaxInvoiceMarkPaidValidator
 * @package Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate
 */
class SingleStackedTaxInvoiceMarkPaidValidator extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(string $invoiceId): Result
    {
        $result = Result::new()->construct();
        $invoice = $this->getInvoiceLoader()
            ->clear()
            ->load((int)$invoiceId);
        if (!$invoice) {
            return $result->addError(Result::ERR_INVOICE_ABSENT);
        }

        if ($invoice->isDeleted()) {
            return $result->addError(Result::ERR_INVOICE_DELETED);
        }

        return $result;
    }

}
