<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRangeDto as Dto;
use Sam\Consignor\Commission\Edit\Normalize\ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait;
use Sam\Consignor\Commission\Edit\Normalize\ConsignorCommissionFeeRangeNormalizerInterface;
use Sam\Consignor\Commission\Edit\Validate\ConsignorCommissionFeeRangesValidationResult as Result;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * This class is responsible for validation consignor commission fee range collection input data
 *
 * Class ConsignorCommissionFeeRangesValidator
 * @package Sam\Consignor\Commission\Edit
 */
class ConsignorCommissionFeeRangesValidator extends CustomizableClass
{
    use ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait;

    protected ?ConsignorCommissionFeeRangeNormalizerInterface $normalizer;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Validate consignor commission fee range collection input data
     *
     * @param Dto[] $rangeDtos
     * @param Mode $mode
     * @return Result
     */
    public function validate(array $rangeDtos, Mode $mode): Result
    {
        $this->normalizer = $this->createConsignorCommissionFeeRangeNormalizerFactory()->create($mode);
        $result = Result::new()->construct();
        foreach ($rangeDtos as $index => $rangeDto) {
            $this->checkAmount($rangeDto, $index, $result);
            $this->checkFixed($rangeDto, $index, $result);
            $this->checkPercent($rangeDto, $index, $result);
            $this->checkMode($rangeDto, $index, $result);
            $this->checkRangeUniqueness($rangeDto, $rangeDtos, $index, $result);
        }
        return $result;
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     */
    protected function checkAmount(Dto $dto, int $rangeIndex, Result $result): void
    {
        if (in_array($dto->amount, [null, ''], true)) {
            $result->addError($rangeIndex, 'amount', ResultCode::ERR_RANGE_AMOUNT_REQUIRED);
            return;
        }

        if (
            !$this->normalizer->isFloat($dto->amount)
            || Floating::lt($this->normalizer->toFloat($dto->amount), 0.)
        ) {
            $result->addError($rangeIndex, 'amount', ResultCode::ERR_RANGE_AMOUNT_INVALID);
            return;
        }

        if ($rangeIndex === 0) {
            $amount = $this->normalizer->toFloat($dto->amount);
            if (!Floating::eq($amount, 0.)) {
                $result->addError($rangeIndex, 'amount', ResultCode::ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO);
                return;
            }
        }
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     */
    protected function checkFixed(Dto $dto, int $rangeIndex, Result $result): void
    {
        if (in_array($dto->fixed, [null, ''], true)) {
            $result->addError($rangeIndex, 'fixed', ResultCode::ERR_RANGE_FIXED_REQUIRED);
            return;
        }

        if (
            !$this->normalizer->isFloat($dto->fixed)
            || Floating::lt($this->normalizer->toFloat($dto->fixed), 0.)
        ) {
            $result->addError($rangeIndex, 'fixed', ResultCode::ERR_RANGE_FIXED_INVALID);
        }
    }

    /**
     * @param Dto $dto
     * @param int $rangeIndex
     * @param Result $result
     */
    protected function checkPercent(Dto $dto, int $rangeIndex, Result $result): void
    {
        if (in_array($dto->percent, [null, ''], true)) {
            $result->addError($rangeIndex, 'percent', ResultCode::ERR_RANGE_PERCENT_REQUIRED);
            return;
        }

        if (
            !$this->normalizer->isFloat($dto->percent)
            || Floating::lt($this->normalizer->toFloat($dto->percent), 0.)
        ) {
            $result->addError($rangeIndex, 'percent', ResultCode::ERR_RANGE_PERCENT_INVALID);
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
            $result->addError($rangeIndex, 'mode', ResultCode::ERR_RANGE_MODE_REQUIRED);
            return;
        }

        if (!in_array($this->normalizer->normalizeMode($dto->mode), Constants\ConsignorCommissionFee::RANGE_MODES, true)) {
            $result->addError($rangeIndex, 'mode', ResultCode::ERR_RANGE_MODE_INVALID);
        }
    }

    /**
     * @param Dto $dto
     * @param Dto[] $rangeDtos
     * @param int $rangeIndex
     * @param Result $result
     */
    protected function checkRangeUniqueness(Dto $dto, array $rangeDtos, int $rangeIndex, Result $result): void
    {
        if (!$this->normalizer->isFloat($dto->amount)) {
            return;
        }

        unset($rangeDtos[$rangeIndex]);
        $amount = $this->normalizer->toFloat($dto->amount);
        if ($this->existRangeWithAmount($rangeDtos, $amount)) {
            $result->addError($rangeIndex, 'amount', ResultCode::ERR_RANGE_EXIST);
        }
    }

    /**
     * @param Dto[] $rangeDtos
     * @param float $amount
     * @return bool
     */
    protected function existRangeWithAmount(array $rangeDtos, float $amount): bool
    {
        foreach ($rangeDtos as $rangeDto) {
            if ($this->normalizer->isFloat($rangeDto->amount)) {
                $rangeAmount = $this->normalizer->toFloat($rangeDto->amount);
                if (Floating::eq($amount, $rangeAmount)) {
                    return true;
                }
            }
        }
        return false;
    }
}
