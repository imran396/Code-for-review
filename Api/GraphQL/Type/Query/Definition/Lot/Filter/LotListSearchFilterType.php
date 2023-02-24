<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter;

use GraphQL\Type\Definition\InputObjectType;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\FilterFieldConfigurationFactoryCreateTrait;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareInterface;
use Sam\Api\GraphQL\Type\TypeRegistryAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotListSearchFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter
 */
class LotListSearchFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface, TypeRegistryAwareInterface
{
    use AppContextAwareTrait;
    use FilterFieldConfigurationFactoryCreateTrait;
    use TypeRegistryAwareTrait;

    public const NAME = 'LotListSearchFilter';

    protected const FILTER_FIELDS = [
        Constants\GraphQL::LOT_LIST_SEARCH_KEY_FILTER,
        Constants\GraphQL::LOT_LIST_CATEGORY_ID_FILTER,
        Constants\GraphQL::LOT_LIST_CATEGORY_MATCH_FILTER,
        Constants\GraphQL::LOT_LIST_ONLY_FEATURED_FILTER,
        Constants\GraphQL::LOT_LIST_EXCLUDE_CLOSED_LOTS_FILTER,
        Constants\GraphQL::LOT_LIST_MIN_PRICE_FILTER,
        Constants\GraphQL::LOT_LIST_MAX_PRICE_FILTER,
        Constants\GraphQL::LOT_LIST_AUCTIONEER_FILTER,
        Constants\GraphQL::LOT_LIST_LOT_NO_FILTER,
        Constants\GraphQL::LOT_LIST_ACCOUNT_FILTER,
        Constants\GraphQL::LOT_LIST_AUCTION_FILTER,
        Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER,
        Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER,
        Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function createTypeDefinition(): InputObjectType
    {
        $fields = $this->createFilterFieldConfigurationFactory()->create(
            self::FILTER_FIELDS,
            $this->getAppContext()->systemAccountId,
            $this->getTypeRegistry()
        );
        return new InputObjectType([
            'name' => self::NAME,
            'fields' => $fields
        ]);
    }
}
