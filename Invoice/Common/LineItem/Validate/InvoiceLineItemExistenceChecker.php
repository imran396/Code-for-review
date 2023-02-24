<?php
/**
 * SAM-4723: Invoice Line item editor
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/22/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepositoryCreateTrait;

/**
 * Class InvoiceLineItemExistenceChecker
 * @package Sam\Invoice\Common\LineItem\Validate
 */
class InvoiceLineItemExistenceChecker extends CustomizableClass
{
    use InvoiceLineItemReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $label
     * @param int $accountId
     * @param array $skipIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLabelAndAccount(
        string $label,
        int $accountId,
        array $skipIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        return $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterLabel($label)
            ->skipId($skipIds)
            ->exist();
    }

    /**
     * @param int $invoiceLineItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existById(int $invoiceLineItemId, bool $isReadOnlyDb = false): bool
    {
        return $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($invoiceLineItemId)
            ->exist();
    }
}
