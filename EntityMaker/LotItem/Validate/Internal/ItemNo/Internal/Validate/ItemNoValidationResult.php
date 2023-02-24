<?php
/**
 * SAM-8833: Lot item entity maker - extract item# validation
 * SAM-6366: Corrections for Auction Lot and Lot Item Entity Makers for v3.5
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 28, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\LotItem\Validate\Internal\ItemNo\Internal\Validate;

use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemNoValidationResult
 * @package Sam\EntityMaker\LotItem
 */
class ItemNoValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_CONCATENATED_ITEM_NO_PARSE_FAILED = 1;
    public const ERR_ITEM_NUM_REQUIRED = 2;
    public const ERR_ITEM_NUM_NOT_POSITIVE_INTEGER = 3;
    public const ERR_ITEM_NUM_EXCEED_MAX_INTEGER = 4;
    public const ERR_ITEM_NUM_EXTENSION_NOT_ALPHA_NUMERIC = 5;
    public const ERR_ITEM_NUM_EXTENSION_INVALID_LENGTH = 6;
    public const ERR_NOT_UNIQUE = 7;

    public const ERROR_MESSAGES = [
        self::ERR_CONCATENATED_ITEM_NO_PARSE_FAILED => 'Concatenated item# parsing failed',
        self::ERR_ITEM_NUM_REQUIRED => 'Item number required',
        self::ERR_ITEM_NUM_NOT_POSITIVE_INTEGER => 'Item number must be positive integer',
        self::ERR_ITEM_NUM_EXCEED_MAX_INTEGER => 'Item number value exceed maximal available integer',
        self::ERR_ITEM_NUM_EXTENSION_NOT_ALPHA_NUMERIC => 'Item number extension must contain of alpha-numeric characters',
        self::ERR_ITEM_NUM_EXTENSION_INVALID_LENGTH => 'Item number extension has invalid length',
        self::ERR_NOT_UNIQUE => 'Item# not unique',
    ];

    /** @var int */
    public int $itemNumExtensionMaxLength = 0;

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
