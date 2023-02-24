<?php
/**
 * SAM-10616: Supply uniqueness of invoice fields: invoice#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Lock\Internal\Detect;

use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Invoice\Common\Lock\Internal\Detect\UniqueInvoiceNoLockRequirementsCheckResult as Result;

/**
 * Class UniqueInvoiceNoLockRequirementsChecker
 * @package Sam\Invoice\Common\Lock\Internal\Detect
 */
class UniqueInvoiceNoLockRequirementsChecker extends CustomizableClass
{
    use InvoiceLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function check(?int $invoiceId, int|string $invoiceNoInput): Result
    {
        $result = Result::new()->construct($invoiceId, $invoiceNoInput);
        if (!$invoiceId) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_NEW_INVOICE_CREATED);
        }
        if ((string)$invoiceNoInput === '') {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_INVOICE_NO_MUST_BE_GENERATED);
        }
        $invoice = $this->getInvoiceLoader()->load($invoiceId);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }
        if ($invoice->InvoiceNo !== Cast::toInt($invoiceNoInput)) {
            return $result->addSuccess(Result::OK_LOCK_BECAUSE_INVOICE_NO_DIFFERS);
        }
        return $result->addInfo(Result::INFO_DO_NOT_LOCK_BECAUSE_INVOICE_NO_INPUT_EQUAL_TO_EXISTING);
    }
}
