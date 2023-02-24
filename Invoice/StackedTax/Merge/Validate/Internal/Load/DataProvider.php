<?php
/**
 * SAM-7978 : Decouple invoice merging service and apply unit tests
 * https://bidpath.atlassian.net/browse/SAM-7978
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Merge\Validate\Internal\Load;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\InvoiceLoader;
use Sam\Invoice\Common\Validate\InvoiceRelatedEntityValidatorAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Invoice\Legacy\Merge\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use InvoiceRelatedEntityValidatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $invoiceIds
     * @param bool $isReadOnlyDb
     * @return Invoice[]
     */
    public function loadInvoices(array $invoiceIds, bool $isReadOnlyDb = false): array
    {
        return InvoiceLoader::new()->loadByIds($invoiceIds, $isReadOnlyDb);
    }

    /**
     * @param int $selectedInvoiceId
     * @return bool
     */
    public function isValidRelatedEntity(int $selectedInvoiceId): bool
    {
        return $this->getInvoiceRelatedEntityValidator()
            ->setInvoiceId($selectedInvoiceId)
            ->validate();
    }
}

