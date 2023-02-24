<?php
/**
 * Search buyers premium duplicates
 *
 * SAM-5076: Data integrity checker - one buyer's premium table (eg live, or per auction) must have exactly one range
 * starting at zero
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           19 Sep, 2019
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\BuyersPremium\Validate;

use Sam\Core\Filter\Entity\FilterAccountAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepository;
use Sam\Storage\ReadRepository\Entity\BuyersPremiumRange\BuyersPremiumRangeReadRepositoryCreateTrait;

/**
 * Class BuyersPremiumDataIntegrityChecker
 * @package Sam\BuyersPremium\Validate
 */
class BuyersPremiumDataIntegrityChecker extends CustomizableClass
{
    use FilterAccountAwareTrait;
    use BuyersPremiumRangeReadRepositoryCreateTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return BuyersPremiumRangeReadRepository
     */
    public function prepareBuyersPremiumDuplicateSearch(): BuyersPremiumRangeReadRepository
    {
        $repo = $this->createBuyersPremiumRangeReadRepository()
            ->select(
                [
                    'bpr.auction_id',
                    'bpr.auction_type',
                    'bpr.buyers_premium_id',
                    'bpr.lot_item_id',
                    'bpr.user_id',
                    'bpr.amount',
                    'bp.account_id as bp_account_id',
                    'bp.name as auction_type_name',
                    'GROUP_CONCAT(bpr.amount) as amounts',
                    'GROUP_CONCAT(bpr.id) as ids',
                    'COUNT(1) as count_records',
                    'bpr.account_id',
                    'acc.name as account_name',
                ]
            )
            ->joinAccount()
            ->joinBuyersPremium()
            ->groupByAccountId()
            ->groupByAuctionId()
            ->groupByAuctionType()
            ->groupByBuyersPremiumId()
            ->groupByLotItemId()
            ->groupByUserId()
            ->having("amounts NOT LIKE '0.00%' AND amounts NOT LIKE '%,0.00%'")
            ->orderByAccountId()
            ->orderByAmount()
            ->orderById()
            ->setChunkSize(200);

        if ($this->getFilterAccountId()) {
            $repo->filterAccountIdOrBuyersPremiumAccountId($this->getFilterAccountId());
        }

        return $repo;
    }
}
