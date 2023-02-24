<?php
/**
 * SAM-9960: Check Printing for Settlements: Payment List management at the "Settlement Edit" page
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 22, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Payment\Update\Internal;

use Payment;
use Sam\Billing\Payment\Load\PaymentLoader;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Load\SettlementLoader;

/**
 * Class DataProvider
 * @package Sam\Settlement\Payment\Update\Internal
 */
class DataProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadSettlementPayment(?int $paymentId, bool $isReadOnlyDb = false): ?Payment
    {
        return PaymentLoader::new()->load($paymentId, $isReadOnlyDb);
    }

    public function loadSettlementAccountId(int $settlementId): ?int
    {
        return SettlementLoader::new()->load($settlementId)->AccountId ?? null;
    }
}
