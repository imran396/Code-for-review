<?php
/**
 * SAM-8892: Auction Lot entity maker - extract lot# validation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 14, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotNoValidationResult
 * @package Sam\EntityMaker\AuctionLot\Validate\Internal\LotNo\Internal\Validate
 */
class LotNoValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_LOT_NUM_EXIST = 1;
    public const ERR_LOT_NUM_EXT_INVALID = 2;
    public const ERR_LOT_NUM_EXT_INVALID_LENGTH = 3;
    public const ERR_LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE = 4;
    public const ERR_LOT_NUM_INVALID = 5;
    public const ERR_LOT_NUM_PREFIX_INVALID = 6;
    public const ERR_LOT_NUM_REQUIRED = 7;
    public const ERR_CONCATENATED_LOT_NO_PARSE_FAILED = 8;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_LOT_NUM_EXIST => 'Already exist in this auction. Please pick a unique one',
        self::ERR_LOT_NUM_EXT_INVALID => 'Lot number extension should be alpha numeric',
        self::ERR_LOT_NUM_EXT_INVALID_LENGTH => 'Should be less or equal to %s characters',
        self::ERR_LOT_NUM_HIGHER_MAX_AVAILABLE_VALUE => 'Higher than the max available value',
        self::ERR_LOT_NUM_INVALID => 'Must be numeric integer',
        self::ERR_LOT_NUM_PREFIX_INVALID => 'Should be alpha numeric',
        self::ERR_LOT_NUM_REQUIRED => 'Required',
        self::ERR_CONCATENATED_LOT_NO_PARSE_FAILED => 'Concatenated lot# parsing failed',
    ];

    public int $lotNoMaxLength = 0;

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

    /**
     * @param int $code
     * @return bool
     */
    public function hasErrorByCode(int $code): bool
    {
        return $this->getResultStatusCollector()->hasConcreteError($code);
    }

    /**
     * @return bool
     */
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
                $errorMessages[] = $errorStatus->getMessage();
            }
            $logData['error messages'] = implode(', ', $errorMessages);
        }
        return $logData;
    }
}
