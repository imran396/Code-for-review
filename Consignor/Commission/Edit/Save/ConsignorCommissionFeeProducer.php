<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Save;

use ConsignorCommissionFee;
use InvalidArgumentException;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeDto;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\ConsignorCommissionFee\ConsignorCommissionFeeWriteRepositoryAwareTrait;

/**
 * This class is responsible for creating and updating the consignor commission fee
 *
 * Class ConsignorCommissionFeeProducer
 * @package Sam\Consignor\Commission\Edit\Save
 */
class ConsignorCommissionFeeProducer extends CustomizableClass
{
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorCommissionFeeWriteRepositoryAwareTrait;
    use CurrentDateTrait;
    use EntityFactoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Create or update shared consignor commission fee that can be applied at any level
     *
     * @param int|null $consignorCommissionFeeId
     * @param ConsignorCommissionFeeDto $dto
     * @param int $editorUserId
     * @return ConsignorCommissionFee
     */
    public function saveNamed(
        ?int $consignorCommissionFeeId,
        ConsignorCommissionFeeDto $dto,
        int $editorUserId
    ): ConsignorCommissionFee {
        $consignorCommissionFee = $this->loadConsignorCommissionFeeOrCreate(
            $consignorCommissionFeeId,
            Constants\ConsignorCommissionFee::LEVEL_ACCOUNT,
            (int)$dto->relatedEntityId
        );

        $consignorCommissionFee->Name = $dto->name ?? '';
        $consignorCommissionFee->CalculationMethod = $this->normalizeCalculationMethod($dto->calculationMethod);
        $consignorCommissionFee->FeeReference = $dto->feeReference ?? Constants\ConsignorCommissionFee::FEE_REFERENCE_ZERO;
        $this->getConsignorCommissionFeeWriteRepository()->saveWithModifier($consignorCommissionFee, $editorUserId);
        return $consignorCommissionFee;
    }

    /**
     * Create or update entity-level consignor commission fee
     *
     * @param int|null $consignorCommissionFeeId
     * @param int $level
     * @param int $relatedEntityId
     * @param int $editorUserId
     * @param ConsignorCommissionFeeDto $dto
     * @return ConsignorCommissionFee
     */
    public function save(
        ?int $consignorCommissionFeeId,
        int $level,
        int $relatedEntityId,
        int $editorUserId,
        ConsignorCommissionFeeDto $dto
    ): ConsignorCommissionFee {
        $consignorCommissionFee = $this->loadConsignorCommissionFeeOrCreate(
            $consignorCommissionFeeId,
            $level,
            $relatedEntityId
        );
        if (isset($dto->calculationMethod)) {
            $consignorCommissionFee->CalculationMethod = $this->normalizeCalculationMethod($dto->calculationMethod);
        }
        if (isset($dto->feeReference)) {
            $consignorCommissionFee->FeeReference = $dto->feeReference;
        }
        $this->getConsignorCommissionFeeWriteRepository()->saveWithModifier($consignorCommissionFee, $editorUserId);
        return $consignorCommissionFee;
    }

    /**
     * @param $calculationMethod
     * @return int
     */
    protected function normalizeCalculationMethod($calculationMethod): int
    {
        $calculationMethod = ctype_digit((string)$calculationMethod)
            ? (int)$calculationMethod
            : (int)array_search($calculationMethod, Constants\ConsignorCommissionFee::CALCULATION_METHOD_NAMES, true);
        return $calculationMethod ?: Constants\ConsignorCommissionFee::CALCULATION_METHOD_SLIDING;
    }

    /**
     * @param int|null $consignorCommissionFeeId
     * @param int $level
     * @param int $relatedEntityId
     * @return ConsignorCommissionFee
     */
    protected function loadConsignorCommissionFeeOrCreate(
        ?int $consignorCommissionFeeId,
        int $level,
        int $relatedEntityId
    ): ConsignorCommissionFee {
        if ($consignorCommissionFeeId) {
            $consignorCommissionFee = $this->createConsignorCommissionFeeLoader()->load($consignorCommissionFeeId);
            if (!$consignorCommissionFee) {
                throw new InvalidArgumentException("Available Consignor commission fee not found" . composeSuffix(['id' => $consignorCommissionFeeId]));
            }
        } else {
            $consignorCommissionFee = $this->createConsignorCommissionFee($level, $relatedEntityId);
        }
        return $consignorCommissionFee;
    }

    /**
     * @param int $level
     * @param int $relatedEntityId
     * @return ConsignorCommissionFee
     */
    protected function createConsignorCommissionFee(int $level, int $relatedEntityId): ConsignorCommissionFee
    {
        $consignorCommissionFee = $this->createEntityFactory()->consignorCommissionFee();
        $consignorCommissionFee->CalculationMethod = Constants\ConsignorCommissionFee::CALCULATION_METHOD_SLIDING;
        $consignorCommissionFee->Level = $level;
        $consignorCommissionFee->RelatedEntityId = $relatedEntityId;
        return $consignorCommissionFee;
    }
}
