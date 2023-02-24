<?php
/**
 * SAM-4670: Payment record loader
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 3, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Load;

use Payment;
use Sam\Core\Load\EntityLoaderBase;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;

/**
 * Class PaymentLoader
 * @package Sam\Billing\Payment\Load
 */
class PaymentLoader extends EntityLoaderBase
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

    public function load(?int $paymentId, bool $isReadOnlyDb = false): ?Payment
    {
        if (!$paymentId) {
            return null;
        }

        $payment = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterId($paymentId)
            ->loadEntity();
        return $payment;
    }

    /**
     * @param int $tranId
     * @param string $tranType
     * @param bool $isReadOnlyDb
     * @return Payment[]
     */
    public function loadByTranIdAndTranType(int $tranId, string $tranType, bool $isReadOnlyDb = false): array
    {
        $payments = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranId($tranId)
            ->filterTranType($tranType)
            ->loadEntities();
        return $payments;
    }

    /**
     * @param string $tranCode
     * @param bool $isReadOnlyDb
     * @return Payment|null
     */
    public function loadByTranCode(string $tranCode, bool $isReadOnlyDb = false): ?Payment
    {
        $payment = $this->createPaymentReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterActive(true)
            ->filterTranCode($tranCode)
            ->loadEntity();
        return $payment;
    }
}
