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

namespace Sam\Api\GraphQL\Type\Query\Resolve\LotCategoryType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Core\Service\CustomizableClass;

/**
 * Class ParentFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\LotCategoryType
 */
class ParentFieldResolver extends CustomizableClass implements FieldResolverInterface
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
        return ['parent_id'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): mixed
    {
        $parentCategoryId = (int)$objectValue['parent_id'];
        if (!$parentCategoryId) {
            return null;
        }
        return $appContext->dataLoader->loadLotCategory($parentCategoryId);
    }
}
