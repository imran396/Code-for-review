<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several Invoice entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Invoice;
use InvoiceItem;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;

/**
 * Class InvoiceAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class InvoiceAggregate extends EntityAggregateBase
{
    use InvoiceItemLoaderAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceUserLoaderAwareTrait;

    private ?int $invoiceId = null;
    private ?Invoice $invoice = null;
    /**
     * Currently we always create new record, if not exist
     */
    private ?InvoiceUserBilling $invoiceUserBilling = null;
    /**
     * Currently we always create new record, if not exist
     */
    private ?InvoiceUserShipping $invoiceUserShipping = null;
    /**
     * @var InvoiceItem[]
     */
    private ?array $invoiceItems = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->invoice = null;
        $this->invoiceId = null;
        $this->invoiceItems = null;
        $this->invoiceUserBilling = null;
        $this->invoiceUserShipping = null;
        return $this;
    }

    // --- invoice.id ---

    /**
     * @return int|null
     */
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }

    /**
     * @param int|null $invoiceId
     * @return static
     */
    public function setInvoiceId(?int $invoiceId): static
    {
        $invoiceId = $invoiceId ?: null;
        if ($this->invoiceId !== $invoiceId) {
            $this->clear();
        }
        $this->invoiceId = $invoiceId;
        return $this;
    }

    // --- Invoice ---

    /**
     * @return bool
     */
    public function hasInvoice(): bool
    {
        return ($this->invoice !== null);
    }

    /**
     * Return Invoice object
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function getInvoice(bool $isReadOnlyDb = false): ?Invoice
    {
        if ($this->invoice === null) {
            $this->invoice = $this->getInvoiceLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoice;
    }

    /**
     * @param Invoice|null $invoice
     * @return static
     */
    public function setInvoice(?Invoice $invoice = null): static
    {
        if (!$invoice) {
            $this->clear();
        } elseif ($invoice->Id !== $this->invoiceId) {
            $this->clear();
            $this->invoiceId = $invoice->Id;
        }
        $this->invoice = $invoice;
        return $this;
    }

    // --- InvoiceUserBilling ---

    /**
     * @return bool
     */
    public function hasInvoiceUserBilling(): bool
    {
        return ($this->invoiceUserBilling !== null);
    }

    /**
     * Return InvoiceUserBilling object
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling|null
     */
    public function getInvoiceUserBilling(bool $isReadOnlyDb = false): ?InvoiceUserBilling
    {
        if ($this->invoiceUserBilling === null) {
            $this->invoiceUserBilling = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserBilling($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoiceUserBilling;
    }

    /**
     * Return InvoiceUserBilling object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function getInvoiceUserBillingOrCreate(bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        if ($this->invoiceUserBilling === null) {
            $this->invoiceUserBilling = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserBillingOrCreate($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoiceUserBilling;
    }

    /**
     * Return InvoiceUserBilling object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function getInvoiceUserBillingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        if ($this->invoiceUserBilling === null) {
            $this->invoiceUserBilling = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserBillingOrCreatePersisted($this->invoiceId, $editorUserId, $isReadOnlyDb);
        }
        return $this->invoiceUserBilling;
    }

    /**
     * Set InvoiceUserBilling object
     * @param InvoiceUserBilling|null $invoiceUserBilling
     * @return static
     */
    public function setInvoiceUserBilling(InvoiceUserBilling $invoiceUserBilling = null): static
    {
        if (
            $invoiceUserBilling
            && $invoiceUserBilling->InvoiceId !== $this->invoiceId
        ) {
            $this->clear();
            $this->invoiceId = $invoiceUserBilling->InvoiceId;
        }
        $this->invoiceUserBilling = $invoiceUserBilling;
        return $this;
    }

    // --- InvoiceUserShipping ---

    /**
     * @return bool
     */
    public function hasInvoiceUserShipping(): bool
    {
        return ($this->invoiceUserShipping !== null);
    }

    /**
     * Return InvoiceUserShipping object
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping|null
     */
    public function getInvoiceUserShipping(bool $isReadOnlyDb = false): ?InvoiceUserShipping
    {
        if ($this->invoiceUserShipping === null) {
            $this->invoiceUserShipping = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserShipping($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoiceUserShipping;
    }

    /**
     * Return InvoiceUserShipping object, create new if not exist
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function getInvoiceUserShippingOrCreate(bool $isReadOnlyDb = false): InvoiceUserShipping
    {
        if ($this->invoiceUserShipping === null) {
            $this->invoiceUserShipping = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserShippingOrCreate($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoiceUserShipping;
    }

    /**
     * Return InvoiceUserShipping object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function getInvoiceUserShippingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): InvoiceUserShipping
    {
        if ($this->invoiceUserShipping === null) {
            $this->invoiceUserShipping = $this->getInvoiceUserLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadInvoiceUserShippingOrCreatePersisted($this->invoiceId, $editorUserId, $isReadOnlyDb);
        }
        return $this->invoiceUserShipping;
    }

    /**
     * Set InvoiceUserShipping object
     * @param InvoiceUserShipping|null $invoiceUserShipping
     * @return static
     */
    public function setInvoiceUserShipping(InvoiceUserShipping $invoiceUserShipping = null): static
    {
        if (
            $invoiceUserShipping
            && $invoiceUserShipping->InvoiceId !== $this->invoiceId
        ) {
            $this->clear();
            $this->invoiceId = $invoiceUserShipping->InvoiceId;
        }
        $this->invoiceUserShipping = $invoiceUserShipping;
        return $this;
    }

    // --- InvoiceItem[] ---

    /**
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function getInvoiceItemIds(bool $isReadOnlyDb = false): array
    {
        $invoiceItemIds = [];
        foreach ($this->getInvoiceItems($isReadOnlyDb) as $invoiceItem) {
            $invoiceItemIds[] = $invoiceItem->Id;
        }
        return $invoiceItemIds;
    }

    /**
     * @return bool
     */
    public function hasInvoiceItems(): bool
    {
        return ($this->invoiceItems !== null);
    }

    /**
     * Return array of invoice items. By default it loads all items, including deleted
     * @param bool $isReadOnlyDb
     * @return InvoiceItem[]
     */
    public function getInvoiceItems(bool $isReadOnlyDb = false): array
    {
        if ($this->invoiceItems === null) {
            $this->invoiceItems = $this->getInvoiceItemLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->loadByInvoiceId($this->invoiceId, $isReadOnlyDb);
        }
        return $this->invoiceItems;
    }

    /**
     * Find InvoiceItem in array by id
     * @param int $invoiceItemId
     * @param bool $isReadOnlyDb
     * @return InvoiceItem|null
     */
    public function getInvoiceItemById(int $invoiceItemId, bool $isReadOnlyDb = false): ?InvoiceItem
    {
        $invoiceItems = $this->getInvoiceItems($isReadOnlyDb);
        foreach ($invoiceItems as $invoiceItem) {
            if ($invoiceItem->Id === $invoiceItemId) {
                return $invoiceItem;
            }
        }
        return null;
    }

    /**
     * @param InvoiceItem[]|null $invoiceItems
     * @return static
     */
    public function setInvoiceItems(?array $invoiceItems): static
    {
        $this->invoiceItems = $invoiceItems;
        return $this;
    }
}
