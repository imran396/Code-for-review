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

use GraphQL\Type\Schema;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\Internal\AggregateTypeBuilderCreateTrait;
use Sam\Api\GraphQL\Type\Query\Definition\ItemType;
use Sam\Api\GraphQL\Type\Query\Resolve;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ItemAggregateType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Aggregate
 */
class ItemAggregateType extends CustomizableClass implements TypeInterface, TypeRegistryAwareInterface
{
    use AggregateTypeBuilderCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'ItemAggregate';

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
        $itemType = $this->getTypeRegistry()->getTypeDefinition(ItemType::NAME);
        $categoryAggregateField = $this->getTypeRegistry()->getTypeDefinition(LotCategoryAggregateType::NAME);
        $categoriesResolver = Resolve\Aggregate\ItemAggregateType\CategoriesAggregateFieldResolver::new()->construct($categoryAggregateField);
        $typeBuilder = $this->createAggregateTypeBuilder()
            ->addCountField()
            ->addAggregateFieldsForCompositeType($itemType)
            ->addField('categories', $categoryAggregateField, $categoriesResolver);

        /** @var AggregateObjectType $customFieldCollectionAggregateType */
        $customFieldCollectionAggregateType = Schema::resolveType(
            $this->getTypeRegistry()->getTypeDefinition(ItemCustomFieldCollectionAggregateType::NAME)
        );
        if (count($customFieldCollectionAggregateType->getFieldNames())) {
            $typeBuilder->addField('custom_fields', $customFieldCollectionAggregateType);
        }
        return $typeBuilder->build(self::NAME);
    }
}
