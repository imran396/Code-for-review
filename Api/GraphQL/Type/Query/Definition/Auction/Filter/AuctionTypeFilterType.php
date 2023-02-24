<?php
/**
 * SAM-10319: Implement a GraphQL prototype for a list of auctions
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter;

use GraphQL\Type\Definition\EnumType;
use Sam\Api\GraphQL\Type\AppContextAwareInterface;
use Sam\Api\GraphQL\Type\AppContextAwareTrait;
use Sam\Api\GraphQL\Type\TypeInterface;
use Sam\Auction\AuctionHelperAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionTypeFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter
 */
class AuctionTypeFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface
{
    use AppContextAwareTrait;
    use AuctionHelperAwareTrait;

    public const NAME = 'AuctionTypeFilter';

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
        $values = [
            Constants\GraphQL::AUCTION_LIST_TYPE_FILTER_ALL => ['value' => null]
        ];

        foreach ($availableTypes as $type) {
            $name = match ($type) {
                Constants\Auction::TIMED => Constants\GraphQL::AUCTION_LIST_TYPE_FILTER_TIMEDONLY,
                Constants\Auction::LIVE => Constants\GraphQL::AUCTION_LIST_TYPE_FILTER_LIVEONLY,
                Constants\Auction::HYBRID => Constants\GraphQL::AUCTION_LIST_TYPE_FILTER_HYBRIDONLY,
            };
            $values[$name] = ['value' => $type];
        }
        return $values;
    }
}
