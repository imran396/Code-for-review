<?php
/**
 * SAM-3694:Invoice related repositories  https://bidpath.atlassian.net/browse/SAM-3694
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 August, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of InvoiceAdditional filtered by criteria
 * $invoiceAdditionalRepository = \Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $invoiceAdditionalRepository->exist();
 * $count = $invoiceAdditionalRepository->count();
 * $invoiceAdditionals = $invoiceAdditionalRepository->loadEntities();
 *
 * // Sample2. Load single InvoiceAdditional
 * $invoiceAdditionalRepository = \Sam\Storage\ReadRepository\Entity\InvoiceAdditional\InvoiceAdditionalReadRepository::new()
 *     ->filterId(1);
 * $invoiceAdditional = $invoiceAdditionalRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\InvoiceAdditional;

/**
 * Class InvoiceAdditionalReadRepository
 * @package Sam\Storage\ReadRepository\Entity\InvoiceAdditional
 */
class InvoiceAdditionalReadRepository extends AbstractInvoiceAdditionalReadRepository
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
