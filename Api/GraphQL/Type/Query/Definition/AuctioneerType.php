<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 21, 2022
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
 * Class AuctioneerType
 * @package Sam\Api\GraphQL\Type
 */
class AuctioneerType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'Auctioneer';

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
        return new ObjectType(
            [
                'name' => self::NAME,
                'fields' => [
                    'id' => Type::id(),
                    'name' => Type::string(),
                ]
            ]
        );
    }
}
