<?php
/**
 * SAM-10616: Supply uniqueness of invoice fields: invoice#
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Lock\Internal\Detect;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class UniqueInvoiceNoLockRequirementsCheckResult
 * @package Sam\Invoice\Common\Lock\Internal\Detect
 */
class UniqueInvoiceNoLockRequirementsCheckResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const INFO_DO_NOT_LOCK_BECAUSE_INVOICE_NO_INPUT_EQUAL_TO_EXISTING = 1;

    public const OK_LOCK_BECAUSE_NEW_INVOICE_CREATED = 11;
    public const OK_LOCK_BECAUSE_INVOICE_NO_DIFFERS = 12;
    public const OK_LOCK_BECAUSE_INVOICE_NO_MUST_BE_GENERATED = 13;

    protected const INFO_MESSAGES = [
        self::INFO_DO_NOT_LOCK_BECAUSE_INVOICE_NO_INPUT_EQUAL_TO_EXISTING => 'Do not lock for unique invoice# constraint, because invoice# input is equal to the existing value',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_LOCK_BECAUSE_NEW_INVOICE_CREATED => 'Lock for unique invoice# constraint, because new invoice will be created',
        self::OK_LOCK_BECAUSE_INVOICE_NO_DIFFERS => 'Lock for unique invoice# constraint, because invoice# will be changed',
        self::OK_LOCK_BECAUSE_INVOICE_NO_MUST_BE_GENERATED => 'Lock for unique invoice# constraint, because new invoice# will be generated'
    ];

    protected readonly array $logData;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(?int $invoiceId, int|string $invoiceNoInput): static
    {
        $this->logData = [
            'invoice ID' => $invoiceId,
            'invoice#' => $invoiceNoInput
        ];

        $this->getResultStatusCollector()->construct([], self::SUCCESS_MESSAGES, [], self::INFO_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        return $this;
    }

    public function addInfo(int $code): static
    {
        $this->getResultStatusCollector()->addInfo($code);
        return $this;
    }

    // --- Query ---

    public function mustLock(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function message(): string
    {
        if ($this->getResultStatusCollector()->hasSuccess()) {
            return $this->getResultStatusCollector()->getConcatenatedSuccessMessage() . composeSuffix($this->logData);
        }

        if ($this->getResultStatusCollector()->hasInfo()) {
            return $this->getResultStatusCollector()->getConcatenatedInfoMessage() . composeSuffix($this->logData);
        }

        return '';
    }
}
