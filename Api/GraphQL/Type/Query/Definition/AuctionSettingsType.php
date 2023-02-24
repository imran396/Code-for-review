<?php
/**
 * SAM-10493: Implement a GraphQL nested structure for a lot
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class AuctionSettingsType
 * @package Sam\Api\GraphQL\Type\Query\Definition
 */
class AuctionSettingsType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'AuctionSettings';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): ObjectType
    {
        return new ObjectType([
            'name' => self::NAME,
            'fields' => [
                'buy_now_restriction' => Type::string(),
                'buy_now_unsold' => Type::boolean(),
                'conditional_sales' => Type::boolean(),
                'confirm_timed_bid' => Type::boolean(),
                'inline_bid_confirm' => Type::boolean(),
                'show_auction_starts_ending' => Type::boolean(),
            ]
        ]);
    }
}
