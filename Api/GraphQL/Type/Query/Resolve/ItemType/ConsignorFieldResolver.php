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

namespace Sam\Api\GraphQL\Type\Query\Resolve\ItemType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverHelperCreateTrait;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;

/**
 * Class ConsignorFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\ItemType
 */
class ConsignorFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use FieldResolverHelperCreateTrait;

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
        return ['consignor_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed
    {
        if ($objectValue['consignor_id'] === null) {
            return null;
        }
        $consignorData = ['id' => $objectValue['consignor_id']];
        if ($this->createFieldResolverHelper()->hasEnoughDataToResolve($info, $consignorData)) {
            return $consignorData;
        }
        return $appContext->dataLoader->loadUser((int)$objectValue['consignor_id']);
    }
}
