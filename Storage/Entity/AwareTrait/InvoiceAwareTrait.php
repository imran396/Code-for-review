<?php
/** @noinspection PhpUnused */

/**
 * SAM-4819: Entity aware traits
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/14/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\AwareTrait;

use Invoice;
use InvoiceItem;
use InvoiceUserBilling;
use InvoiceUserShipping;
use Sam\Storage\Entity\Aggregate\InvoiceAggregate;

/**
 * Trait InvoiceAwareTrait
 * @package Sam\Storage\Entity\AwareTrait
 */
trait InvoiceAwareTrait
{
    protected ?InvoiceAggregate $invoiceAggregate = null;

    /**
     * Return Id of Invoice
     * @return int|null
     */
    public function getInvoiceId(): ?int
    {
        return $this->getInvoiceAggregate()->getInvoiceId();
    }

    /**
     * Set Id of Invoice
     * @param int|null $invoiceId
     * @return static
     */
    public function setInvoiceId(?int $invoiceId): static
    {
        $this->getInvoiceAggregate()->setInvoiceId($invoiceId);
        return $this;
    }

    /**
     * Return Invoice object
     * @param bool $isReadOnlyDb
     * @return Invoice|null
     */
    public function getInvoice(bool $isReadOnlyDb = false): ?Invoice
    {
        return $this->getInvoiceAggregate()->getInvoice($isReadOnlyDb);
    }

    /**
     * Set Invoice object
     * @param Invoice|null $invoice
     * @return static
     */
    public function setInvoice(?Invoice $invoice): static
    {
        $this->getInvoiceAggregate()->setInvoice($invoice);
        return $this;
    }

    // Invoice User Billing

    /**
     * Return InvoiceUserBilling object
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling|null
     */
    public function getInvoiceUserBilling(bool $isReadOnlyDb = false): ?InvoiceUserBilling
    {
        return $this->getInvoiceAggregate()->getInvoiceUserBilling($isReadOnlyDb);
    }

    /**
     * Return InvoiceUserBilling object, create new if not exist (do not save)
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function getInvoiceUserBillingOrCreate(bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return $this->getInvoiceAggregate()->getInvoiceUserBillingOrCreate($isReadOnlyDb);
    }

    /**
     * Return InvoiceUserBilling object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserBilling
     */
    public function getInvoiceUserBillingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): InvoiceUserBilling
    {
        return $this->getInvoiceAggregate()->getInvoiceUserBillingOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set InvoiceUserBilling object
     * @param InvoiceUserBilling|null $invoiceUserBilling
     * @return static
     */
    public function setInvoiceUserBilling(InvoiceUserBilling $invoiceUserBilling = null): static
    {
        $this->getInvoiceAggregate()->setInvoiceUserBilling($invoiceUserBilling);
        return $this;
    }

    // InvoiceUser Shipping

    /**
     * Return InvoiceUserShipping object
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping|null
     */
    public function getInvoiceUserShipping(bool $isReadOnlyDb = false): ?InvoiceUserShipping
    {
        return $this->getInvoiceAggregate()->getInvoiceUserShipping($isReadOnlyDb);
    }

    /**
     * Return InvoiceUserShipping object, create new if not exist (do not save)
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function getInvoiceUserShippingOrCreate(bool $isReadOnlyDb = false): InvoiceUserShipping
    {
        return $this->getInvoiceAggregate()->getInvoiceUserShippingOrCreate($isReadOnlyDb);
    }

    /**
     * Return InvoiceUserShipping object, create and save if not exist
     * @param int $editorUserId
     * @param bool $isReadOnlyDb
     * @return InvoiceUserShipping
     */
    public function getInvoiceUserShippingOrCreatePersisted(int $editorUserId, bool $isReadOnlyDb = false): InvoiceUserShipping
    {
        return $this->getInvoiceAggregate()->getInvoiceUserShippingOrCreatePersisted($editorUserId, $isReadOnlyDb);
    }

    /**
     * Set InvoiceUserShipping object
     * @param InvoiceUserShipping|null $invoiceUserShipping
     * @return static
     */
    public function setInvoiceUserShipping(InvoiceUserShipping $invoiceUserShipping = null): static
    {
        $this->getInvoiceAggregate()->setInvoiceUserShipping($invoiceUserShipping);
        return $this;
    }

    // --- InvoiceItem[] ---

    /**
     * @param bool $isReadOnlyDb
     * @return int[]
     */
    public function getInvoiceItemIds(bool $isReadOnlyDb = false): array
    {
        return $this->getInvoiceAggregate()->getInvoiceItemIds($isReadOnlyDb);
    }

    /**
     * Return array of invoice items. By default it loads all items, including deleted
     * @param bool $isReadOnlyDb
     * @return InvoiceItem[]
     */
    public function getInvoiceItems(bool $isReadOnlyDb = false): array
    {
        return $this->getInvoiceAggregate()->getInvoiceItems($isReadOnlyDb);
    }

    /**
     * Find InvoiceItem in array by id
     * @param int $invoiceItemId
     * @param bool $isReadOnlyDb
     * @return InvoiceItem|null
     */
    public function getInvoiceItemById(int $invoiceItemId, bool $isReadOnlyDb = false): ?InvoiceItem
    {
        return $this->getInvoiceAggregate()->getInvoiceItemById($invoiceItemId, $isReadOnlyDb);
    }

    /**
     * @param InvoiceItem[]|null $invoiceItems
     * @return static
     */
    public function setInvoiceItems(?array $invoiceItems): static
    {
        $this->getInvoiceAggregate()->setInvoiceItems($invoiceItems);
        return $this;
    }

    // --- InvoiceAggregate ---

    /**
     * @return InvoiceAggregate
     */
    protected function getInvoiceAggregate(): InvoiceAggregate
    {
        if ($this->invoiceAggregate === null) {
            $this->invoiceAggregate = InvoiceAggregate::new();
        }
        return $this->invoiceAggregate;
    }
}
