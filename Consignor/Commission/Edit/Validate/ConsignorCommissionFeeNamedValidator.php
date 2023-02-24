<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr. 30, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Validate;

use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeDto;
use Sam\Consignor\Commission\Validate\ConsignorCommissionFeeExistenceCheckerCreateTrait;
use Sam\Core\Save\ResultStatus\ResultStatus;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Translation\AdminTranslatorAwareTrait;

/**
 * This class is responsible for validating a shared consignor commission fee input data
 *
 * Class ConsignorCommissionFeeNamedValidator
 * @package Sam\Consignor\Commission\Edit
 */
class ConsignorCommissionFeeNamedValidator extends CustomizableClass
{
    use AdminTranslatorAwareTrait;
    use ConsignorCommissionFeeExistenceCheckerCreateTrait;
    use ConsignorCommissionFeeValidatorCreateTrait;
    use ResultStatusCollectorAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function construct(): static
    {
        $translator = $this->getAdminTranslator();
        $this->getResultStatusCollector()->construct(
            [
                ResultCode::ERR_CALCULATION_METHOD_INVALID => $translator->trans('consignor.commission_fee.calculation_method.invalid', [], 'admin_validation'),
                ResultCode::ERR_CALCULATION_METHOD_REQUIRED => $translator->trans('consignor.commission_fee.calculation_method.required', [], 'admin_validation'),
                ResultCode::ERR_FEE_REFERENCE_INVALID => $translator->trans('consignor.commission_fee.fee_reference.invalid', [], 'admin_validation'),
                ResultCode::ERR_NAME_NOT_UNIQUE => $translator->trans('consignor.commission_fee.name.not_unique', [], 'admin_validation'),
                ResultCode::ERR_NAME_REQUIRED => $translator->trans('consignor.commission_fee.name.required', [], 'admin_validation'),
                ResultCode::ERR_RELATED_ENTITY_ID_REQUIRED => $translator->trans('consignor.commission_fee.related_entity.required', [], 'admin_validation'),
            ]
        );
        return $this;
    }

    /**
     * Validate shared consignor commission fee
     *
     * @param int|null $commissionFeeId
     * @param ConsignorCommissionFeeDto $commissionFeeDto
     * @return bool
     */
    public function validate(
        ?int $commissionFeeId,
        ConsignorCommissionFeeDto $commissionFeeDto
    ): bool {
        $this->checkRequired($commissionFeeDto, 'calculationMethod', ResultCode::ERR_CALCULATION_METHOD_REQUIRED);
        $validationHelper = $this->createConsignorCommissionFeeValidator();
        if (!$validationHelper->isValidCalculationMethod($commissionFeeDto->calculationMethod)) {
            $this->getResultStatusCollector()->addError(
                ResultCode::ERR_CALCULATION_METHOD_INVALID,
                null,
                ['property' => 'calculationMethod']
            );
        }
        if (!$validationHelper->isValidFeeReference($commissionFeeDto->feeReference)) {
            $this->getResultStatusCollector()->addError(
                ResultCode::ERR_FEE_REFERENCE_INVALID,
                null,
                ['property' => 'feeReference']
            );
        }
        $this->checkRequired($commissionFeeDto, 'relatedEntityId', ResultCode::ERR_RELATED_ENTITY_ID_REQUIRED);
        $this->checkRequired($commissionFeeDto, 'name', ResultCode::ERR_NAME_REQUIRED);
        $this->checkNameUniqueness($commissionFeeDto, $commissionFeeId);
        return !$this->getResultStatusCollector()->hasError();
    }

    /**
     * @return ResultStatus[]
     */
    public function getErrors(): array
    {
        return $this->getResultStatusCollector()->getErrorStatuses();
    }

    /**
     * @param ConsignorCommissionFeeDto $dto
     * @param string $property
     * @param int $errorCode
     */
    protected function checkRequired(ConsignorCommissionFeeDto $dto, string $property, int $errorCode): void
    {
        if (in_array($dto->{$property}, [null, ''], true)) {
            $this->getResultStatusCollector()->addError($errorCode, null, ['property' => $property]);
        }
    }

    /**
     * @param ConsignorCommissionFeeDto $dto
     * @param int|null $commissionFeeId
     */
    protected function checkNameUniqueness(ConsignorCommissionFeeDto $dto, ?int $commissionFeeId): void
    {
        if ($dto->name && $dto->relatedEntityId) {
            $isExist = $this->createConsignorCommissionFeeExistenceChecker()->existByNameAndAccountId(
                $dto->name,
                (int)$dto->relatedEntityId,
                $commissionFeeId
            );
            if ($isExist) {
                $this->getResultStatusCollector()->addError(ResultCode::ERR_NAME_NOT_UNIQUE, null, ['property' => 'name']);
            }
        }
    }
}
