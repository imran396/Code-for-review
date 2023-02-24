<?php
/**
 * SAM-5412: Validations at controller layer
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           04/05/2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Controller\Responsive\Invoice\Validate\Internal\Load;

use Invoice;
use Sam\Account\DomainAuctionVisibility\DomainAuctionVisibilityCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Invoice\Common\Load\InvoiceLoader;

/**
 * Class DataProvider
 * @package Sam\Application\Controller\Responsive\Invoice\Internal
 */
class DataProvider extends CustomizableClass
{
    use DomainAuctionVisibilityCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $invoiceId
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function loadInvoice(?int $invoiceId, bool $isReadOnlyDb): ?Invoice
    {
        return InvoiceLoader::new()
            ->filterInvoiceStatusId(Constants\Invoice::$openInvoiceStatuses)
            ->load($invoiceId, $isReadOnlyDb);
    }

    /**
     * @param Invoice $invoice
     * @return bool
     */
    public function isAllowedForInvoice(Invoice $invoice): bool
    {
        return $this->createDomainAuctionVisibilityChecker()->isAllowedForInvoice($invoice);
    }
}
