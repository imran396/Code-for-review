<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 23, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\QueryType;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Exception\AuthorizationError;
use Sam\Api\GraphQL\Load\Internal\Lot\LotFilterCondition;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\QueryType\Internal\LotListCustomFieldFilterResolver;
use Sam\Core\Service\CustomizableClass;

/**
 * Class MyItemsFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\QueryType
 */
class MyItemsFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function dependentOnDataFields(array $referencedFieldNodes): array
    {
        return [];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): Deferred
    {
        if (!$appContext->editorUserId) {
            throw new AuthorizationError('Forbidden. Authorization required.', 403);
        }

        $filterData = $args['filter'] ?? [];
        $filterData = LotListCustomFieldFilterResolver::new()->resolve($filterData, $info->fieldDefinition);
        $filterCondition = LotFilterCondition::new()->fromGraphQlQueryArgs($filterData);
        return $appContext->dataLoader->loadLotsForMyItems(
            pageType: $args['pageType'],
            filterCondition: $filterCondition,
            sortCriteria: $args['sort'] ?? null,
            limit: $args['limit'],
            offset: $args['offset']
        );
    }
}
