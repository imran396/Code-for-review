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

namespace Sam\Api\GraphQL\Type\Query\Resolve\QueryType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Exception\AuthorizationError;
use Sam\Api\GraphQL\Load\Internal\TaxDefinition\TaxDefinitionFilterCondition;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Privilege\Validate\RoleCheckerAwareTrait;

/**
 * Class TaxDefinitionsFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\QueryType
 */
class TaxDefinitionsFieldResolver extends CustomizableClass implements FieldResolverInterface
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

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed
    {
        $isAdmin = $this->getRoleChecker()->isAdmin($appContext->editorUserId, true);
        if (!$isAdmin) {
            throw new AuthorizationError('Forbidden. You don\'t have access to perform this request', 4);
        }

        $filterCondition = TaxDefinitionFilterCondition::new()->fromArgs($args['filter'] ?? []);
        $order = $args['order'] ?? [];
        return $appContext->dataLoader->loadTaxDefinitions($filterCondition, $order, $args['limit'], $args['offset']);
    }
}
