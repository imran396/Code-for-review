<?php
/**
 * SAM-10785: Create in Admin Web the "Tax Schema Edit" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Edit\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\TaxSchema\TaxSchemaWriteRepositoryAwareTrait;
use Sam\Tax\StackedTax\Schema\Edit\Dto\TaxSchemaDto;
use Sam\Tax\StackedTax\Schema\Edit\Save\Internal\Load\DataProviderCreateTrait;
use Sam\Tax\StackedTax\Schema\LotCategory\Save\TaxSchemaLotCategoryProducerCreateTrait;
use TaxSchema;

/**
 * Class TaxSchemaProducer
 * @package Sam\Tax\StackedTax\Schema\Edit\Save
 */
class TaxSchemaProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use TaxSchemaLotCategoryProducerCreateTrait;
    use TaxSchemaTaxDefinitionProducerCreateTrait;
    use TaxSchemaWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function save(TaxSchemaDto $dto, ?int $taxSchemaId, int $accountId, int $editorUserId): TaxSchema
    {
        $taxSchema = $this->loadOrCreate($taxSchemaId, $accountId);
        $taxSchema->Name = $dto->name;
        $taxSchema->GeoType = $dto->geoType;
        $taxSchema->Country = $dto->country;
        $taxSchema->State = $dto->state;
        $taxSchema->County = $dto->county;
        $taxSchema->City = $dto->city;
        $taxSchema->ForInvoice = $dto->forInvoice;
        $taxSchema->ForSettlement = $dto->forSettlement;
        $taxSchema->Description = $dto->description;
        $taxSchema->Note = $dto->note;
        $taxSchema->AmountSource = $dto->amountSource;
        $this->getTaxSchemaWriteRepository()->saveWithModifier($taxSchema, $editorUserId);

        $this->createTaxSchemaLotCategoryProducer()->update($taxSchema->Id, $dto->lotCategoryIds, $editorUserId);
        $this->createTaxSchemaTaxDefinitionProducer()->update($taxSchema->Id, $dto->taxDefinitionIds, $editorUserId);

        return $taxSchema;
    }

    protected function loadOrCreate(?int $taxSchemaId, int $accountId): TaxSchema
    {
        if ($taxSchemaId) {
            $taxSchema = $this->createDataProvider()->loadTaxSchema($taxSchemaId);
        } else {
            $taxSchema = $this->createDataProvider()->newTaxSchema();
            $taxSchema->AccountId = $accountId;
            $taxSchema->Active = true;
        }
        return $taxSchema;
    }
}
