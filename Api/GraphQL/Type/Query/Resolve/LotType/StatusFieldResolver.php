<?php
/**
 * SAM-11161: Replace auction and lot enumeration fields with GraphQL enums
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\LotType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class StatusFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\LotType
 */
class StatusFieldResolver extends CustomizableClass implements FieldResolverInterface
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
        return ['lot_status_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed
    {
        return $objectValue['lot_status_id'];
    }
}
