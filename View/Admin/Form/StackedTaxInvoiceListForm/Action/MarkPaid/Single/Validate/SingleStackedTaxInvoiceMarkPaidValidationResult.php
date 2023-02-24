<?php
/**
 * SAM-11079: Stacked Tax. Tax aggregation. Admin Invoice List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class SingleStackedTaxInvoiceMarkPaidValidationResult
 * @package Sam\View\Admin\Form\StackedTaxInvoiceListForm\Action\MarkPaid\Single\Validate
 */
class SingleStackedTaxInvoiceMarkPaidValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_ABSENT = 1;
    public const ERR_INVOICE_DELETED = 2;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_ABSENT => 'Invoice not found by id',
        self::ERR_INVOICE_DELETED => 'Cannot mark paid deleted invoice',
    ];

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

    // --- Query error ---

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
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

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData += [
                'error code' => $this->errorCodes(),
                'error message' => $this->errorMessage()
            ];
        }
        return $logData;
    }
}
