<?php
/**
 * SAM-10844: Extend parameter filtering on auction lots, my items level
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 27, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\QueryType;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Load\Internal\Lot\LotDataLoader;
use Sam\Api\GraphQL\Load\Internal\Lot\LotFilterCondition;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\QueryType\Internal\LotListCustomFieldFilterResolver;
use Sam\Core\Service\CustomizableClass;

/**
 * Class CatalogLotsAggregateFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\QueryType
 */
class CatalogLotsAggregateFieldResolver extends CustomizableClass implements AggregateFieldResolverInterface
{
    use AggregateFieldResolverHelperCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function dependentOnAggregateDataFields(array $referencedFieldNodes): array
    {
        return [];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): array
    {
        /** @var ListOfType $returnType */
        $returnType = $info->returnType;
        /** @var AggregateObjectType $rootType */
        $rootType = $returnType->getWrappedType();
        $fields = $this->createAggregateFieldResolverHelper()
            ->collectAggregateDependentOnFields($rootType, $info->lookAhead()->queryPlan());
        $filterData = LotListCustomFieldFilterResolver::new()->resolve($args['filter'] ?? [], $info->fieldDefinition);
        $filterCondition = LotFilterCondition::new()->fromGraphQlQueryArgs($filterData);

        $result = LotDataLoader::new()->aggregateForCatalog(
            auctionId: $args['auctionId'],
            systemAccountId: $appContext->systemAccountId,
            editorUserId: $appContext->editorUserId,
            fields: $fields,
            filterCondition: $filterCondition,
            limit: $args['limit'],
            offset: $args['offset'],
            isReadOnlyDb: $appContext->dataLoader->isReadOnlyDb
        );
        return $result;
    }
}
