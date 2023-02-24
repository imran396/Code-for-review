<?php
/**
 *
 * SAM-4681: Coupon management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-06
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Coupon\Load;

use Coupon;
use CouponAuction;
use CouponLotCategory;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Filter\EntityLoader\CouponAllFilterTrait;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\Entity\Cache\EntityMemoryCacheManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\CouponAuction\CouponAuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\CouponLotCategory\CouponLotCategoryReadRepositoryCreateTrait;

/**
 * Class CouponLoader
 * @package Sam\Coupon\Load
 */
class CouponLoader extends EntityLoaderBase
{
    use CouponAllFilterTrait;
    use CouponAuctionReadRepositoryCreateTrait;
    use CouponLotCategoryReadRepositoryCreateTrait;
    use DbConnectionTrait;
    use EntityMemoryCacheManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return static
     */
    public function initInstance(): static
    {
        $this->initFilter();
        return $this;
    }

    /**
     * @param int|null $couponId
     * @param bool $isReadOnlyDb
     * @return Coupon|null
     */
    public function load(?int $couponId, bool $isReadOnlyDb = false): ?Coupon
    {
        if (!$couponId) {
            return null;
        }

        $fn = function () use ($couponId, $isReadOnlyDb) {
            $coupon = $this->prepareRepository($isReadOnlyDb)
                ->filterId($couponId)
                ->loadEntity();
            return $coupon;
        };

        $entityKey = $this->getEntityMemoryCacheManager()
            ->makeEntityCacheKey(Constants\MemoryCache::COUPON_ID, $couponId);
        $filterDescriptors = $this->collectFilterDescriptors();
        $coupon = $this->getEntityMemoryCacheManager()
            ->loadWithFilterConformityCheck($entityKey, $fn, $filterDescriptors);
        return $coupon;
    }

    /**
     * @param int $accountId
     * @param float $amount
     * @param string $invDate
     * @param int $userId
     * @param string|null $code null means empty value
     * @param array|null $auctionIds null leads to empty array
     * @param array|null $lotCategoryIds null leads to empty array
     * @param bool|null $isReadOnlyDb null leads to false
     * @return Coupon|null
     */
    public function loadAvailableCoupon(
        int $accountId,
        float $amount,
        string $invDate,
        int $userId,
        ?string $code = null,
        ?array $auctionIds = [],
        ?array $lotCategoryIds = [],
        ?bool $isReadOnlyDb = false
    ): ?Coupon {
        // @formatter:off
        $amount = round($amount, 2);
        $n = "\n";
        $query =
            "SELECT c.* " . $n .
            "FROM coupon AS c " . $n .
            "INNER JOIN timezone AS ctz ON ctz.id = c.timezone_id " . $n .
            "WHERE " . $n .
                $this->escape($amount) . " >= c.min_purchase_amt " . $n .
                "AND c.account_id = " . $this->escape($accountId) . " " . $n .
                "AND c.coupon_status_id = " . $this->escape(Constants\Coupon::STATUS_ACTIVE) . " " . $n .
                "AND " . $this->escape($invDate) . " >= c.start_date " . $n .
                "AND " . $this->escape($invDate) . " <= c.end_date " . $n .
                "AND (c.per_user = 0 OR (c.per_user > " . $n .
                "(SELECT COUNT(1) FROM invoice_additional AS ia INNER JOIN invoice AS i ON i.id = ia.invoice_id " . $n .
                "WHERE ia.coupon_id = c.id AND i.bidder_id = " . $this->escape($userId) . "))) " . $n;

        if (count($auctionIds) > 0) {
            $auction = '';
            foreach ($auctionIds as $auctionId) {
                $auction = "auction_id = " . $this->escape($auctionId) . " OR ";
            }
            $auction = rtrim($auction, "OR ");
            $query .=
                "AND IF ( " . $n .
                    "(SELECT COUNT(1) FROM coupon_auction WHERE coupon_id = c.id) = 0," . $n .
                    "1," . $n .
                    "(SELECT COUNT(1) FROM coupon_auction WHERE coupon_id = c.id and (" . $auction . "))" . $n .
                    ") > 0" . $n;
        }

        if (count($lotCategoryIds) > 0) {
            $lotCategory = '';
            $lotCategoryIds = array_unique($lotCategoryIds, SORT_NUMERIC);
            foreach ($lotCategoryIds as $lotCategoryId) {
                $lotCategory .= "lot_category_id = " . $this->escape($lotCategoryId) . " OR ";
            }
            $lotCategory = rtrim($lotCategory, "OR ");
            $query .=
                "AND IF ( " . $n .
                    "(SELECT COUNT(1) FROM coupon_lot_category WHERE coupon_id = c.id) = 0," . $n .
                    "1," . $n .
                    "(SELECT COUNT(1) FROM coupon_lot_category WHERE coupon_id = c.id and (" . $lotCategory . "))" . $n .
                    ") > 0" . $n;
        }

        if ($code) {
            $query .= "AND c.code = " . $this->escape($code) . " " . $n;
            $query .= "LIMIT 1 " . $n;
        }

        $this->enableReadOnlyDb($isReadOnlyDb);
        $dbResult = $this->query($query);
        $coupons = Coupon::InstantiateDbResult($dbResult);
        $coupon = $coupons[0] ?? null;
        return $coupon;
        // @formatter:on
    }

    /**
     * @param int $couponId
     * @param bool $isReadOnlyDb
     * @return CouponAuction[]
     */
    public function loadCouponAuctions(int $couponId, bool $isReadOnlyDb = false): array
    {
        $couponAuction = $this->createCouponAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCouponId($couponId)
            ->joinAuctionFilterAuctionStatusId(Constants\Auction::$availableAuctionStatuses)
            ->loadEntities();
        return $couponAuction;
    }

    /**
     * @param int $couponId
     * @param bool $isReadOnlyDb
     * @return CouponLotCategory[]
     */
    public function loadCouponLotCategories(int $couponId, bool $isReadOnlyDb = false): array
    {
        $couponLotCategories = $this->createCouponLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCouponId($couponId)
            ->joinLotCategoryFilterActive(true)
            ->loadEntities();
        return $couponLotCategories;
    }
}
