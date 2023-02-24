<?php
/**
 * SAM-10787: Create in Admin Web the "Tax Schema List" page (Stage-1)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 19, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\QueryType;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Load\Internal\TaxSchema\TaxSchemaDataLoader;
use Sam\Api\GraphQL\Load\Internal\TaxSchema\TaxSchemaFilterCondition;
use Sam\Api\GraphQL\Type\Query\Definition\Aggregate\AggregateObjectType;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class TaxSchemaAggregateFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\QueryType
 */
class TaxSchemaAggregateFieldResolver extends CustomizableClass implements AggregateFieldResolverInterface
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
        $filterCondition = TaxSchemaFilterCondition::new()->fromArgs($args['filter'] ?? []);

        $result = TaxSchemaDataLoader::new()->aggregate(
            filterCondition: $filterCondition,
            limit: $args['limit'],
            offset: $args['offset'],
            fields: $fields,
            editorUserId: $appContext->editorUserId,
            systemAccountId: $appContext->systemAccountId,
            isReadOnlyDb: $appContext->dataLoader->isReadOnlyDb
        );
        return $result;
    }
}
