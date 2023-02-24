<?php
/**
 * SAM-5834 : Disable MarkUnsoldOnDelete dialog box on Invoice List and Invoice Details
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package        com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Feb 24, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Delete\Multiple;

use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Delete\Single\SingleInvoiceDeleterCreateTrait;
use Sam\Storage\Entity\AwareTrait\SystemAccountAwareTrait;

/**
 * Class MultipleInvoiceDeleter
 * @package Sam\Invoice\Common\Delete
 */
class MultipleInvoiceDeleter extends CustomizableClass
{
    use SingleInvoiceDeleterCreateTrait;
    use SystemAccountAwareTrait;

    /**
     * @var array
     */
    protected array $invoiceIds;
    /**
     * @var array
     */
    protected array $deletedInvoiceNumbers = [];
    /**
     * @var array
     */
    protected array $errorMessages = [];
    /**
     * @var bool
     */
    protected bool $shouldUnsoldLot;
    /**
     * @var int
     */
    protected int $editorUserId;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $invoiceIds
     * @param bool $shouldUnsoldLot
     * @param int $systemAccountId
     * @param int $editorUserId
     * @return $this
     */
    public function construct(array $invoiceIds, bool $shouldUnsoldLot, int $systemAccountId, int $editorUserId): static
    {
        $this->invoiceIds = $invoiceIds;
        $this->shouldUnsoldLot = $shouldUnsoldLot;
        $this->editorUserId = $editorUserId;
        $this->setSystemAccountId($systemAccountId);
        return $this;
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->errorMessages = [];
        foreach ($this->invoiceIds as $invoiceId) {
            $singleInvoiceDeleter = $this->createSingleInvoiceDeleter()->construct(
                $invoiceId,
                $this->shouldUnsoldLot,
                $this->getSystemAccountId(),
                $this->editorUserId
            );
            $singleInvoiceDeleter->validate();
            if ($singleInvoiceDeleter->hasError()) {
                $this->errorMessages[] = $singleInvoiceDeleter->errorMessage();
            }
        }
        return count($this->errorMessages) === 0;
    }

    public function delete(): void
    {
        $this->deletedInvoiceNumbers = [];
        foreach ($this->invoiceIds as $invoiceId) {
            $singleInvoiceDeleter = $this->createSingleInvoiceDeleter()->construct(
                $invoiceId,
                $this->shouldUnsoldLot,
                $this->getSystemAccountId(),
                $this->editorUserId
            );
            $singleInvoiceDeleter->delete($this->editorUserId);
            $this->deletedInvoiceNumbers[] = $singleInvoiceDeleter->getDeletedNumber();
        }
    }

    /**
     * @return int[]
     */
    public function getDeletedNumbers(): array
    {
        return $this->deletedInvoiceNumbers;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return implode(',', $this->errorMessages);
    }
}
