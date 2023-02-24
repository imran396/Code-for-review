<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 27, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * Data structure that contains consignor commission fee validation errors
 *
 * Class ConsignorCommissionFeeRangesValidationResult
 * @package Sam\Consignor\Commission\Edit\Validate
 */
class ConsignorCommissionFeeRangesValidationResult extends CustomizableClass
{
    use ResultStatusCollectorAwareTrait;
    use AdminTranslatorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $messages
     * @return static
     */
    public function construct(array $messages = []): static
    {
        $translator = $this->getAdminTranslator();
        $defaultMessages = [
            ResultCode::ERR_RANGE_AMOUNT_INVALID => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.amount.invalid', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_AMOUNT_REQUIRED => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.amount.required', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_EXIST => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.range.exist', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_FIXED_INVALID => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.fixed.invalid', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_MODE_INVALID => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.mode.invalid', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_MODE_REQUIRED => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.mode.required', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_PERCENT_INVALID => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.percent.invalid', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_FIXED_REQUIRED => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.fixed.required', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_PERCENT_REQUIRED => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.percent.required', [], 'admin_validation');
            },
            ResultCode::ERR_RANGE_FIRST_AMOUNT_MUST_BE_ZERO => static function () use ($translator) {
                return $translator->trans('consignor.commission_fee.range.amount.first_amount_must_be_zero', [], 'admin_validation');
            },
        ];
        $this->getResultStatusCollector()->construct(array_replace($defaultMessages, $messages));
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

    /**
     * @param int $rangeIndex
     * @param string $property
     * @return string
     */
    public function getErrorMessage(int $rangeIndex, string $property): string
    {
        $resultStatuses = $this->getResultStatusCollector()->getErrorStatuses();
        foreach ($resultStatuses as $status) {
            $payload = $status->getPayload();
            if ($payload['property'] === $property && $payload['rangeIndex'] === $rangeIndex) {
                return $status->getMessage();
            }
        }
        return '';
    }
}
