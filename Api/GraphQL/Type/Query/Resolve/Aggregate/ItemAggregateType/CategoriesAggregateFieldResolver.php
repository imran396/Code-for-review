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

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ItemAggregateType;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Schema;
use RuntimeException;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Load\Aggregate\AggregateDataField;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\LotCategoryAggregateType;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CategoriesAggregateFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\ItemAggregateType
 */
class CategoriesAggregateFieldResolver extends CustomizableClass implements AggregateFieldResolverInterface
{
    use AggregateFieldResolverHelperCreateTrait;

    protected const DATA_FIELD_PREFIX = 'category_';
    protected Closure $type;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(Closure $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function dependentOnAggregateDataFields(array $referencedFieldNodes): array
    {
        $type = Schema::resolveType($this->type);
        if (
            !is_a($type, AggregateObjectType::class)
            || $type->name !== LotCategoryAggregateType::NAME
        ) {
            throw new RuntimeException('GQL type must be a LotCategoryAggregateType definition');
        }

        $fields = $this->createAggregateFieldResolverHelper()->collectAggregateDependentOnFields($type, $referencedFieldNodes);
        return array_map(
            static fn(AggregateDataField $dataField) => AggregateDataField::new()->construct(
                self::DATA_FIELD_PREFIX . $dataField->dataField,
                $dataField->aggregateFunction,
                self::DATA_FIELD_PREFIX . $dataField->alias
            ),
            $fields
        );
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        $categoryData = [];
        $dataFieldPrefixLength = strlen(self::DATA_FIELD_PREFIX);
        foreach ($objectValue as $key => $value) {
            if (str_starts_with($key, self::DATA_FIELD_PREFIX)) {
                $categoryData[substr($key, $dataFieldPrefixLength)] = $value;
            }
        }
        return $categoryData;
    }
}
