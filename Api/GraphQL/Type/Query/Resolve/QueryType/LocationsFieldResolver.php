<?php
/**
 * SAM-10719: SAM 3.7 Taxes. Add Search/Filter panel at Account Location List page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\QueryType;

use GraphQL\Deferred;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Exception\AuthorizationError;
use Sam\Api\GraphQL\Load\Internal\Location\LocationFilterCondition;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class LocationsFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\QueryType
 */
class LocationsFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldResolverHelperCreateTrait;
    use RoleCheckerAwareTrait;

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
        $isAdmin = $this->getRoleChecker()->isAdmin($appContext->editorUserId, true);
        if (!$isAdmin) {
            throw new AuthorizationError('Forbidden. You don\'t have access to perform this request', 4);
        }

        $filterCondition = LocationFilterCondition::new()->fromArgs($args['filter'] ?? []);
        $order = $args['order'] ?? [];

        if ($args['unique']) {
            /** @var ListOfType $returnType */
            $returnType = $info->fieldDefinition->getType();
            /** @var ObjectType $fieldType */
            $fieldType = $returnType->getWrappedType();
            $fields = $this->createFieldResolverHelper()->collectDependentOnFields($fieldType, $info->lookAhead()->queryPlan());
            return $appContext->dataLoader->loadLocationsWithGrouping(
                $fields,
                $filterCondition,
                $order,
                $args['limit'],
                $args['offset']
            );
        }

        return $appContext->dataLoader->loadLocations($filterCondition, $order, $args['limit'], $args['offset']);
    }
}
