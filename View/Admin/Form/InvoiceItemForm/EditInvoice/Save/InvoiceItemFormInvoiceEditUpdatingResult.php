<?php
/**
 * SAM-10923: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract invoice General validation and save (#invoice-save-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class InvoiceItemFormInvoiceEditingResult
 * @package Sam\View\Admin\Form\InvoiceItemForm\EditInvoice\Common
 */
class InvoiceItemFormInvoiceEditUpdatingResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_OLC_FAILED = 1;

    protected const ERROR_MESSAGES = [
        self::ERR_OLC_FAILED => 'Optimistic Locking Constraint check failed',
    ];

    /** @var int[] */
    public array $releasedInvoiceItemIds = [];
    public bool $hasLotMarkedAsReleased = false;
    public bool $isUnsold = false;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return $this
     */
    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function enableUnsold(bool $is): static
    {
        $this->isUnsold = $is;
        return $this;
    }

    public function enableHasLotMarkedAsReleased(bool $has): static
    {
        $this->hasLotMarkedAsReleased = $has;
        return $this;
    }

    public function setReleasedInvoiceItemIds(array $releasedInvoiceItemIds): static
    {
        $this->releasedInvoiceItemIds = $releasedInvoiceItemIds;
        return $this;
    }

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasOlcFailedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::ERR_OLC_FAILED);
    }

    /**
     * @return int[]
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }

        return '';
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCodes(),
                'error message' => $this->errorMessage()
            ];
        }
        $logData += [
            'isUnsold' => $this->isUnsold,
            'hasLotMarkedAsReleased' => $this->hasLotMarkedAsReleased,
            'releasedInvoiceItemIds' => $this->releasedInvoiceItemIds
        ];
        return $logData;
    }
}
