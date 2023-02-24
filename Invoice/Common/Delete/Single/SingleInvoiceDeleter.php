<?php
/**
 * SAM-5834 : Disable MarkUnsoldOnDelete dialog box on Invoice List and Invoice Details
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 21, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Delete\Single;

use InvoiceItem;
use Sam\Core\Service\CustomizableClass;
use Invoice;
use Sam\Account\Validate\AccountExistenceCheckerAwareTrait;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Invoice\Common\Load\InvoiceItem\InvoiceItemLoaderAwareTrait;
use Sam\Invoice\Common\Load\InvoiceLoaderAwareTrait;
use Sam\Settlement\Delete\LotFromSettlementRemoverAwareTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\InvoiceItem\InvoiceItemWriteRepositoryAwareTrait;
use Sam\User\Privilege\Validate\AdminPrivilegeCheckerAwareTrait;

/**
 * Class SingleInvoiceDeleter
 * @package Sam\Invoice\Common\Delete
 */
class SingleInvoiceDeleter extends CustomizableClass
{
    use AccountExistenceCheckerAwareTrait;
    use AdminPrivilegeCheckerAwareTrait;
    use InvoiceItemLoaderAwareTrait;
    use InvoiceItemWriteRepositoryAwareTrait;
    use InvoiceLoaderAwareTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use LotFromInvoiceRemoverCreateTrait;
    use LotFromSettlementRemoverAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SystemAccountAwareTrait;

    /**
     * Check invoice entity existence
     */
    public const ERR_ALREADY_DELETED = 1;
    /**
     * Access denied. Admin has not enough privileges
     */
    public const ERR_INVOICE_ACCESS_DENIED = 2;
    /**
     * Check of invoice account
     */
    public const ERR_INVOICE_ACCOUNT_NOT_FOUND = 3;
    /**
     * Check access rights on portal
     */
    public const ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH = 4;
    /**
     * Check invoice availability (not deleted)
     */
    public const ERR_UNAVAILABLE_INVOICE = 5;

    /**
     * @var int|null
     */
    protected ?int $deletedNumber = null;

    /**
     * @var bool
     */
    protected bool $shouldUnsoldLot;

    /**
     * @var Invoice|null
     */
    protected ?Invoice $invoice;
    /**
     * @var int
     */
    private int $editorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $invoiceId
     * @param bool $shouldUnsoldLot
     * @param int $systemAccountId
     * @param int $editorUserId
     * @return static
     */
    public function construct(int $invoiceId, bool $shouldUnsoldLot, int $systemAccountId, int $editorUserId): static
    {
        $this->invoice = $this->getInvoiceLoader()->load($invoiceId, true);
        $this->setSystemAccountId($systemAccountId);
        $this->editorUserId = $editorUserId;
        $this->shouldUnsoldLot = $shouldUnsoldLot;
        $errorMessages = [
            self::ERR_ALREADY_DELETED => 'Cannot delete already deleted invoice' . composeSuffix(['i' => $invoiceId]),
            self::ERR_INVOICE_ACCESS_DENIED => ' Access denied: admin has not enough privileges',
            self::ERR_INVOICE_ACCOUNT_NOT_FOUND => 'Access denied: invoice account not available',
            self::ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH => 'Access denied: invoice and portal accounts not match',
            self::ERR_UNAVAILABLE_INVOICE => 'Available invoice not found (deleted)',
        ];
        $this->getResultStatusCollector()->construct($errorMessages);
        return $this;
    }

    /**
     * It is main method for deleting invoice
     * @param int $editorUserId
     */
    public function delete(int $editorUserId): void
    {
        if ($this->shouldUnsoldLot) {
            $this->createLotFromInvoiceRemover()->removeByInvoiceId($this->invoice->Id, $editorUserId);
        }
        $this->deletedNumber = $this->invoice->InvoiceNo;
        $this->deleteInvoiceItems($this->invoice->Id);
        $this->markInvoiceDeleted($this->invoice);
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->invoice) {
            $this->getResultStatusCollector()->addError(self::ERR_ALREADY_DELETED);
            return false;
        }
        if (!$this->hasPrivilegeForManageInvoices()) {
            $this->getResultStatusCollector()->addError(self::ERR_INVOICE_ACCESS_DENIED);
            return false;
        }
        $isFound = $this->getAccountExistenceChecker()->existById($this->invoice->AccountId);
        if (!$isFound) {
            $this->getResultStatusCollector()->addError(self::ERR_INVOICE_ACCOUNT_NOT_FOUND);
            return false;
        }
        if (
            $this->invoice->AccountId !== $this->getSystemAccountId()
            && !$this->isMainSystemAccount()
        ) {
            $this->getResultStatusCollector()->addError(self::ERR_INVOICE_AND_PORTAL_ACCOUNTS_NOT_MATCH);
            return false;
        }
        if (!$this->invoice->isAmongAvailableInvoiceStatuses()) {
            $this->getResultStatusCollector()->addError(self::ERR_UNAVAILABLE_INVOICE);
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function hasPrivilegeForManageInvoices(): bool
    {
        return $this->getAdminPrivilegeChecker()
            ->initByUserId($this->editorUserId)
            ->hasPrivilegeForManageInvoices();
    }

    /**
     * It serves deleted invoice number
     * @return int
     */
    public function getDeletedNumber(): int
    {
        return $this->deletedNumber;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * Delete invoice items
     * @param int $invoiceId
     */
    protected function deleteInvoiceItems(int $invoiceId): void
    {
        $invoiceItems = $this->getInvoiceItemLoader()->loadByInvoiceId($invoiceId);
        foreach ($invoiceItems as $invoiceItem) {
            $this->deleteInvoiceItem($invoiceItem);
        }
    }

    /**
     * @param InvoiceItem $invoiceItem
     */
    public function deleteInvoiceItem(InvoiceItem $invoiceItem): void
    {
        $invoiceItem->toDeleted();
        $this->getInvoiceItemWriteRepository()->saveWithModifier($invoiceItem, $this->editorUserId);
    }

    /**
     * Change invoice status to deleted
     * @param Invoice $invoice
     */
    protected function markInvoiceDeleted(Invoice $invoice): void
    {
        $invoice->toDeleted();
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $this->editorUserId);
    }
}
