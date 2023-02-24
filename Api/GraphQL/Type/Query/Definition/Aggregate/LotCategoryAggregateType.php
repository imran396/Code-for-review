<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Aggregate;

use GraphQL\Type\Definition\Type;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\LotCategoryType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotCategoryAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class LotCategoryAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'LotCategoryAggregate';

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
        $sourceType = $this->getTypeRegistry()->getTypeDefinition(LotCategoryType::NAME);
        return $this->createAggregateTypeBuilder()
            ->addAggregateFieldsForCompositeType($sourceType)
            ->addGroupField('parent_id', Type::id())
            ->build(self::NAME);
    }
}
