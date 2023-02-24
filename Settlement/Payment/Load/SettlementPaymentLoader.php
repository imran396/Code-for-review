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

namespace Sam\Settlement\Payment\Load;

use Payment;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;

/**
 * Class SettlementPaymentLoader
 * @package Sam\Settlement\Payment\Load
 */
class SettlementPaymentLoader extends CustomizableClass
{
    use PaymentReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load payments for settlement
     * @param int $settlementId
     * @param array $skipPaymentIds
     * @param bool $isReadOnlyDb
     * @return Payment[]
     */
    public function loadBySettlementId(int $settlementId, array $skipPaymentIds = [], bool $isReadOnlyDb = false): array
    {
        $payments = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($settlementId)   // payment.tran_id = settlement.id
            ->filterTranType(Constants\Payment::TT_SETTLEMENT)
            ->skipId($skipPaymentIds)
            ->orderById()
            ->loadEntities();
        return $payments;
    }
}
