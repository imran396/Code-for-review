<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           June 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargeSettlement\ThreeD\CallbackResponse\SettlementSuccess\Handle\Internal\Payment;

use Payment;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settlement\Payment\Update\SettlementPayment;
use Sam\Settlement\Payment\Update\SettlementPaymentProducer;


class PaymentProducer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(
        int $settlementId,
        int $userId,
        float $amount,
        string $note
    ): Payment {
        return SettlementPaymentProducer::new()->produce(
            SettlementPayment::new()->constructNew(
                $settlementId,
                Constants\Payment::PM_CC,
                $amount,
                $note
            ),
            $userId
        );
    }
}
