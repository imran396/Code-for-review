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

namespace Sam\Api\GraphQL\Load\Internal\Auction;

use Sam\Auction\AuctionList\DataSourceMysql;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class AuctionFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal
 */
class AuctionFilterCondition extends CustomizableClass
{
    public int $status = DataSourceMysql::DEF;
    public ?string $auctionType = null;
    public ?int $account = null;
    public ?int $auctioneer = null;
    public bool $onlyRegisteredIn = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function fromGraphQlQueryArgs(array $args): static
    {
        if (array_key_exists(Constants\GraphQL::AUCTION_LIST_STATUS_FILTER, $args)) {
            $this->status = $args[Constants\GraphQL::AUCTION_LIST_STATUS_FILTER];
        }
        if (array_key_exists(Constants\GraphQL::AUCTION_LIST_AUCTION_TYPE_FILTER, $args)) {
            $this->auctionType = $args[Constants\GraphQL::AUCTION_LIST_AUCTION_TYPE_FILTER];
        }
        if (array_key_exists(Constants\GraphQL::AUCTION_LIST_ACCOUNT_FILTER, $args)) {
            $this->account = $args[Constants\GraphQL::AUCTION_LIST_ACCOUNT_FILTER];
        }
        if (array_key_exists(Constants\GraphQL::AUCTION_LIST_AUCTIONEER_FILTER, $args)) {
            $this->auctioneer = $args[Constants\GraphQL::AUCTION_LIST_AUCTIONEER_FILTER];
        }
        if (array_key_exists(Constants\GraphQL::AUCTION_LIST_ONLY_REGISTERED_IN_FILTER, $args)) {
            $this->onlyRegisteredIn = $args[Constants\GraphQL::AUCTION_LIST_ONLY_REGISTERED_IN_FILTER];
        }
        return $this;
    }
}
