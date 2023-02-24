<?php
/**
 * SAM-9424: Disabled 'Display lot quantity in catalog' does not make effect at Compact view
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Quantity\Check\LotQuantityChecker;
use Sam\Settings\SettingsManager;

/**
 * Class QuantityChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch\PriceInfo\Quantity
 */
class LotQuantityRenderingChecker extends CustomizableClass
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
     * Check, if we should display "Quantity" header in "Compact View" mode. (SAM-9424)
     * Display it when DISPLAY_QUANTITY system parameter is On,
     * or when at least one lot has enabled "Quantity x Money" and filled "Quantity" value.
     * @param array $rows 2-dimension associative array [['quantity' => ..., 'isQuantityXMoney' => ...], ... ]
     * @param bool $isDisplayQuantity
     * @return bool
     * #[Pure]
     */
    public function isHeaderInCompactModePure(array $rows, bool $isDisplayQuantity): bool
    {
        $checker = LotQuantityChecker::new();
        foreach ($rows as $row) {
            $shouldDisplay = $checker->shouldDisplayForResponsivePure(
                (float)$row['quantity'],
                (int)$row['quantityScale'],
                (bool)$row['isQuantityXMoney'],
                $isDisplayQuantity
            );
            if ($shouldDisplay) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check, if we should display "Quantity" header in "Compact View" mode. (SAM-9424)
     * @param array $rows 2-dimension associative array [['quantity' => ..., 'isQuantityXMoney' => ...], ... ]
     * @param array $optionals
     * @return bool
     */
    public function isHeaderInCompactMode(array $rows, array $optionals = []): bool
    {
        return $this->isHeaderInCompactModePure(
            $rows,
            $this->fetchOptionalDisplayQuantity($optionals)
        );
    }

    protected function fetchOptionalDisplayQuantity(array $optionals): bool
    {
        return (bool)($optionals[self::OP_DISPLAY_QUANTITY]
            ?? SettingsManager::new()->getForMain(Constants\Setting::DISPLAY_QUANTITY));
    }
}
