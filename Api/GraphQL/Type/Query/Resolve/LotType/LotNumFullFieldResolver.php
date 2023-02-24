<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Resolve\LotType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;
use Sam\Lot\Render\LotRendererAwareTrait;

/**
 * Class LotNumFullFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\LotType
 */
class LotNumFullFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use LotRendererAwareTrait;

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
        return ['lot_num', 'lot_num_ext', 'lot_num_prefix'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): string
    {
        return $this->getLotRenderer()->makeLotNo($objectValue['lot_num'], $objectValue['lot_num_ext'], $objectValue['lot_num_prefix']);
    }
}
