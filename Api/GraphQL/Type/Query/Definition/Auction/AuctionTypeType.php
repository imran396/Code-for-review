<?php
/**
 * SAM-11161: Replace auction and lot enumeration fields with GraphQL enums
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep 01, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Auction;

use GraphQL\Type\Definition\EnumType;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Auction
 */
class AuctionTypeType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'AuctionType';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): EnumType
    {
        $values = [];
        foreach (Constants\GraphQL::AUCTION_TYPE_ENUM_VALUES as $value => $name) {
            $values[$name] = [
                'value' => $value,
                'description' => Constants\Auction::$auctionTypeLabels[$value],
            ];
        }
        return new EnumType(
            [
                'name' => self::NAME,
                'values' => $values
            ]
        );
    }
}
