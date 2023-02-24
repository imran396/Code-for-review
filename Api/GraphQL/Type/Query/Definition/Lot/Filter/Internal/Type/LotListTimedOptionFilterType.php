<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 05, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type;

use GraphQL\Type\Definition\EnumType;
use Sam\Core\Constants;
use Sam\Core\Lot\LotList\DataSourceMysql;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class LotListTimedOptionFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type
 */
class LotListTimedOptionFilterType extends CustomizableClass implements TypeInterface
{
    public const NAME = 'LotListTimedOptionFilter';

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
        return new EnumType(
            [
                'name' => self::NAME,
                'values' => [
                    Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER_REGULAR_BIDDING => [
                        'value' => DataSourceMysql::TOIO_REGULAR_BIDDING
                    ],
                    Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER_BUY_NOW => [
                        'value' => DataSourceMysql::TOIO_BUY_NOW
                    ],
                    Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER_MAKE_OFFER => [
                        'value' => DataSourceMysql::TOIO_MAKE_OFFER
                    ]
                ]
            ]
        );
    }
}
