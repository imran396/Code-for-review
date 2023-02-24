<?php
/**
 * Helping methods for invoice loading
 *
 * SAM-4337: Invoice Loader class
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

namespace Sam\Invoice\Common\Load;

use Invoice;
use Sam\Core\Constants;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Filter\EntityLoader\InvoiceAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;

/**
 * Class InvoiceLoader
 * @package Sam\Invoice\Common\Load
 */
class InvoiceLoader extends EntityLoaderBase
{
    use EntityMemoryCacheManagerAwareTrait;
    use InvoiceAllFilterTrait;

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
     * Get Invoice by ID
     *
     * @param int|null $invoiceId invoice.id
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function load(?int $invoiceId, bool $isReadOnlyDb = false): ?Invoice
    {
        if (!$invoiceId) {
            return null;
        }

        $fn = function () use ($invoiceId, $isReadOnlyDb) {
            $invoice = $this->prepareRepository($isReadOnlyDb)
                ->filterId($invoiceId)
                ->loadEntity();
            return $invoice;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::INVOICE_ID, $invoiceId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $invoice = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $invoice;
    }


    /**
     * @param int[] $invoicesIds
     * @param bool $isReadOnlyDb
     * @return Invoice[]
     */
    public function loadByIds(array $invoicesIds, bool $isReadOnlyDb = false): iterable
    {
        $ids = [];
        foreach ($invoicesIds as $invoicesId) {
            $id = Cast::toInt($invoicesId, Constants\Type::F_INT_POSITIVE);
            if ($id) {
                $ids[] = $id;
            }
        }

        if (!$ids) {
            return [];
        }

        $invoice = $this->prepareRepository($isReadOnlyDb)
            ->filterId($ids)
            ->loadEntities();
        return $invoice;
    }

    /**
     * Loads invoices, that contain lot item
     * @param int $lotItemId
     * @param bool $isReadOnlyDb
     * @return array|Invoice[]
     */
    public function loadForLotItem(int $lotItemId, bool $isReadOnlyDb = false): array
    {
        // we don't group by invoice.id, because lots are not duplicated between invoice items
        $invoices = $this->prepareRepository($isReadOnlyDb)
            ->joinInvoiceItemFilterActive(true)
            ->joinInvoiceItemFilterLotItemId($lotItemId)
            ->loadEntities();
        return $invoices;
    }

    public function loadForInvoiceAdditional(int $invoiceAdditionalId, bool $isReadOnlyDb = false): ?Invoice
    {
        $invoice = $this->prepareRepository($isReadOnlyDb)
            ->joinInvoiceItemFilterActive(true)
            ->joinInvoiceAdditionalFilterId($invoiceAdditionalId)
            ->loadEntity();
        return $invoice;
    }
}
