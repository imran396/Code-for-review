<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 22, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\Aggregate\AggregateFieldResolverInterface;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class Resolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve
 */
class DefaultFieldResolver extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info)
    {
        /** @var FieldResolverInterface|AggregateFieldResolverInterface|null $resolver */
        $resolver = $info->fieldDefinition->config['resolver'] ?? null;
        if ($resolver) {
            return $resolver->resolve($objectValue, $args, $appContext, $info);
        }

        $fieldName = $info->fieldName;
        return $objectValue[$fieldName] ?? null;
    }
}
