<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Validate;

use Sam\Consignor\Commission\Edit\Validate\RangeValidationResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionRangesValidationResult
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate
 */
class TaxDefinitionRangesValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;

    public const ERR_RANGE_AMOUNT_INVALID = 1;
    public const ERR_RANGE_AMOUNT_REQUIRED = 2;
    public const ERR_RANGE_EXIST = 3;
    public const ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO = 4;
    public const ERR_RANGE_FIXED_INVALID = 5;
    public const ERR_RANGE_FIXED_REQUIRED = 6;
    public const ERR_RANGE_MODE_INVALID = 7;
    public const ERR_RANGE_MODE_REQUIRED = 8;
    public const ERR_RANGE_PERCENT_INVALID = 9;
    public const ERR_RANGE_PERCENT_REQUIRED = 10;

    public const ERROR_MESSAGES = [
        self::ERR_RANGE_AMOUNT_INVALID => 'Amount should be positive or zero number',
        self::ERR_RANGE_AMOUNT_REQUIRED => 'Amount required',
        self::ERR_RANGE_EXIST => 'The range with this amount already exist',
        self::ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO => 'First range start amount should be 0',
        self::ERR_RANGE_FIXED_INVALID => 'Fixed value should be positive or zero number',
        self::ERR_RANGE_FIXED_REQUIRED => 'Fixed value required',
        self::ERR_RANGE_MODE_INVALID => 'Invalid mode value',
        self::ERR_RANGE_MODE_REQUIRED => 'Mode required',
        self::ERR_RANGE_PERCENT_INVALID => 'Percent value should be positive or zero number',
        self::ERR_RANGE_PERCENT_REQUIRED => 'Percent value required',
    ];

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
        $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        return $this;
    }

    /**
     * @param int $rangeIndex
     * @param string $property
     * @param int $errorCode
     */
    public function addError(int $rangeIndex, string $property, int $errorCode): void
    {
        $this->getResultStatusCollector()->addError(
            $errorCode,
            null,
            [
                'property' => $property,
                'rangeIndex' => $rangeIndex
            ]
        );
    }

    public function hasError(): bool
    {
        return $this->getResultStatusCollector()->hasError();
    }

    /**
     * @return RangeValidationResultStatus[]
     */
    public function getErrors(): array
    {
        $errors = array_map(
            static function (ResultStatus $resultStatus) {
                $payload = $resultStatus->getPayload();
                return RangeValidationResultStatus::new()->construct($payload['rangeIndex'], $payload['property'], $resultStatus);
            },
            $this->getResultStatusCollector()->getErrorStatuses()
        );
        return $errors;
    }

    public function getError(int $rangeIndex, string $property): ?ResultStatus
    {
        $resultStatuses = $this->getResultStatusCollector()->getErrorStatuses();
        foreach ($resultStatuses as $status) {
            $payload = $status->getPayload();
            if ($payload['property'] === $property && $payload['rangeIndex'] === $rangeIndex) {
                return $status;
            }
        }
        return null;
    }
}
