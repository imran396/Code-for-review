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

use ConsignorCommissionFeeRange;
use Sam\Consignor\Commission\Delete\ConsignorCommissionFeeRangeDeleterCreateTrait;
use Sam\Consignor\Commission\Edit\Normalize\ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait;
use Sam\Consignor\Commission\Load\ConsignorCommissionFeeRangeLoaderCreateTrait;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\EntityMaker\Base\Common\Mode;
use Sam\Storage\WriteRepository\Entity\ConsignorCommissionFeeRange\ConsignorCommissionFeeRangeWriteRepositoryAwareTrait;

/**
 * Class ConsignorCommissionFeeRangesProducer
 * @package Sam\Consignor\Commission\Edit\Save
 */
class ConsignorCommissionFeeRangesProducer extends CustomizableClass
{
    use ConsignorCommissionFeeRangeDeleterCreateTrait;
    use ConsignorCommissionFeeRangeLoaderCreateTrait;
    use ConsignorCommissionFeeRangeNormalizerFactoryCreateTrait;
    use ConsignorCommissionFeeRangeWriteRepositoryAwareTrait;
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
     * Update consignor commission fee range collection. Searches for a record by the amount and updates the data or creates  new.
     * If there is no range for an existing record, then it deletes the record.
     *
     * @param int $consignorCommissionFeeId
     * @param array $rangeDtos
     * @param int $editorUserId
     * @param Mode $mode
     * @return array
     */
    public function save(int $consignorCommissionFeeId, array $rangeDtos, int $editorUserId, Mode $mode): array
    {
        $ranges = $this->createConsignorCommissionFeeRangeLoader()->loadForConsignorCommissionFee($consignorCommissionFeeId);

        $processedRanges = [];
        $normalizer = $this->createConsignorCommissionFeeRangeNormalizerFactory()->create($mode);
        foreach ($rangeDtos as $rangeDto) {
            //Replace existing entities with new data in order not to accumulate many soft deleted records
            $amount = $normalizer->toFloat($rangeDto->amount);
            $index = $this->searchInRangesByAmount($ranges, $amount);
            if ($index !== null) {
                $range = $ranges[$index];
                unset($ranges[$index]);
            } else {
                $range = $this->createRange($consignorCommissionFeeId);
            }

            $range->Amount = $amount;
            $range->Fixed = $normalizer->toFloat($rangeDto->fixed);
            $range->Percent = $normalizer->toFloat($rangeDto->percent);
            $range->Mode = $normalizer->normalizeMode($rangeDto->mode);
            $this->getConsignorCommissionFeeRangeWriteRepository()->saveWithModifier($range, $editorUserId);
            $processedRanges[] = $range;
        }

        //Delete records that do not exist in the input collection
        $this->deleteRanges($ranges, $editorUserId);

        return $processedRanges;
    }

    /**
     * @param ConsignorCommissionFeeRange[] $ranges
     * @param float $amount
     * @return int|string|null
     */
    protected function searchInRangesByAmount(array $ranges, float $amount): int|string|null
    {
        foreach ($ranges as $key => $range) {
            if (Floating::eq($range->Amount, $amount)) {
                return $key;
            }
        }
        return null;
    }

    /**
     * @param int $consignorCommissionFeeId
     * @return ConsignorCommissionFeeRange
     */
    protected function createRange(int $consignorCommissionFeeId): ConsignorCommissionFeeRange
    {
        $range = $this->createEntityFactory()->consignorCommissionFeeRange();
        $range->ConsignorCommissionFeeId = $consignorCommissionFeeId;
        return $range;
    }

    /**
     * @param array $ranges
     * @param int $editorUserId
     */
    protected function deleteRanges(array $ranges, int $editorUserId): void
    {
        $deleter = $this->createConsignorCommissionFeeRangeDeleter();
        foreach ($ranges as $rangeToDelete) {
            $deleter->delete($rangeToDelete->Id, $editorUserId);
        }
    }
}
