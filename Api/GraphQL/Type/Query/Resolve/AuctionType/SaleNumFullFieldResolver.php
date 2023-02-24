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

namespace Sam\Api\GraphQL\Type\Query\Resolve\AuctionType;

use GraphQL\Type\Definition\ResolveInfo;
use Sam\Auction\Render\AuctionRendererAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\AppContext;
use Sam\Api\GraphQL\Type\Query\Resolve\FieldResolverInterface;

/**
 * Class SaleNumFullFieldResolver
 * @package Sam\Api\GraphQL\Type\Query\Resolve\AuctionType
 */
class SaleNumFullFieldResolver extends CustomizableClass implements FieldResolverInterface
{
    use AuctionRendererAwareTrait;

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
        return ['sale_num', 'sale_num_ext'];
    }

    public function resolve(array $objectValue, array $args, AppContext $appContext, ResolveInfo $info): string
    {
        return $this->getAuctionRenderer()->makeSaleNo($objectValue['sale_num'], $objectValue['sale_num_ext']);
    }
}
