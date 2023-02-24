<?php
/**
 * SAM-11260: Stacked Tax. Invoice Management pages: Implement unit tests
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 25, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\InvoiceAdditional\Load;

use InvoiceAdditional;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepositoryCreateTrait;

/**
 * Class InvoiceAdditionalLoader
 * @package Sam\Invoice\StackedTax\InvoiceAdditional\Load
 */
class InvoiceAdditionalLoader extends CustomizableClass
{
    use InvoiceAdditionalReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadByPaymentId(?int $paymentId, bool $isReadOnlyDb = false): ?InvoiceAdditional
    {
        if (!$paymentId) {
            return null;
        }
        $invoiceAdditional = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterPaymentId($paymentId)
            ->loadEntity();
        return $invoiceAdditional;
    }

    /**
     * @param int|null $invoiceId
     * @param bool $isReadOnlyDb
     * @return InvoiceAdditional[]
     */
    public function loadByInvoiceId(?int $invoiceId, bool $isReadOnlyDb = false): array
    {
        if (!$invoiceId) {
            return [];
        }
        $invoiceAdditionals = $this->createInvoiceAdditionalReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterInvoiceId($invoiceId)
            ->filterActive(true)
            ->loadEntities();
        return $invoiceAdditionals;
    }
}
