<?php
/**
 * SAM-10775: Create in Admin Web the "Tax Definition Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Definition\Edit\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxDefinitionRange\TaxDefinitionRangeWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Definition\Delete\TaxDefinitionRangeDeleterCreateTrait;
use Sam\Tax\StackedTax\Definition\Edit\Dto\TaxDefinitionRangeDto;
use Sam\Tax\StackedTax\Definition\Edit\Normalize\TaxDefinitionRangeNormalizerCreateTrait;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionRangeLoaderCreateTrait;
use TaxDefinitionRange;

/**
 * Class TaxDefinitionRangesProducer
 * @package Sam\Tax\StackedTax\Definition\Edit\Save
 */
class TaxDefinitionRangesProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use TaxDefinitionRangeDeleterCreateTrait;
    use TaxDefinitionRangeLoaderCreateTrait;
    use TaxDefinitionRangeNormalizerCreateTrait;
    use TaxDefinitionRangeWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param TaxDefinitionRangeDto[] $rangeDtos
     * @param int $taxDefinitionId
     * @param int $taxDefinitionAccountId
     * @param int $editorUserId
     * @return array
     */
    public function save(array $rangeDtos, int $taxDefinitionId, int $taxDefinitionAccountId, int $editorUserId): array
    {
        $ranges = $this->createTaxDefinitionRangeLoader()->loadForTaxDefinition($taxDefinitionId);
        $processedRanges = [];
        $normalizer = $this->createTaxDefinitionRangeNormalizer();
        foreach ($rangeDtos as $rangeDto) {
            //Replace existing entities with new data in order not to accumulate many soft deleted records
            $amount = $normalizer->toFloat($rangeDto->amount, $taxDefinitionAccountId);
            $index = $this->searchInRangesByAmount($ranges, $amount);
            if ($index !== null) {
                $range = $ranges[$index];
                unset($ranges[$index]);
            } else {
                $range = $this->createRange($taxDefinitionId);
            }

            $range->Amount = $amount;
            $range->Fixed = $normalizer->toFloat($rangeDto->fixed, $taxDefinitionAccountId);
            $range->Percent = $normalizer->toFloat($rangeDto->percent, $taxDefinitionAccountId);
            $range->Mode = $rangeDto->mode;
            $this->getTaxDefinitionRangeWriteRepository()->saveWithModifier($range, $editorUserId);
            $processedRanges[] = $range;
        }

        //Delete records that do not exist in the input collection
        $this->deleteRanges($ranges, $editorUserId);

        return $processedRanges;
    }

    /**
     * @param TaxDefinitionRange[] $ranges
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

    protected function createRange(int $taxDefinitionId): TaxDefinitionRange
    {
        $range = $this->createEntityFactory()->taxDefinitionRange();
        $range->TaxDefinitionId = $taxDefinitionId;
        return $range;
    }

    protected function deleteRanges(array $ranges, int $editorUserId): void
    {
        $deleter = $this->createTaxDefinitionRangeDeleter();
        foreach ($ranges as $rangeToDelete) {
            $deleter->delete($rangeToDelete->Id, $editorUserId);
        }
    }
}
