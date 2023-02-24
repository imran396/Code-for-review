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

namespace Sam\Tax\StackedTax\Definition\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinitionRange\TaxDefinitionRangeReadRepositoryCreateTrait;
use TaxDefinitionRange;

/**
 * Class TaxDefinitionRangeLoader
 * @package Sam\Tax\StackedTax\Definition\Load
 */
class TaxDefinitionRangeLoader extends CustomizableClass
{
    use TaxDefinitionRangeReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function load(?int $taxDefinitionRangeId, bool $isReadOnlyDb = false): ?TaxDefinitionRange
    {
        if (!$taxDefinitionRangeId) {
            return null;
        }

        return $this->createTaxDefinitionRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($taxDefinitionRangeId)
            ->orderByAmount()
            ->loadEntity();
    }

    /**
     * @return TaxDefinitionRange[]
     */
    public function loadForTaxDefinition(?int $taxDefinitionId, bool $isReadOnlyDb = false): array
    {
        if (!$taxDefinitionId) {
            return [];
        }
        return $this->createTaxDefinitionRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTaxDefinitionId($taxDefinitionId)
            ->orderByAmount()
            ->loadEntities();
    }

    /**
     * @return TaxDefinitionRange[]
     */
    public function loadTaxDefinitionRangesByAmount(?int $taxDefinitionId, float $amount, bool $isReadOnlyDb = false): array
    {
        if (!$taxDefinitionId) {
            return [];
        }

        return $this->createTaxDefinitionRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAmountLessOrEqual($amount)
            ->filterTaxDefinitionId($taxDefinitionId)
            ->orderByAmount(false)
            ->loadEntities();
    }

    public function loadTaxDefinitionRangeByAmount(?int $taxDefinitionId, float $amount, bool $isReadOnlyDb = false): ?TaxDefinitionRange
    {
        if (!$taxDefinitionId) {
            return null;
        }

        return $this->createTaxDefinitionRangeReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterAmountLessOrEqual($amount)
            ->filterTaxDefinitionId($taxDefinitionId)
            ->orderByAmount(false)
            ->loadEntity();
    }
}
