<?php
/**
 * SAM-10995: Stacked Tax. New Invoice Edit page: Initial layout and header section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate;

use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Validate\InvoiceExistenceCheckerAwareTrait;
use Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Dto\InvoiceHeaderInputDto;
use Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate\InvoiceHeaderValidationResult as Result;

/**
 * Class InvoiceHeaderValidator
 * @package Sam\View\Admin\Form\InvoiceEditForm\HeaderPanel\Edit\Validate
 */
class InvoiceHeaderValidator extends CustomizableClass
{
    use InvoiceExistenceCheckerAwareTrait;
    use InvoiceLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function validate(InvoiceHeaderInputDto $input, int $invoiceId): Result
    {
        $result = Result::new()->construct();
        $invoiceNo = trim($input->invoiceNo);
        if (!$invoiceNo) {
            $result->addError(Result::ERR_INVOICE_NO_REQUIRED);
        }

        $invoiceNo = Cast::toInt($invoiceNo, Constants\Type::F_INT_POSITIVE);
        if (!$invoiceNo) {
            $result->addError(Result::ERR_INVOICE_NO_INVALID);
        }

        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        if (
            $invoiceNo
            && $invoiceNo !== $invoice->InvoiceNo
            && $this->getInvoiceExistenceChecker()
                ->existByInvoiceNo($invoiceNo, $invoice->AccountId, [$invoiceId], true)
        ) {
            $result->addError(Result::ERR_INVOICE_NO_EXISTS);
        }
        return $result;
    }
}
