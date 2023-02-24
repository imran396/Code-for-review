<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\MarkPosted\Single\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SingleSettlementCheckPostedMarkingValidationResult
 * @package Sam\Settlement\Check
 */
class SingleSettlementCheckPostedMarkingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_UNKNOWN_ID = 1;
    public const ERR_CHECK_NOT_FOUND = 2;
    public const ERR_ALREADY_POSTED_ON = 3;
    public const ERR_EMPTY_CHECK_NO = 4;
    public const ERR_NOT_PRINTED_YET = 5;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_UNKNOWN_ID => 'Unknown check ID',
        self::ERR_CHECK_NOT_FOUND => 'Check not found',
        self::ERR_ALREADY_POSTED_ON => 'Already posted',
        self::ERR_EMPTY_CHECK_NO => 'Empty check#',
        self::ERR_NOT_PRINTED_YET => 'Not printed yet',
    ];

    // --- Construct ---

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

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

    // --- Query ---

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(?string $glue = null): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage($glue);
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function hasEmptyCheckNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_EMPTY_CHECK_NO]);
    }

    public function hasNotPrintedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_NOT_PRINTED_YET]);
    }

    public function hasAlreadyPostedError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError([self::ERR_ALREADY_POSTED_ON]);
    }

    public function hasSuccess(): bool
    {
        return !$this->hasError();
    }

    public function logData(): array
    {
        $logData = [];
        if ($this->hasError()) {
            $logData['error'] = $this->errorMessage();
        }
        return $logData;
    }
}
