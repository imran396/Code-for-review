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
use Sam\Auction\AuctionList\DataSourceMysql;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AuctionStatusFilterType
 * @package Sam\Api\GraphQL\Type\Query\Definition\Auction\Filter
 */
class AuctionStatusFilterType extends CustomizableClass implements TypeInterface, AppContextAwareInterface
{
    use AppContextAwareTrait;
    use SettingsManagerAwareTrait;

    public const NAME = 'AuctionStatusFilter';

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
                'values' => $this->makeValues(),
            ]
        );
    }

    protected function makeValues(): array
    {
        $visibleAuctionStatuses = (int)$this->getSettingsManager()->get(
            Constants\Setting::VISIBLE_AUCTION_STATUSES,
            $this->getAppContext()->systemAccountId
        );
        $values = [
            Constants\GraphQL::AUCTION_LIST_STATUS_FILTER_ALL => ['value' => DataSourceMysql::DEF]
        ];
        if ($visibleAuctionStatuses & 1) {
            $values[Constants\GraphQL::AUCTION_LIST_STATUS_FILTER_BIDDING_UPCOMING] = ['value' => DataSourceMysql::BIDDING_UPCOMING];
        }
        if ($visibleAuctionStatuses & 2) {
            $values[Constants\GraphQL::AUCTION_LIST_STATUS_FILTER_BIDDING] = ['value' => DataSourceMysql::BIDDING];
        }
        if ($visibleAuctionStatuses & 4) {
            $values[Constants\GraphQL::AUCTION_LIST_STATUS_FILTER_UPCOMING] = ['value' => DataSourceMysql::UPCOMING];
        }
        if ($visibleAuctionStatuses & 8) {
            $values[Constants\GraphQL::AUCTION_LIST_STATUS_FILTER_CLOSED] = ['value' => DataSourceMysql::CLOSED];
        }
        return $values;
    }
}
