<?php
/**
 * SAM-8891: Auction entity-maker - Extract sale# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SaleNoValidationResult
 * @package Sam\EntityMaker\Auction\Validate\Internal\SaleNo\Internal\Validate
 */
class SaleNoValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_SALE_FULL_NO_PARSE_ERROR = 1;
    public const ERR_SALE_NO_EXIST = 2;
    public const ERR_SALE_NUM_EXT_INVALID = 3;
    public const ERR_SALE_NUM_EXT_NOT_ALPHA = 4;
    public const ERR_SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE = 5;
    public const ERR_SALE_NUM_INVALID = 6;


    public const ERROR_MESSAGES = [
        self::ERR_SALE_FULL_NO_PARSE_ERROR => 'Parse error',
        self::ERR_SALE_NO_EXIST => 'Already exists',
        self::ERR_SALE_NUM_EXT_INVALID => 'Max limit exceeds',
        self::ERR_SALE_NUM_EXT_NOT_ALPHA => 'Should be letters',
        self::ERR_SALE_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available mysql value',
        self::ERR_SALE_NUM_INVALID => 'Should be integer',
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
     * @param array $errorMessages
     * @param string|null $messageGlue null means default value of ResultStatusCollector.
     * @return $this
     */
    public function construct(
        array $errorMessages = [],
        ?string $messageGlue = null
    ): static {
        $errorMessages = $errorMessages ?: self::ERROR_MESSAGES;
        $this->getResultStatusCollector()
            ->construct($errorMessages)
            ->setResultMessageGlue($messageGlue);
        return $this;
    }

    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasErrorByCode(int $code): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($code);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $errorMessages = [];
            foreach ($this->getResultStatusCollector()->getErrorStatuses() as $errorStatus) {
                $errorMessages[] = sprintf('%s (%d)', $errorStatus->getMessage(), $errorStatus->getCode());
            }
            $logData['error messages'] = implode(', ', $errorMessages);
        }
        return $logData;
    }
}
