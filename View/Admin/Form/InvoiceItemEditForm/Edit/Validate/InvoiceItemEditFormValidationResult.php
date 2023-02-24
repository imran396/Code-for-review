<?php
/**
 * SAM-11091: Stacked Tax. New Invoice Edit page: Invoice Item Edit page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class InvoiceItemEditFormValidationResult
 * @package Sam\View\Admin\Form\InvoiceItemEditForm\Edit\Validate
 */
class InvoiceItemEditFormValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_QUANTITY_INVALID = 1;
    public const ERR_QUANTITY_TOO_BIG = 2;
    public const ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH = 3;
    public const ERR_QUANTITY_INVALID_PRECISION = 4;
    public const ERR_HP_TAX_SCHEMA_INVALID = 5;
    public const ERR_BP_TAX_SCHEMA_INVALID = 6;
    public const ERR_HAMMER_PRICE_INVALID = 7;
    public const ERR_BUYERS_PREMIUM_INVALID = 8;

    /** @var string[] */
    public const ERROR_MESSAGES = [
        self::ERR_QUANTITY_INVALID => 'Should be positive decimal',
        self::ERR_QUANTITY_TOO_BIG => 'Maximum number of integer digits is %s',
        self::ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH => 'Maximum number of decimal digits %s',
        self::ERR_QUANTITY_INVALID_PRECISION => 'Maximum precision is %s digits',
        self::ERR_HP_TAX_SCHEMA_INVALID => 'HP tax schema invalid',
        self::ERR_BP_TAX_SCHEMA_INVALID => 'BP tax schema invalid',
        self::ERR_HAMMER_PRICE_INVALID => 'Should be positive decimal or zero',
        self::ERR_BUYERS_PREMIUM_INVALID => 'Should be positive decimal or zero'
    ];

    public readonly int $quantityMaxPrecision;
    public readonly int $quantityMaxIntegerDigits;
    public readonly int $quantityScale;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $quantityMaxPrecision
     * @param int $quantityMaxIntegerDigits
     * @param int $quantityScale
     * @return $this
     */
    public function construct(
        int $quantityMaxPrecision,
        int $quantityMaxIntegerDigits,
        int $quantityScale,
    ): static {
        $errorMessages = array_replace(
            self::ERROR_MESSAGES,
            [
                self::ERR_QUANTITY_TOO_BIG => sprintf(
                    self::ERROR_MESSAGES[self::ERR_QUANTITY_TOO_BIG],
                    $quantityMaxIntegerDigits
                ),
                self::ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH => sprintf(
                    self::ERROR_MESSAGES[self::ERR_QUANTITY_INVALID_FRACTIONAL_PART_LENGTH],
                    $quantityScale
                ),
                self::ERR_QUANTITY_INVALID_PRECISION => sprintf(
                    self::ERROR_MESSAGES[self::ERR_QUANTITY_INVALID_PRECISION],
                    $quantityMaxPrecision
                ),
            ]
        );
        $this->getResultStatusCollector()->construct($errorMessages);
        $this->quantityMaxPrecision = $quantityMaxPrecision;
        $this->quantityScale = $quantityScale;
        $this->quantityMaxIntegerDigits = $quantityMaxIntegerDigits;
        return $this;
    }

    /**
     * @param int $code
     * @return $this
     */
    public function addError(int $code): static
    {
        $this->getResultStatusCollector()->addError($code);
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
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }
}
