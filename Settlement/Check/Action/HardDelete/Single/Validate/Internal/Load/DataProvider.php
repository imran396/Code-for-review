<?php
/**
 * SAM-9887: Check Printing for Settlements: Single Check Processing - Single Settlement level (Part 1)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 11, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Check\Action\HardDelete\Single\Validate\Internal\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Check\Load\SettlementCheckLoader;
use SettlementCheck;

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

    public function loadSettlementCheck(int $settlementCheckId, bool $isReadOnlyDb = false): ?SettlementCheck
    {
        return SettlementCheckLoader::new()->load($settlementCheckId, $isReadOnlyDb);
    }
}
