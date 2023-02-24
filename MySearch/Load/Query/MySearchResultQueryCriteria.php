<?php
/**
 * SAM-6606: Refactoring classes in the \MySearch namespace
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov. 08, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\MySearch\Load\Query;

use Sam\Lot\Search\Query\LotSearchQueryCriteria;

/**
 * Class MySearchResultQueryCriteria
 * @package Sam\MySearch\Load\Query
 */
class MySearchResultQueryCriteria extends LotSearchQueryCriteria
{
    public array $auctionType = [];
    public array $skipAuctionLotIds = [];
    public bool $hasBestOffer = false;
    public bool $hasBuyNow = false;
    public bool $isExcludeClosed = false;
    public bool $isRegularBidding = false;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }
}
