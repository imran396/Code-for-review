<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 18, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate;

use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\TaxSchemaType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class TaxSchemaAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'TaxSchemaAggregate';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): AggregateObjectType
    {
        $taxDefinitionType = $this->getTypeRegistry()->getTypeDefinition(TaxSchemaType::NAME);
        return $this->createAggregateTypeBuilder()
            ->addCountField()
            ->addAggregateFieldsForCompositeType($taxDefinitionType)
            ->build(self::NAME);
    }
}
