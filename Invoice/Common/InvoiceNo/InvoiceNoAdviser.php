<?php
/**
 * SAM-4367: Invoice No Adviser class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           22.01.2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Invoice\Common\InvoiceNo;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Invoice\InvoiceReadRepositoryCreateTrait;

/**
 * Class InvoiceNoAdviser
 */
class InvoiceNoAdviser extends CustomizableClass
{
    use InvoiceReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Get next available invoice number
     *
     * @param int $accountId account_id
     * @param bool $isReadOnlyDb
     * @return int
     */
    public function suggest(int $accountId, bool $isReadOnlyDb = false): int
    {
        $row = $this->createInvoiceReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterInvoiceStatusId(Constants\Invoice::$availableInvoiceStatuses)
            ->select(['MAX(invoice_no) + 1 AS next_inv'])
            ->loadRow();
        return (int)($row['next_inv'] ?? 1);
    }
}
