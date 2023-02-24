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

namespace Sam\Settlement\Check\Action\Edit\Single\Validate\Result;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class SettlementCheckEditingValidationResult
 * @package Sam\Settlement\Check
 */
class SettlementCheckEditingValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    // check no
    public const ERR_CHECK_NO = 1;
    // Payee
    public const ERR_PAYEE_REQUIRED = 2;
    public const ERR_PAYEE_MAX_LENGTH = 3;
    // Amount
    public const ERR_AMOUNT_REQUIRED = 4;
    public const ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL = 5;
    // Amount spelling
    public const ERR_AMOUNT_SPELLING_MAX_LENGTH = 6;
    // Memo
    public const ERR_MEMO_MAX_LENGTH = 7;
    // Note
    public const ERR_NOTE_MAX_LENGTH = 8;
    // Posted on date
    public const ERR_POSTED_ON_DATE = 9;
    // Cleared on date
    public const ERR_CLEARED_ON_DATE = 10;

    /** @var string[] */
    protected const ERROR_MESSAGES = [
        // check no
        self::ERR_CHECK_NO => 'Check# must be positive integer',
        // payee
        self::ERR_PAYEE_REQUIRED => 'Payee required',
        self::ERR_PAYEE_MAX_LENGTH => 'Payee must be less that 255 characters length',
        // amount
        self::ERR_AMOUNT_REQUIRED => 'Amount required',
        self::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL => 'Amount must be positive decimal',
        // amount spelling
        self::ERR_AMOUNT_SPELLING_MAX_LENGTH => 'Amount Spelling must be less that 255 characters length',
        // memo
        self::ERR_MEMO_MAX_LENGTH => 'Memo must be less that 255 characters length',
        // note
        self::ERR_NOTE_MAX_LENGTH => 'Note must be less that 255 characters length',
        // date posted on
        self::ERR_POSTED_ON_DATE => 'Date Posted must be US date with time (m\d\Y, hh:mm AM|PM)',
        // date cleared on
        self::ERR_CLEARED_ON_DATE => 'Date Cleared must be US date with time (m\d\Y, hh:mm AM|PM)',
    ];

    /** @var int[] */
    protected const CHECK_NO_ERRORS = [self::ERR_CHECK_NO];

    /** @var int[] */
    protected const PAYEE_ERRORS = [
        self::ERR_PAYEE_REQUIRED,
        self::ERR_PAYEE_MAX_LENGTH
    ];

    /** @var int[] */
    protected const AMOUNT_ERRORS = [
        self::ERR_AMOUNT_REQUIRED,
        self::ERR_AMOUNT_MUST_BE_POSITIVE_DECIMAL
    ];

    /** @var int[] */
    protected const AMOUNT_SPELLING_ERRORS = [
        self::ERR_AMOUNT_SPELLING_MAX_LENGTH,
    ];

    /** @var int[] */
    protected const MEMO_ERRORS = [
        self::ERR_MEMO_MAX_LENGTH,
    ];

    /** @var int[] */
    protected const NOTE_ERRORS = [
        self::ERR_NOTE_MAX_LENGTH,
    ];

    /** @var int[] */
    protected const DATE_POSTED_ON_ERRORS = [
        self::ERR_POSTED_ON_DATE
    ];

    /** @var int[] */
    protected const DATE_CLEARED_ON_ERRORS = [
        self::ERR_CLEARED_ON_DATE
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

    // --- Query ---

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

    public function hasCheckNoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::CHECK_NO_ERRORS);
    }

    public function hasPayeeError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::PAYEE_ERRORS);
    }

    public function hasAmountError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::AMOUNT_ERRORS);
    }

    public function hasAmountSpellingError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::AMOUNT_SPELLING_ERRORS);
    }

    public function hasMemoError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::MEMO_ERRORS);
    }

    public function hasNoteError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::NOTE_ERRORS);
    }

    public function hasDatePostedOnError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::DATE_POSTED_ON_ERRORS);
    }

    public function hasDateClearedOnError(): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError(self::DATE_CLEARED_ON_ERRORS);
    }

    /**
     * @param int $searchCode
     * @return bool
     */
    public function hasConcreteError(int $searchCode): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($searchCode);
    }
}
