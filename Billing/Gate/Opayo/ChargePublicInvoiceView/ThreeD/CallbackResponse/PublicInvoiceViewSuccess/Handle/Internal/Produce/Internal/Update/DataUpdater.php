<?php
/**
 * SAM-8962: Refactor SagePay UK 3d communication and processing logic
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           May 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Update;

use DateTime;
use InvoiceAdditional;
use Payment;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManager;
use Sam\Invoice\Common\Payment\InvoicePaymentManager;


class DataUpdater extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function addInvoiceAdditionalCharge(int $invoiceId, string $name, float $amount, int $editorUserId): InvoiceAdditional
    {
        return InvoiceAdditionalChargeManager::new()->add(
            Constants\Invoice::IA_CC_SURCHARGE,
            $invoiceId,
            $name,
            $amount,
            $editorUserId
        );
    }

    public function addInvoicePayment(
        int $invoiceId,
        float $amount,
        int $userId,
        string $note,
        int $creditCardId,
        DateTime $dateTime,
    ): Payment {
        return InvoicePaymentManager::new()->add(
            $invoiceId,
            Constants\Payment::PM_CC,
            $amount,
            $userId,
            $note,
            $dateTime,
            null,
            $creditCardId
        );
    }
}
