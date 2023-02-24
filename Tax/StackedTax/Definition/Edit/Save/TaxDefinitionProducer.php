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

namespace Sam\Tax\StackedTax\Definition\Edit\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxDefinition\TaxDefinitionWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Definition\Edit\Dto\TaxDefinitionDto;
use Sam\Tax\StackedTax\Definition\Exception\CouldNotFindTaxDefinition;
use Sam\Tax\StackedTax\Definition\Load\TaxDefinitionLoaderCreateTrait;
use TaxDefinition;

/**
 * Class TaxDefinitionProducer
 * @package Sam\Tax\StackedTax\Definition\Edit\Save
 */
class TaxDefinitionProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use TaxDefinitionLoaderCreateTrait;
    use TaxDefinitionWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(TaxDefinitionDto $dto, ?int $taxDefinitionId, int $accountId, int $editorUserId): TaxDefinition
    {
        $taxDefinition = $this->loadOrCreate($taxDefinitionId, $accountId);
        $taxDefinition->Name = $dto->name;
        $taxDefinition->TaxType = $dto->taxType;
        $taxDefinition->GeoType = $dto->geoType;
        $taxDefinition->Country = $dto->country;
        $taxDefinition->State = $dto->state;
        $taxDefinition->County = $dto->county;
        $taxDefinition->City = $dto->city;
        $taxDefinition->Description = $dto->description;
        $taxDefinition->Note = $dto->note;
        $taxDefinition->RangeCalculation = $dto->rangeCalculationMethod;
        $this->getTaxDefinitionWriteRepository()->saveWithModifier($taxDefinition, $editorUserId);
        return $taxDefinition;
    }

    protected function loadOrCreate(?int $taxDefinitionId, int $accountId): TaxDefinition
    {
        if ($taxDefinitionId) {
            $taxDefinition = $this->createTaxDefinitionLoader()->load($taxDefinitionId);
            if (!$taxDefinition) {
                throw CouldNotFindTaxDefinition::withId($taxDefinitionId);
            }
        } else {
            $taxDefinition = $this->createEntityFactory()->taxDefinition();
            $taxDefinition->AccountId = $accountId;
            $taxDefinition->Active = true;
        }
        return $taxDefinition;
    }
}
