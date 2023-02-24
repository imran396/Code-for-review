<?php
/**
 * SAM-11239: Stacked Tax. Store invoice tax amounts per definition
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 15, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ServiceFeeEditForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\TaxDefinition\TaxDefinitionReadRepositoryCreateTrait;

/**
 * Class TaxDefinitionDataLoader
 * @package Sam\View\Admin\Form\ServiceFeeEditForm\Load
 */
class TaxDefinitionDataLoader extends CustomizableClass
{
    use TaxDefinitionReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return TaxDefinitionDto[]
     */
    public function load(?int $taxSchemaId, bool $isReadOnlyDb = false): array
    {
        if (!$taxSchemaId) {
            return [];
        }
        $rows = $this->createTaxDefinitionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->joinTaxSchemaTaxDefinitionFilterActive(true)
            ->joinTaxSchemaTaxDefinitionFilterTaxSchemaId($taxSchemaId)
            ->select(['tdef.id', 'tdef.name', 'collected_amount'])
            ->loadRows();
        $dtos = array_map(TaxDefinitionDto::new()->fromDbRow(...), $rows);
        return $dtos;
    }
}
