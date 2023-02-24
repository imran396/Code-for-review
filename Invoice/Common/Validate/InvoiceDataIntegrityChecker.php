<?php
/**
 * Search invoice duplicates
 *
 * SAM-5073: Data integrity checker - an lot_item shall only be in one non paid/canceled invoice at a time
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           12 Sep, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

use Sam\Core\Constants;
use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepository;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class InvoiceDataIntegrityChecker
 * @package Sam\Invoice\Common\Validate
 */
class InvoiceDataIntegrityChecker extends CustomizableClass
{
    use FilterAccountAwareTrait;
    use InvoiceItemReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return InvoiceItemReadRepository
     */
    public function prepareInvoiceDuplicateSearch(): InvoiceItemReadRepository
    {
        $repo = $this->createInvoiceItemReadRepository()
            ->select(
                [
                    'ii.lot_name',
                    'ii.lot_item_id',
                    'COUNT(1) as count_records',
                    'GROUP_CONCAT(ii.invoice_id) as invoices_ids',
                    'i.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinInvoiceFilterInvoiceStatusId(Constants\Invoice::$openInvoiceStatuses)
            ->joinAccount()
            ->filterActive(true)
            ->groupByLotItemId()
            ->having('COUNT(1) > 1')
            ->joinInvoiceOrderByAccountId()
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->joinInvoiceFilterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
