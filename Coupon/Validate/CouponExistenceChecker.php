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

namespace Sam\Coupon\Validate;

use QMySqli5DatabaseResult;
use Sam\Core\Constants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\CouponAuction\CouponAuctionReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\CouponLotCategory\CouponLotCategoryReadRepositoryCreateTrait;

/**
 * Class CouponExistenceChecker
 * @package Sam\Coupon\Validate
 */
class CouponExistenceChecker extends CustomizableClass
{
    use CouponAuctionReadRepositoryCreateTrait;
    use CouponLotCategoryReadRepositoryCreateTrait;
    use DbConnectionTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param float $amount
     * @param string $dateUtcIso
     * @param int $userId
     * @param int[] $auctionIds
     * @param int[] $lotCategoryIds
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existAvailableCoupon(
        int $accountId,
        float $amount,
        string $dateUtcIso,
        int $userId,
        array $auctionIds = [],
        array $lotCategoryIds = [],
        bool $isReadOnlyDb = false
    ): bool {
        // @formatter:off
        $amount = Floating::roundCalc($amount);
        $n = "\n";
        $query =
            "SELECT " . $n .
            "COUNT(1) AS coupon_avail " . $n .
            "FROM coupon AS c " . $n .
            "WHERE " . $n .
                $this->escape($amount) . " >= c.min_purchase_amt " . $n .
                "AND c.account_id = " . $this->escape($accountId) . " " . $n .
                "AND c.coupon_status_id = " . $this->escape(Constants\Coupon::STATUS_ACTIVE) . " " . $n .
                "AND " . $this->escape($dateUtcIso) . " >= c.start_date " . $n .
                "AND " . $this->escape($dateUtcIso) . " <= c.end_date " . $n .
                "AND (c.per_user = 0 OR (c.per_user > " . $n .
                    "(SELECT COUNT(1) FROM invoice_additional AS ia INNER JOIN invoice AS i ON i.id = ia.invoice_id " . $n .
                    "WHERE ia.coupon_id = c.id AND i.bidder_id = " . $this->escape($userId) . "))) " . $n;

        if (count($auctionIds) > 0) {
            $auction = '';
            foreach ($auctionIds as $auctionId) {
                $auction .= "auction_id = " . $this->escape($auctionId) . " OR ";
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
        // @formatter:on
        $this->enableReadOnlyDb($isReadOnlyDb);
        $this->query($query);
        $row = $this->fetchArray(QMySqli5DatabaseResult::FETCH_ASSOC);
        $avail = (int)$row['coupon_avail'];
        return ($avail > 0);
    }

    /**
     * @param int $couponId
     * @param int $auctionId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForAuction(int $couponId, int $auctionId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createCouponAuctionReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCouponId($couponId)
            ->filterAuctionId($auctionId)
            ->exist();
        return $isFound;
    }

    /**
     * @param int $couponId
     * @param int $lotCategoryId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function existForCategory(int $couponId, int $lotCategoryId, bool $isReadOnlyDb = false): bool
    {
        $isFound = $this->createCouponLotCategoryReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterCouponId($couponId)
            ->filterLotCategoryId($lotCategoryId)
            ->exist();
        return $isFound;
    }
}
