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

namespace Sam\Invoice\Common\LineItem\Load;

use InvoiceLineItem;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\InvoiceLineItem\InvoiceLineItemReadRepositoryCreateTrait;

/**
 * Class InvoiceLineItemLoader
 * @package Sam\Invoice\Common\LineItem\Load
 */
class InvoiceLineItemLoader extends EntityLoaderBase
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
     * @param int|null $invoiceLineItemId invoice_line_item.id
     * @param bool $isReadOnlyDb
     * @return InvoiceLineItem|null
     */
    public function load(?int $invoiceLineItemId, bool $isReadOnlyDb = false): ?InvoiceLineItem
    {
        if (!$invoiceLineItemId) {
            return null;
        }
        $invoiceLineItem = $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($invoiceLineItemId)
            ->loadEntity();
        return $invoiceLineItem;
    }

    /**
     * @param string $label
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return InvoiceLineItem|null
     */
    public function loadByLabelAndAccount(string $label, int $accountId, bool $isReadOnlyDb = false): ?InvoiceLineItem
    {
        $invoiceLineItem = $this->createInvoiceLineItemReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterLabel($label)
            ->loadEntity();
        return $invoiceLineItem;
    }
}
