<?php
/**
 * SAM-9424: Disabled 'Display lot quantity in catalog' does not make effect at Compact view
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 17, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Quantity\Check;

use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;

/**
 * Class LotQuantityRenderingChecker
 * @package
 */
class LotQuantityChecker extends CustomizableClass
{
    public const OP_DISPLAY_QUANTITY = 'displayQuantity'; // bool

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if we can display "Quantity" for the lot according values of its own fields
     * @param float|null $quantity
     * @param int $quantityScale
     * @param bool $isQuantityXMoney
     * @param bool $isDisplayQuantity
     * @return bool
     * #[Pure]
     */
    public function shouldDisplayForResponsivePure(
        ?float $quantity,
        int $quantityScale,
        bool $isQuantityXMoney,
        bool $isDisplayQuantity
    ): bool {
        if (!Floating::gt($quantity, 0, $quantityScale)) {
            return false;
        }

        if ($isDisplayQuantity) {
            return true;
        }

        return $isQuantityXMoney;
    }

    /**
     * @param float|null $quantity
     * @param int $quantityScale
     * @param bool $isQuantityXMoney
     * @param array $optionals = [
     *      self::OP_DISPLAY_QUANTITY => bool
     * ]
     * @return bool
     */
    public function shouldDisplayForResponsive(
        ?float $quantity,
        int $quantityScale,
        bool $isQuantityXMoney,
        array $optionals = []
    ): bool {
        return $this->shouldDisplayForResponsivePure(
            $quantity,
            $quantityScale,
            $isQuantityXMoney,
            $this->fetchOptionalDisplayQuantity($optionals)
        );
    }

    protected function fetchOptionalDisplayQuantity(array $optionals): bool
    {
        return (bool)($optionals[self::OP_DISPLAY_QUANTITY]
            ?? SettingsManager::new()->getForMain(Constants\Setting::DISPLAY_QUANTITY));
    }
}
