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

namespace Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type;

use GraphQL\Type\Definition\EnumType;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\TypeInterface;

/**
 * Class LotListAuctionTypeFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Lot\Filter\Internal\Type
 */
class LotListAuctionTypeFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface
{
    use AppContextAwareTrait;
    use AuctionHelperAwareTrait;

    public const NAME = 'LotListAuctionTypeFilter';

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
                'values' => $this->makeValues()
            ]
        );
    }

    protected function makeValues(): array
    {
        $availableTypes = $this->getAuctionHelper()->getAvailableTypes($this->getAppContext()->systemAccountId);
        $values = [];

        foreach ($availableTypes as $type) {
            $name = match ($type) {
                Constants\Auction::TIMED => Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER_TIMED,
                Constants\Auction::LIVE => Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER_LIVE,
                Constants\Auction::HYBRID => Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER_HYBRID,
            };
            $values[$name] = ['value' => $type];
        }
        return $values;
    }
}
