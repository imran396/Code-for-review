<?php
/**
 * SAM-8005: Allow decimals in quantity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\Quantity\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class QuantityValidationResult
 * @package Sam\EntityMaker\LotItem
 */
class QuantityValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INVALID = 1;
    public const ERR_TOO_BIG = 2;
    public const ERR_INVALID_FRACTIONAL_PART_LENGTH = 3;
    public const ERR_INVALID_PRECISION = 4;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_INVALID => 'Should be positive decimal',
        self::ERR_TOO_BIG => 'Maximum number of integer digits is %s',
        self::ERR_INVALID_FRACTIONAL_PART_LENGTH => 'Maximum number of decimal digits is %s',
        self::ERR_INVALID_PRECISION => 'Maximum precision is %s digits',
    ];

    /**
     * Class instantiation method
     * @return static
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

    /**
     * @param int $code
     * @param string|null $message
     * @return $this
     */
    public function addError(int $code, ?string $message = null): static
    {
        $this->getResultStatusCollector()->addError($code, $message);
        return $this;
    }

    /**
     * @return array
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
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

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData['error messages'] = $this->getResultStatusCollector()->getConcatenatedErrorMessage(', ');
        }
        return $logData;
    }
}
