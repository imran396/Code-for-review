<?php
/**
 * SAM-4111: Invoice and settlement fields renderers
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 21, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Settlement\Render;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class SettlementPureRenderer
 * @package Sam\Core\Settlement\Render
 */
class SettlementPureRenderer extends CustomizableClass
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
     * @param int $settlementStatus
     * @return string
     */
    public function makeSettlementStatus(int $settlementStatus): string
    {
        return Constants\Settlement::$settlementStatusNames[$settlementStatus] ?? '';
    }

    /**
     * Render payment status based on settlement status
     * @param int $settlementStatus
     * @return string
     */
    public function makePaymentStatus(int $settlementStatus): string
    {
        return Constants\Settlement::$settlementStatusNames[$settlementStatus] ?? '';
    }
}
