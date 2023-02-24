<?php
/**
 * SAM-9365: Refactor BidIncrementCsvUpload
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 06, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Import\Csv\BidIncrement\Internal\Validate\Internal;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Container for a CSV row validation errors
 *
 * Class RowValidationResult
 * @package Sam\Import\Csv\BidIncrement\Internal\Validate\Internal
 */
class RowValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_AMOUNT_INVALID = 1;
    public const ERR_AMOUNT_WITH_THOUSAND_SEPARATOR = 2;
    public const ERR_INCREMENT_INVALID = 3;
    public const ERR_INCREMENT_WITH_THOUSAND_SEPARATOR = 4;

    protected const ERROR_MESSAGE_PAYLOAD_KEY = 'message';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->getResultStatusCollector()->construct(
            [
                self::ERR_AMOUNT_INVALID => 'Invalid amount',
                self::ERR_INCREMENT_INVALID => 'Invalid increment',
                self::ERR_AMOUNT_WITH_THOUSAND_SEPARATOR => 'Amount with thousand separator',
                self::ERR_INCREMENT_WITH_THOUSAND_SEPARATOR => 'Increment with thousand separator',
            ]
        );
        return $this;
    }

    public function addError(int $errorCode): void
    {
        $this->getResultStatusCollector()->addError($errorCode);
    }

    public function addInvalidAmountError(string $errorMessage): void
    {
        $this->getResultStatusCollector()->addError(
            self::ERR_AMOUNT_INVALID,
            null,
            [self::ERROR_MESSAGE_PAYLOAD_KEY => $errorMessage]
        );
    }

    public function addInvalidIncrementError(string $errorMessage): void
    {
        $this->getResultStatusCollector()->addError(
            self::ERR_INCREMENT_INVALID,
            null,
            [self::ERROR_MESSAGE_PAYLOAD_KEY => $errorMessage]
        );
    }

    public function extractValidationErrorMessage(ResultStatus $resultStatus): string
    {
        return $resultStatus->getPayload()[self::ERROR_MESSAGE_PAYLOAD_KEY] ?? '';
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function getErrorMessages(): array
    {
        return $this->getResultStatusCollector()->getErrorMessages();
    }

    public function getErrorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function getErrorStatuses(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
