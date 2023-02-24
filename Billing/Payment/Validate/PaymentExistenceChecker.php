<?php
/**
 * SAM-10909: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. General adjustments
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 02, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Payment\Validate;

use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\Payment\PaymentReadRepositoryCreateTrait;

/**
 * Class PaymentExistenceChecker
 * @package Sam\Billing\Payment\Validate
 */
class PaymentExistenceChecker extends CustomizableClass
{
    use PaymentReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $accountId
     * @param string $txnId
     * @param int $paymentMethodId
     * @param float|null $amount
     * @return bool
     */
    public function existByAccountIdAndTxnIdAndPaymentMethodId(
        int $accountId,
        string $txnId,
        int $paymentMethodId,
        ?float $amount = null
    ): bool {
        log_debug(composeSuffix(['txn_id' => $txnId, 'payment_method' => $paymentMethodId, 'amount' => $amount]));
        $paymentRepository = $this->createPaymentReadRepository()
            ->filterAccountId($accountId)
            ->filterActive(true)
            ->filterTxnId($txnId)
            ->filterPaymentMethodId($paymentMethodId);
        if (Floating::gt($amount, 0.)) {
            $paymentRepository->filterAmount($amount);
        }
        $isFound = $paymentRepository->exist();
        return $isFound;
    }

}
