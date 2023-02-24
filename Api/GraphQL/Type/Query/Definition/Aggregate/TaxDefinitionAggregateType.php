<?php
/**
 * SAM-10782: Create in Admin Web the "Tax Definition List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate;

use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\TaxDefinitionType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxDefinitionAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class TaxDefinitionAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'TaxDefinitionAggregate';

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
        $taxDefinitionType = $this->getTypeRegistry()->getTypeDefinition(TaxDefinitionType::NAME);
        return $this->createAggregateTypeBuilder()
            ->addCountField()
            ->addAggregateFieldsForCompositeType($taxDefinitionType)
            ->build(self::NAME);
    }
}
