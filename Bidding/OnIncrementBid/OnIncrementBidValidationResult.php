<?php
/**
 * SAM-6909: Refactor on-increment bid validator for v3.6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\OnIncrementBid;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class OnIncrementBidValidationResult
 * @package Sam\Bidding\OnIncrementBid
 */
class OnIncrementBidValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_INCORRECT_AMOUNT = 1;
    public const ERR_QUANTIZED_LOW_BID_NOT_FOUND = 2;
    public const ERR_QUANTIZED_HIGH_BID_NOT_FOUND = 3;
    public const ERR_OFF_INCREMENT_AMOUNT = 4;
    public const ERR_CHECKING_BID_NOT_MEET_ASKING_BID = 5;

    public const OK_CHECK_DISABLED = 11;
    public const OK_AMOUNT_EQUAL_STARTING_BID_WHEN_NO_BIDS = 12;
    public const OK_AMOUNT_EQUAL_QUANTIZED_LOW_BID = 13;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        self::ERR_INCORRECT_AMOUNT => 'Bid amount should be positive number',
        self::ERR_QUANTIZED_LOW_BID_NOT_FOUND => 'Surrounding quantized low bid cannot be found',
        self::ERR_QUANTIZED_HIGH_BID_NOT_FOUND => 'Surrounding quantized high bid cannot be found',
        self::ERR_OFF_INCREMENT_AMOUNT => 'Bid amount is off-increment',
        self::ERR_CHECKING_BID_NOT_MEET_ASKING_BID => 'Checking bid does not meet asking bid',
    ];

    /** @var string[] */
    protected const SUCCESS_MESSAGES = [
        self::OK_CHECK_DISABLED => 'On-increment checking disabled',
        self::OK_AMOUNT_EQUAL_STARTING_BID_WHEN_NO_BIDS => 'Bid amount is equal to starting bid',
        self::OK_AMOUNT_EQUAL_QUANTIZED_LOW_BID => 'Bid amount is equal to quantized bid',
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
        $this->getResultStatusCollector()->construct(static::ERROR_MESSAGES, static::SUCCESS_MESSAGES);
        return $this;
    }

    // --- Mutate state ---

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

    // --- Outgoing results ---

    public function hasSuccess(): bool
    {
        return $this->getResultStatusCollector()->hasSuccess();
    }

    public function successCodes(): array
    {
        return $this->getResultStatusCollector()->getSuccessCodes();
    }

    public function successMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedSuccessMessage();
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    public function logData(): array
    {
        if ($this->hasSuccess()) {
            return ['success' => $this->successMessage()];
        }
        if ($this->hasError()) {
            return ['error' => $this->errorMessage()];
        }
        return [];
    }
}
