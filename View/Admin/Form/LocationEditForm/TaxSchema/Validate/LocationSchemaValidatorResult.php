<?php
/**
 * SAM-10823: Stacked Tax. Location reference with Tax Schema (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;

/**
 * Class LocationSchemaValidatorResult
 * @package Sam\View\Admin\Form\LocationEditForm\TaxSchema\Validate
 */
class LocationSchemaValidatorResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INACTIVE = 1;
    public const ERR_INVALID_LOCATION_ACCOUNT = 2;
    public const ERR_IS_ORIGINAL_TAX_SCHEMA = 3;
    public const ERR_IS_DUPLICATE = 4;

    protected const ERROR_MESSAGES = [
        self::ERR_INACTIVE => 'tax schemas should be active',
        self::ERR_INVALID_LOCATION_ACCOUNT => 'Account of Tax schema must correspond account of location',
        self::ERR_IS_ORIGINAL_TAX_SCHEMA => 'tax schemas should be original not snapshots',
        self::ERR_IS_DUPLICATE => 'tax schemas already exists',
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
    public function construct(string $glue = "\n"): static
    {
        $this->getResultStatusCollector()->construct(errorMessages: self::ERROR_MESSAGES, messageGlue: $glue);
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
                'error message' => $this->errorMessage(),
            ];
        }
        return $logData;
    }
}
