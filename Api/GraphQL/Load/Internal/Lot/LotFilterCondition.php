<?php
/**
 * SAM-10384: Implement a GraphQL prototype for a list of lots
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 03, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\GraphQL\Load\Internal\Lot;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotFilterCondition
 * @package Sam\Api\GraphQL\Load\Internal\LotList
 */
class LotFilterCondition extends CustomizableClass
{
    public array $accountIds = [];
    public ?int $auctionId = null;
    public ?int $auctioneerId = null;
    public array $auctionTypes = [];
    public array $categoryIds = [];
    public int $categoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY;
    public bool $onlyUnassigned = false;
    public bool $onlyFeatured = false;
    public array $timedOptions = [];
    public ?string $lotNo = null;
    public ?float $minPrice = null;
    public ?float $maxPrice = null;
    public ?string $searchKey = null;
    public bool $excludeClosedLots = false;
    public array $customFieldsCondition = [];

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
        foreach ($args as $key => $value) {
            switch ($key) {
                case Constants\GraphQL::LOT_LIST_SEARCH_KEY_FILTER:
                    $this->searchKey = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_CATEGORY_ID_FILTER:
                    $this->categoryIds = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_CATEGORY_MATCH_FILTER:
                    $this->categoryMatch = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_ONLY_FEATURED_FILTER:
                    $this->onlyFeatured = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_EXCLUDE_CLOSED_LOTS_FILTER:
                    $this->excludeClosedLots = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_MIN_PRICE_FILTER:
                    $this->minPrice = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_MAX_PRICE_FILTER:
                    $this->maxPrice = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_AUCTIONEER_FILTER:
                    $this->auctioneerId = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_LOT_NO_FILTER:
                    $this->lotNo = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_ACCOUNT_FILTER:
                    $this->accountIds = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_AUCTION_FILTER:
                    $this->auctionId = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_AUCTION_TYPE_FILTER:
                    $this->auctionTypes = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_TIMED_OPTION_FILTER:
                    $this->timedOptions = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_ONLY_UNASSIGNED_FILTER:
                    $this->onlyUnassigned = $value;
                    break;
                case Constants\GraphQL::LOT_LIST_CUSTOM_FIELDS_FILTER:
                    $this->customFieldsCondition = $value;
            }
        }
        return $this;
    }
}
