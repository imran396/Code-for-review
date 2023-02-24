<?php
/**
 * SAM-7978 : Decouple invoice merging service and apply unit tests
 * https://bidpath.atlassian.net/browse/SAM-7978
 * @copyright       2021 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Merge\Validate;

use Invoice;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

class LegacyInvoiceMergingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_NOT_AVAILABLE = 1;
    public const ERR_RELATED_DELETED = 2;
    public const ERR_DIFFERENT_ACCOUNTS = 3;
    public const ERR_DIFFERENT_BIDDERS = 4;
    public const ERR_DIFFERENT_CURRENCY = 5;
    public const ERR_NO_INVOICE_TO_MERGE = 6;
    public const ERR_NO_INVOICE_TO_MERGE_WITH = 7;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_NOT_AVAILABLE => 'Invoice not available',
        self::ERR_RELATED_DELETED => 'Selected invoices contain deleted related entities, merging can not be continue',
        self::ERR_DIFFERENT_ACCOUNTS => 'Selected invoices comes from different account, merging can not be continue',
        self::ERR_DIFFERENT_BIDDERS => 'Selected invoices has different bidders, merging can not be continue',
        self::ERR_DIFFERENT_CURRENCY => 'Selected invoices has different currency, merging can not be continue',
        self::ERR_NO_INVOICE_TO_MERGE => 'No invoice has been checked to merge',
        self::ERR_NO_INVOICE_TO_MERGE_WITH => 'There is no second invoice to merge with.',
    ];

    /**
     * @var Invoice[]
     */
    public array $validInvoices = [];
    /**
     * @var array
     */
    public array $missedInvoiceIds = [];

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
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addErrorWithAppendedMessage(int $code, string $append): static
    {
        $this->getResultStatusCollector()->addErrorWithAppendedMessage($code, $append);
        return $this;
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
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }
}
