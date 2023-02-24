<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 06, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort;

use GraphQL\Type\Definition\EnumType;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort\Internal\SortFieldConfigurationFactoryCreateTrait;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class LotListSearchSortType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Sort
 */
class LotListSearchSortType extends CustomizableClass implements TypeInterface, AppContextAwareInterface
{
    use AppContextAwareTrait;
    use SortFieldConfigurationFactoryCreateTrait;

    public const NAME = 'LotListSearchSort';

    protected const SORT_KEYS = [
        Constants\GraphQL::LOT_LIST_ORDER_NUM_SORT,
        Constants\GraphQL::LOT_LIST_TIME_LEFT_SORT,
        Constants\GraphQL::LOT_LIST_TIME_LEFT_DESC_SORT,
        Constants\GraphQL::LOT_LIST_LOT_NUM_SORT,
        Constants\GraphQL::LOT_LIST_LOT_NAME_SORT,
        Constants\GraphQL::LOT_LIST_AUCTION_SORT,
        Constants\GraphQL::LOT_LIST_NEWEST_SORT,
        Constants\GraphQL::LOT_LIST_HIGHEST_PRICE_SORT,
        Constants\GraphQL::LOT_LIST_LOWEST_PRICE_SORT,
        Constants\GraphQL::LOT_LIST_BIDS_SORT,
        Constants\GraphQL::LOT_LIST_VIEWS_SORT,
        Constants\GraphQL::LOT_LIST_DISTANCE_SORT,
    ];

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
        $values = $this->createSortFieldConfigurationFactory()->create(self::SORT_KEYS, $this->getAppContext()->editorUserId);
        return new EnumType([
            'name' => static::NAME,
            'values' => $values
        ]);
    }
}
