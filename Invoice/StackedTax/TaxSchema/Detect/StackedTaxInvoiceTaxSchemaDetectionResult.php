<?php
/**
 * SAM-11061: Stacked Tax. Invoice Management pages. Implement Stacked Tax Invoice Generation logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\TaxSchema\Detect;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use TaxSchema;

/**
 * Class StackedTaxInvoiceTaxSchemaDetectionResult
 * @package Sam\Invoice\StackedTax\TaxSchema\Detect
 */
class StackedTaxInvoiceTaxSchemaDetectionResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_NOT_FOUND = 1;

    public const OK_AUCTION_LOT_LEVEL = 11;
    public const OK_LOT_ITEM_LEVEL = 12;
    public const OK_AUCTION_LEVEL = 13;
    public const OK_LOT_ACCOUNT_LEVEL = 14;

    protected const ERROR_MESSAGES = [
        self::ERR_NOT_FOUND => 'Cannot find tax schema',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_AUCTION_LOT_LEVEL => 'Tax schema is found at the Auction Lot level',
        self::OK_LOT_ITEM_LEVEL => 'Tax schema is found at the Lot Item level',
        self::OK_AUCTION_LEVEL => 'Tax schema is found at the Auction level',
        self::OK_LOT_ACCOUNT_LEVEL => 'Tax schema is found at the Lot Account level',
    ];

    public ?TaxSchema $taxSchema = null;

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code, TaxSchema $taxSchema): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
        $this->taxSchema = $taxSchema;
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

    // --- Query success ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    /**
     * @return int[]
     */
    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(string $glue = "\n"): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage($glue);
    }

    // --- Query methods ---

    public function statusMessage(): string
    {
        if ($this->hasError()) {
            return $this->errorMessage();
        }

        if ($this->hasSuccess()) {
            return $this->successMessage();
        }

        return '';
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasSuccess()) {
            $logData += [
                'ts' => $this->taxSchema->Id,
                'ts name' => $this->taxSchema->Name,
            ];
        }
        return $logData;
    }
}
