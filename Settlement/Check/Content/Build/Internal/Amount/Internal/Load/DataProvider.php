<?php
/**
 * SAM-9766: Check Printing for Settlements: Implementation
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Content\Build\Internal\Amount\Internal\Load;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;
use Sam\Settlement\Calculate\SettlementCalculator;
use Sam\Settlement\Check\Calculate\SettlementCheckCalculator;

/**
 * Class DataProvider
 * @package Sam\Settlement\Check
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function calcBalanceDue(int $settlementId): float
    {
        return SettlementCalculator::new()->calcRoundedBalanceDue($settlementId);
    }

    public function loadLocale(int $accountId): string
    {
        return SettingsManager::new()->get(Constants\Setting::LOCALE, $accountId);
    }

    public function calcRemainingAmount(int $settlementId, ?int $settlementCheckId, bool $isReadOnlyDb = false): float
    {
        return SettlementCheckCalculator::new()->calcRemainingAmount($settlementId, $settlementCheckId, $isReadOnlyDb);
    }
}
