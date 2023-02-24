<?php
/**
 * Search bid increment duplicates
 *
 * SAM-5075: Data integrity checker - one bid increment table (eg live, or per auction) must have exactly one range
 * starting at zero
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           13 Sep, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BidIncrement\Validate;

use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepository;
use Sam\Storage\ReadRepository\Entity\BidIncrement\BidIncrementReadRepositoryCreateTrait;

/**
 * Class BidIncrementDataIntegrityChecker
 * @package Sam\Bidding\BidIncrement\Validate
 */
class BidIncrementDataIntegrityChecker extends CustomizableClass
{
    use BidIncrementReadRepositoryCreateTrait;
    use FilterAccountAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BidIncrementReadRepository
     */
    public function prepareBidIncrementDuplicateSearch(): BidIncrementReadRepository
    {
        $repo = $this->createBidIncrementReadRepository()
            ->select(
                [
                    'bi.auction_id',
                    'bi.auction_type',
                    'bi.lot_item_id',
                    'bi.amount',
                    'GROUP_CONCAT(bi.amount) as amounts',
                    'GROUP_CONCAT(bi.id) as ids',
                    'COUNT(1) as count_records',
                    'bi.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAccount()
            ->groupByAccountId()
            ->groupByAuctionId()
            ->groupByAuctionType()
            ->groupByLotItemId()
            ->having("amounts NOT LIKE '0.00%' AND amounts NOT LIKE '%,0.00%'")
            ->orderByAccountId()
            ->orderByAmount()
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
