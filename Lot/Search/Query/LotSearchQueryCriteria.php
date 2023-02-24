<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 06, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Search\Query;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotSearchQueryCriteria
 * @package Sam\Lot\Search\Query
 */
class LotSearchQueryCriteria extends CustomizableClass
{
    public const ORDER_BY_DISTANCE = 'distance';
    public const ORDER_BY_RECENT_AUCTION = 'recent_auction';

    public ?int $accountId = null;
    public ?int $auctionId = null;
    public ?int $auctioneerId = null;
    public ?int $bidCountStrategy = null;
    public ?array $categoryIds = null;
    public int $categoryMatch = Constants\MySearch::CATEGORY_MATCH_ANY;
    public ?int $consignorId = null;
    public bool $isAscendingOrder = false;
    public bool $isAuctionReverse = false;
    public bool $isFeatured = false;
    public bool $isPrivateSearch = false;
    public ?int $lotBillingStatus = null;
    public array $lotCustomFieldsValue = [];
    public ?int $lotItemNum = null;
    public ?int $lotStatusId = null;
    public ?string $orderBy = null;
    public ?int $overallLotStatus = null;
    public ?float $priceMax = null;
    public ?float $priceMin = null;
    public ?int $reserveMeetStrategy = null;
    public string $searchKey = '';
    public ?int $skipAuctionId = null;
    public ?array $skipLotStatus = null;
    public ?int $userId = null;
    public ?int $winningBidderId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
