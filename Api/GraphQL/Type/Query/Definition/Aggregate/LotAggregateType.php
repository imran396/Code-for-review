<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate;

use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\LotType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class LotAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'LotAggregate';

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
        $lotType = $this->getTypeRegistry()->getTypeDefinition(LotType::NAME);
        $itemAggregateField = $this->getTypeRegistry()->getTypeDefinition(ItemAggregateType::NAME);
        return $this->createAggregateTypeBuilder()
            ->addCountField()
            ->addAggregateFieldsForCompositeType($lotType)
            ->addField('item', $itemAggregateField)
            ->build(self::NAME);
    }
}
