<?php
/**
 * SAM-3696 : Coupon related repositories  https://bidpath.atlassian.net/browse/SAM-3696
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 May, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 * Usage samples:
 *
 * You can pass single value or array to filter..() methods
 * Don't forget to turn on ->enableReadOnlyDb(true) when it is possible
 *
 * // Sample1. Check, count and load array of CouponAuction filtered by criteria
 * $couponAuctionRepository = \Sam\Storage\ReadRepository\Entity\CouponAuction\CouponAuctionReadRepository::new()
 *     ->filterId($ids);   // array passed as argument
 *
 * $isFound = $couponAuctionRepository->exist();
 * $count = $couponAuctionRepository->count();
 * $couponAuctions = $couponAuctionRepository->loadEntities();
 *
 * // Sample2. Load single CouponAuction
 * $couponAuctionRepository = \Sam\Storage\ReadRepository\Entity\CouponAuction\CouponAuctionReadRepository::new()
 *     ->filterId(1);
 * $couponAuction = $couponAuctionRepository->loadEntity();
 */

namespace Sam\Storage\ReadRepository\Entity\CouponAuction;

/**
 * Class CouponAuctionReadRepository
 * @package Sam\Storage\ReadRepository\Entity\CouponAuction
 */
class CouponAuctionReadRepository extends AbstractCouponAuctionReadRepository
{
    /** @var string[] */
    protected array $joins = [
        'auction' => 'JOIN auction a ON a.id = cauc.auction_id',
    ];

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Join auction table
     * @return static
     */
    public function joinAuction(): static
    {
        $this->join('auction');
        return $this;
    }

    /**
     * Define filtering by a.auction_status_id
     * @param int|int[] $auctionStatusIds
     * @return static
     */
    public function joinAuctionFilterAuctionStatusId(int|array|null $auctionStatusIds): static
    {
        $this->joinAuction();
        $this->filterArray('a.auction_status_id', $auctionStatusIds);
        return $this;
    }
}
