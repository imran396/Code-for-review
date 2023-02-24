<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Save;

use RuntimeException;
use Sam\Consignor\Commission\Delete\ConsignorCommissionFeeDeleterCreateTrait;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeDto;
use Sam\Consignor\Commission\Edit\Dto\ConsignorCommissionFeeRelatedEntityDto as Dto;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Base\Common\Mode;

/**
 * This class is responsible for updating entity-level consignor commission fee and ranges
 *
 * Class ConsignorCommissionFeeRelatedEntityProducer
 * @package Sam\Consignor\Commission\Edit\Save
 */
class ConsignorCommissionFeeRelatedEntityProducer extends CustomizableClass
{
    use ConsignorCommissionFeeDeleterCreateTrait;
    use ConsignorCommissionFeeLoaderCreateTrait;
    use ConsignorCommissionFeeProducerCreateTrait;
    use ConsignorCommissionFeeRangesProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Decides to create/update/delete custom consignor commission fee
     * if it was changed or replaced with a shared consignor commission fee
     *
     * @param int|null $commissionFeeId
     * @param Dto $dto
     * @param int $level
     * @param int $relatedEntityId
     * @param int $editorUserId
     * @param Mode $mode
     * @return int|null Consignor commission fee id
     */
    public function update(?int $commissionFeeId, Dto $dto, int $level, int $relatedEntityId, int $editorUserId, Mode $mode): ?int
    {
        if ($this->shouldDropCommissionFee($dto)) {
            $this->deleteCustomCommissionFeeIfChanged($commissionFeeId, null, $level, $editorUserId);
            return null;
        }

        if ($dto->id) {
            $this->deleteCustomCommissionFeeIfChanged($commissionFeeId, (int)$dto->id, $level, $editorUserId);
            return (int)$dto->id;
        }

        if (!$this->isApplicableConsignorCommissionFeeId($commissionFeeId, $dto, $level)) {
            $commissionFeeId = null;
        }

        $commissionFeeId = $this->createOrUpdateCommissionFee($commissionFeeId, $dto, $level, $relatedEntityId, $editorUserId);
        $this->updateRanges($commissionFeeId, $dto, $editorUserId, $mode);
        return $commissionFeeId;
    }

    /**
     * @param Dto $dto
     * @return bool
     */
    protected function shouldDropCommissionFee(Dto $dto): bool
    {
        // Soap or csv modes clear ranges
        if (
            !isset($dto->id)
            && isset($dto->ranges)
            && !$dto->ranges
        ) {
            return true;
        }

        return isset($dto->id)
            && !$dto->id
            && !$dto->ranges;
    }

    /**
     * @param int|null $commissionFeeId
     * @param Dto $dto
     * @param int $expectedLevel
     * @return bool
     */
    protected function isApplicableConsignorCommissionFeeId(?int $commissionFeeId, Dto $dto, int $expectedLevel): bool
    {
        if (
            $dto->calculationMethod
            || $dto->feeReference
            || $dto->ranges
        ) {
            if ($commissionFeeId) {
                return $this->isConsignorCommissionFeeAtLevel($commissionFeeId, $expectedLevel);
            }
        }
        return true;
    }

    /**
     * @param int $commissionFeeId
     * @param int $expectedLevel
     * @return bool
     */
    protected function isConsignorCommissionFeeAtLevel(int $commissionFeeId, int $expectedLevel): bool
    {
        $consignorCommissionFee = $this->createConsignorCommissionFeeLoader()->load($commissionFeeId);
        if (
            !$consignorCommissionFee
            || $consignorCommissionFee->Level !== $expectedLevel
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param int|null $commissionFeeId
     * @param Dto $dto
     * @param int $level
     * @param int $relatedEntityId
     * @param $editorUserId
     * @return int|null Consignor commission fee id
     */
    protected function createOrUpdateCommissionFee(
        ?int $commissionFeeId,
        Dto $dto,
        int $level,
        int $relatedEntityId,
        $editorUserId
    ): ?int {
        if (
            $dto->calculationMethod
            || $dto->feeReference
            || isset($dto->ranges)
        ) {
            $feeDto = ConsignorCommissionFeeDto::new();
            if ($dto->calculationMethod) {
                $feeDto->calculationMethod = $dto->calculationMethod;
            }
            if ($dto->feeReference) {
                $feeDto->feeReference = $dto->feeReference;
            }
            $producer = $this->createConsignorCommissionFeeProducer();
            $consignorCommissionFee = $producer->save($commissionFeeId, $level, $relatedEntityId, $editorUserId, $feeDto);
            return $consignorCommissionFee->Id;
        }
        return $commissionFeeId;
    }

    /**
     * @param int|null $commissionFeeId
     * @param Dto $dto
     * @param $editorUserId
     * @param Mode $mode
     */
    protected function updateRanges(?int $commissionFeeId, Dto $dto, $editorUserId, Mode $mode): void
    {
        if (isset($dto->ranges)) {
            if (!$commissionFeeId) {
                throw new RuntimeException('Consignor commission fee id cannot be NULL');
            }
            $this->createConsignorCommissionFeeRangesProducer()->save($commissionFeeId, $dto->ranges, $editorUserId, $mode);
        }
    }

    /**
     * Delete custom consignor commission fee in case if it was changed to default or named
     *
     * @param int|null $originalCommissionFeeId
     * @param int|null $newCommissionFeeId
     * @param int $level
     * @param int $editorUserId
     */
    protected function deleteCustomCommissionFeeIfChanged(?int $originalCommissionFeeId, ?int $newCommissionFeeId, int $level, int $editorUserId): void
    {
        if (
            $originalCommissionFeeId
            && $originalCommissionFeeId !== $newCommissionFeeId
            && $this->isConsignorCommissionFeeAtLevel($originalCommissionFeeId, $level)
        ) {
            $this->createConsignorCommissionFeeDeleter()->delete($originalCommissionFeeId, $editorUserId);
        }
    }
}
