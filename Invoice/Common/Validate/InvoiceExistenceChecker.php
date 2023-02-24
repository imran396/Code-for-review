<?php
/**
 * Helping methods for invoice validating
 *
 * SAM-4374: Invoice Existence Checker class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 9, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Validate;

use Sam\Cache\Memory\MemoryCacheManagerAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Filter\EntityLoader\InvoiceAllFilterTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\InvoiceItem\InvoiceItemReadRepositoryCreateTrait;

/**
 * Class InvoiceExistenceChecker
 * @package Sam\Invoice\Common\Validate
 */
class InvoiceExistenceChecker extends CustomizableClass
{
    use InvoiceAllFilterTrait;
    use InvoiceItemReadRepositoryCreateTrait;
    use MemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * Check if there are already generated invoices for this auction
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByAuctionId(int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->joinInvoiceItemFilterActive(true)
            ->joinInvoiceItemFilterAuctionId($auctionId)
            ->exist();
        return $isFound;
    }

    /**
     * Checks, if invoice with passed # exists in account
     * @param int $invoiceNo
     * @param int $accountId
     * @param int[] $skipInvoiceIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByInvoiceNo(
        int $invoiceNo,
        int $accountId,
        array $skipInvoiceIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        $repo = $this->prepareRepository($isReadOnlyDb)
            ->filterAccountId($accountId)
            ->filterInvoiceNo($invoiceNo)
            ->skipId($skipInvoiceIds);
        $isFound = $repo->exist();
        return $isFound;
    }

    /**
     * Checks if exist invoices, that contain lot item
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existByLotItemId(int $lotItemId, bool $isReadOnlyDb = false): bool
    {
        $fn = function () use ($lotItemId, $isReadOnlyDb) {
            $repo = $this->createInvoiceItemReadRepository()
                ->enableReadOnlyDb($isReadOnlyDb)
                ->filterActive(true)
                ->filterLotItemId($lotItemId);
            if ($this->hasFilterAccountActive()) {
                $repo->joinAccountFilterActive($this->getFilterAccountActive());
            }
            if ($this->hasFilterInvoiceStatusId()) {
                $repo->joinInvoiceFilterInvoiceStatusId($this->getFilterInvoiceStatusId());
            }
            return $repo->exist();
        };

        $cacheKey = sprintf(Constants\MemoryCache::INVOICE_ITEM_LOT_ITEM_ID_EXIST, $lotItemId);
        return $this->getMemoryCacheManager()->load($cacheKey, $fn);
    }


    public function existById(int $invoiceId, bool $isReadOnlyDb = false): bool
    {
        if (!$invoiceId) {
            return false;
        }

        $isFound = $this->prepareRepository($isReadOnlyDb)
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($invoiceId)
            ->exist();
        return $isFound;
    }
}
