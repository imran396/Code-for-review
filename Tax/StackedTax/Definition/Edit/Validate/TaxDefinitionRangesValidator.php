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

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Tax\StackedTax\Definition\Edit\Dto\TaxDefinitionRangeDto as Dto;
use Sam\Tax\StackedTax\Definition\Edit\Normalize\TaxDefinitionRangeNormalizerCreateTrait;
use Sam\Tax\StackedTax\Definition\Edit\Validate\TaxDefinitionRangesValidationResult as Result;

/**
 * Class TaxDefinitionRangesValidator
 * @package Sam\Tax\StackedTax\Definition\Edit\Validate
 */
class TaxDefinitionRangesValidator extends CustomizableClass
{
    use TaxDefinitionRangeNormalizerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Dto[] $rangeDtos
     * @param int $taxDefinitionAccountId
     * @return Result
     */
    public function validate(array $rangeDtos, int $taxDefinitionAccountId): Result
    {
        $result = Result::new()->construct();
        foreach ($rangeDtos as $index => $rangeDto) {
            $this->checkAmount($rangeDto, $index, $result, $taxDefinitionAccountId);
            $this->checkFixed($rangeDto, $index, $result, $taxDefinitionAccountId);
            $this->checkPercent($rangeDto, $index, $result, $taxDefinitionAccountId);
            $this->checkMode($rangeDto, $index, $result);
            $this->checkRangeUniqueness($rangeDto, $rangeDtos, $index, $result, $taxDefinitionAccountId);
        }
        return $result;
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     * @param int $taxDefinitionAccountId
     */
    protected function checkAmount(Dto $dto, int $rangeIndex, Result $result, int $taxDefinitionAccountId): void
    {
        if (in_array($dto->amount, [null, ''], true)) {
            $result->addError($rangeIndex, 'amount', Result::ERR_RANGE_AMOUNT_REQUIRED);
            return;
        }

        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        if (
            !$normalizer->isFloat($dto->amount, $taxDefinitionAccountId)
            || $normalizer->toFloat($dto->amount, $taxDefinitionAccountId) < 0
        ) {
            $result->addError($rangeIndex, 'amount', Result::ERR_RANGE_AMOUNT_INVALID);
            return;
        }

        if ($rangeIndex === 0) {
            $amount = $normalizer->toFloat($dto->amount, $taxDefinitionAccountId);
            if (!Floating::eq($amount, 0)) {
                $result->addError($rangeIndex, 'amount', Result::ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO);
                return;
            }
        }
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     * @param int $taxDefinitionAccountId
     */
    protected function checkFixed(Dto $dto, int $rangeIndex, Result $result, int $taxDefinitionAccountId): void
    {
        if (in_array($dto->fixed, [null, ''], true)) {
            $result->addError($rangeIndex, 'fixed', Result::ERR_RANGE_FIXED_REQUIRED);
            return;
        }

        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        if (
            !$normalizer->isFloat($dto->fixed, $taxDefinitionAccountId)
            || $normalizer->toFloat($dto->fixed, $taxDefinitionAccountId) < 0
        ) {
            $result->addError($rangeIndex, 'fixed', Result::ERR_RANGE_FIXED_INVALID);
        }
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     * @param int $taxDefinitionAccountId
     */
    protected function checkPercent(Dto $dto, int $rangeIndex, Result $result, int $taxDefinitionAccountId): void
    {
        if (in_array($dto->percent, [null, ''], true)) {
            $result->addError($rangeIndex, 'percent', Result::ERR_RANGE_PERCENT_REQUIRED);
            return;
        }

        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        if (
            !$normalizer->isFloat($dto->percent, $taxDefinitionAccountId)
            || $normalizer->toFloat($dto->percent, $taxDefinitionAccountId) < 0
        ) {
            $result->addError($rangeIndex, 'percent', Result::ERR_RANGE_PERCENT_INVALID);
        }
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     */
    protected function checkMode(Dto $dto, int $rangeIndex, Result $result): void
    {
        if (in_array($dto->mode, [null, ''], true)) {
            $result->addError($rangeIndex, 'mode', Result::ERR_RANGE_MODE_REQUIRED);
            return;
        }
        if (!in_array($dto->mode, Constants\StackedTax::RANGE_MODES, true)) {
            $result->addError($rangeIndex, 'mode', Result::ERR_RANGE_MODE_INVALID);
        }
    }

    /**
     * @param Dto $dto
     * @param Dto[] $rangeDtos
     * @param int $rangeIndex
     * @param Result $result
     * @param int $taxDefinitionAccountId
     */
    protected function checkRangeUniqueness(Dto $dto, array $rangeDtos, int $rangeIndex, Result $result, int $taxDefinitionAccountId): void
    {
        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        if (!$normalizer->isFloat($dto->amount, $taxDefinitionAccountId)) {
            return;
        }
        unset($rangeDtos[$rangeIndex]);
        $amount = $normalizer->toFloat($dto->amount, $taxDefinitionAccountId);
        if ($this->existRangeWithAmount($rangeDtos, $amount, $taxDefinitionAccountId)) {
            $result->addError($rangeIndex, 'amount', Result::ERR_RANGE_EXIST);
        }
    }

    /**
     * @param Dto[] $rangeDtos
     * @param float $amount
     * @param int $taxDefinitionAccountId
     * @return bool
     */
    protected function existRangeWithAmount(array $rangeDtos, float $amount, int $taxDefinitionAccountId): bool
    {
        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        foreach ($rangeDtos as $rangeDto) {
            if ($normalizer->isFloat($rangeDto->amount, $taxDefinitionAccountId)) {
                $rangeAmount = $normalizer->toFloat($rangeDto->amount, $taxDefinitionAccountId);
                if (Floating::eq($amount, $rangeAmount)) {
                    return true;
                }
            }
        }
        return false;
    }
}
