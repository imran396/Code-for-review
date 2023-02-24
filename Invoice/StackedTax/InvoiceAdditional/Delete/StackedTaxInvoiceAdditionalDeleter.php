<?php
/**
 * StackedTaxInvoiceAdditionalDeleter
 *
 * SAM-10998: Stacked Tax. New Invoice Edit page: Services section
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 24, 2022
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\InvoiceAdditional\Delete;

use InvoiceAdditional;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceAdditional\InvoiceAdditionalWriteRepositoryAwareTrait;

/**
 * Class Deleter
 * @package Sam\Invoice\StackedTax\InvoiceAdditional\Delete
 */
class StackedTaxInvoiceAdditionalDeleter extends CustomizableClass
{
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoiceAdditionalWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function delete(InvoiceAdditional $invoiceAdditional, int $editorUserId): void
    {
        $invoiceAdditional->Active = false;
        $this->getInvoiceAdditionalWriteRepository()->saveWithModifier($invoiceAdditional, $editorUserId);
    }

    public function deleteById(int $invoiceAdditionalId, int $editorUserId): void
    {
        $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->load($invoiceAdditionalId);
        if ($invoiceAdditional) {
            $this->delete($invoiceAdditional, $editorUserId);
        }
    }

    public function deleteByPaymentId(int $paymentId, int $editorUserId): void
    {
        $invoiceAdditional = $this->getInvoiceAdditionalChargeManager()->loadByPaymentId($paymentId);
        if ($invoiceAdditional) {
            $this->delete($invoiceAdditional, $editorUserId);
        }
    }
}
