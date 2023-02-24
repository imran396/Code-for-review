<?php
/**
 * SAM-10900: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Individual Invoiced Item Sales Tax validation and updating
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemForm\EditItemTax\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

class InvoiceItemFormItemTaxEditValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVOICE_ITEM_NOT_FOUND = 1;
    public const ERR_TAX_PERCENT_INVALID = 2;
    public const ERR_TAX_APPLICATION_INVALID = 3;

    protected const ERROR_MESSAGES = [
        self::ERR_INVOICE_ITEM_NOT_FOUND => 'Could not find invoice item',
        self::ERR_TAX_PERCENT_INVALID => 'Tax percent value must be positive decimal or zero',
        self::ERR_TAX_APPLICATION_INVALID => 'Tax application is wrong',
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

    public function errorMessage(string $glue = ","): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasTaxPercentError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_TAX_PERCENT_INVALID]);
    }

    public function hasTaxApplicationError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_TAX_APPLICATION_INVALID]);
    }

    // --- Query success ---

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        return $this->errorMessage();
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
        return $logData;
    }
}
