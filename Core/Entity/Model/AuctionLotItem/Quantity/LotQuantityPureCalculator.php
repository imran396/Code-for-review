<?php
/**
 * SAM-9583: Extract quantity related calculation to pure service
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 18, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\AuctionLotItem\Quantity;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;

/**
 * Class LotQuantityPureCalculator
 * @package Sam\Core\Entity\Model\AuctionLotItem\Quantity
 */
class LotQuantityPureCalculator extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * "Quantity x Money" lot configuration is effective,
     * when the property is enabled and items quantity is more than zero.
     * @param float|null $quantity
     * @param bool $isQuantityXMoney
     * @return bool
     */
    public function isQuantityXMoneyEffective(?float $quantity, int $quantityScale, bool $isQuantityXMoney): bool
    {
        return Floating::gt($quantity, 0, $quantityScale) && Floating::neq($quantity, 1, $quantityScale) && $isQuantityXMoney;
    }

    /**
     * Multiply initial to quantity if this is required according quantity and quantity-x-money values.
     * Don't multiply when:
     * - Zero quantity;
     * - 1 quantity;
     * - No quantity-x-money;
     * @param float|null $initial
     * @param float|null $quantity
     * @param int $quantityScale
     * @param bool $isQuantityXMoney
     * @return float|null
     */
    public function multiplyEffectively(?float $initial, ?float $quantity, int $quantityScale, bool $isQuantityXMoney): ?float
    {
        if (!$initial) { // null or 0.
            return $initial;
        }

        if ($this->isQuantityXMoneyEffective($quantity, $quantityScale, $isQuantityXMoney)) {
            $initial *= round($quantity, $quantityScale);
        }
        return $initial;
    }
}
