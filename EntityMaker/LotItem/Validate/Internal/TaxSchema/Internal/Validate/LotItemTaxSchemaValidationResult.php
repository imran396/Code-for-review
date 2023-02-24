<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 17, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\TaxSchema\Internal\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

class LotItemTaxSchemaValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_HP_TAX_SCHEMA_ID_INVALID = 1;
    public const ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH = 2;
    public const ERR_BP_TAX_SCHEMA_ID_INVALID = 3;
    public const ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH = 4;

    public const OK_NOTHING_TO_VALIDATE = 11;
    public const OK_VALIDATED = 12;

    protected const ERROR_MESSAGES = [
        self::ERR_HP_TAX_SCHEMA_ID_INVALID => 'HP Tax Schema ID is invalid',
        self::ERR_HP_TAX_SCHEMA_COUNTRY_MISMATCH => 'HP Tax Schema Country mismatch',
        self::ERR_BP_TAX_SCHEMA_ID_INVALID => 'BP Tax Schema ID is invalid',
        self::ERR_BP_TAX_SCHEMA_COUNTRY_MISMATCH => 'BP Tax Schema Country mismatch',
    ];

    protected const SUCCESS_MESSAGES = [
        self::OK_NOTHING_TO_VALIDATE => 'Nothing to validate',
        self::OK_VALIDATED => 'Successfully validated',
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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES, self::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate ---

    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
        return $this;
    }

    public function addSuccess(int $code): static
    {
        $this->getResultStatusCollector()->addSuccess($code);
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

    public function hasErrorByCode(int $code): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($code);
    }

    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
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
