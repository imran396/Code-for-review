<?php
/**
 * SAM-9454: Refactor Invoice Line item editor for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Dec 11, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: +1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\LineItem\Edit\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\LineItem\Validate\InvoiceLineItemExistenceCheckerAwareTrait;

/**
 * Class DataProvider
 * @package Sam\Invoice\Common\LineItem\Edit\Validate\Internal\Load
 */
class DataProvider extends CustomizableClass
{
    use InvoiceLineItemExistenceCheckerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceLineItemId
     * @return bool
     */
    public function existById(int $invoiceLineItemId): bool
    {
        return $this->getInvoiceLineItemExistenceChecker()->existById($invoiceLineItemId);
    }

    /**
     * @param string $label
     * @param int $accountId
     * @param array $skipIds
     * @return bool
     */
    public function existByLabelAndAccount(string $label, int $accountId, array $skipIds): bool
    {
        return $this->getInvoiceLineItemExistenceChecker()->existByLabelAndAccount($label, $accountId, $skipIds);
    }
}
