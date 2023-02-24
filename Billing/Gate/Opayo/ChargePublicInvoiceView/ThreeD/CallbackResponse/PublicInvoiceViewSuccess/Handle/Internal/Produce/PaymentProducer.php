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

namespace Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce;

use DateTime;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Calculate\CalculatorCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Load\DataProviderCreateTrait;
use Sam\Billing\Gate\Opayo\ChargePublicInvoiceView\ThreeD\CallbackResponse\PublicInvoiceViewSuccess\Handle\Internal\Produce\Internal\Update\DataUpdaterCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;

class PaymentProducer extends CustomizableClass
{
    use DataProviderCreateTrait;
    use DataUpdaterCreateTrait;
    use CalculatorCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function produce(
        int $invoiceId,
        int $userId,
        int $creditCardId,
        float $amount,
        string $threeDStatusResponse,
        string $transactionId,
        DateTime $dateTime,
        int $editorUserId,
        bool $isReadOnlyDb = false,
    ): array {
        $dataProvider = $this->createDataProvider();
        $invoice = $dataProvider->loadInvoice($invoiceId, $isReadOnlyDb);

        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $calculator = $this->createCalculator();
        $balanceDue = $calculator->calculateBalanceDue($invoiceId, $isReadOnlyDb);
        $ccSurcharge = $calculator->calculateCcSurcharge($creditCardId, $balanceDue, $invoice->AccountId);

        $dataUpdater = $this->createDataUpdater();
        $invoiceAdditional = null;
        if ($ccSurcharge) {
            $invoiceAdditional = $dataUpdater->addInvoiceAdditionalCharge(
                $invoiceId,
                $ccSurcharge['name'],
                $ccSurcharge['amount'],
                $editorUserId
            );
        }


        $userBilling = $dataProvider->loadUserBilling($userId, $isReadOnlyDb);
        $ccNumber = $dataProvider->decrypt($userBilling->CcNumber);
        $note = 'Trans.:' . $transactionId . ' CC:' . substr($ccNumber, -4);
        if ($threeDStatusResponse === Constants\BillingOpayo::STATUS_ATTEMPTONLY) {
            $note .= ' 3DSecure=ATTEMPTONLY';
            log_info('Opayo 3DSecure=ATTEMPTONLY' . composeSuffix(['i' => $invoiceId]));
        }
        log_info('Opayo transaction id return: ' . $note);

        $payment = $dataUpdater->addInvoicePayment(
            $invoiceId,
            $amount,
            $userId,
            $note,
            $creditCardId,
            $dateTime
        );

        return [$invoiceAdditional, $payment];
    }
}
