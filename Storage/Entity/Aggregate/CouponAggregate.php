<?php
/**
 * SAM-4819: Entity aware traits
 *
 * Aggregate class can be used, when we need to operate we several Coupon entities in one class namespace.
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           1/21/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Storage\Entity\Aggregate;

use Coupon;
use Sam\Coupon\Load\CouponLoaderAwareTrait;

/**
 * Class CouponAggregate
 * @package Sam\Storage\Entity\Aggregate
 */
class CouponAggregate extends EntityAggregateBase
{
    use CouponLoaderAwareTrait;

    private ?int $couponId = null;
    private ?Coupon $coupon = null;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Empty aggregated entities
     * @return static
     */
    public function clear(): static
    {
        $this->couponId = null;
        $this->coupon = null;
        return $this;
    }

    // --- coupon.id ---

    /**
     * @return int|null
     */
    public function getCouponId(): ?int
    {
        return $this->couponId;
    }

    /**
     * @param int|null $couponId
     * @return static
     */
    public function setCouponId(int|null $couponId): static
    {
        $couponId = $couponId ?: null;
        if ($this->couponId !== $couponId) {
            $this->clear();
        }
        $this->couponId = $couponId;
        return $this;
    }

    // --- Coupon ---

    /**
     * @return bool
     */
    public function hasCoupon(): bool
    {
        return ($this->coupon !== null);
    }

    /**
     * Return Coupon object
     * @param bool $isReadOnlyDb
     * @return Coupon|null
     */
    public function getCoupon(bool $isReadOnlyDb = false): ?Coupon
    {
        if ($this->coupon === null) {
            $this->coupon = $this->getCouponLoader()
                ->clear()
                ->enableEntityMemoryCacheManager($this->isMemoryCaching())
                ->load($this->couponId, $isReadOnlyDb);
        }
        return $this->coupon;
    }

    /**
     * @param Coupon|null $coupon
     * @return static
     */
    public function setCoupon(?Coupon $coupon = null): static
    {
        if (!$coupon) {
            $this->clear();
        } elseif ($coupon->Id !== $this->couponId) {
            $this->clear();
            $this->couponId = $coupon->Id;
        }
        $this->coupon = $coupon;
        return $this;
    }
}
