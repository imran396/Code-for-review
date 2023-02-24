<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 1, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\CimCharge;

use Invoice;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\CimCharge\AdminInvoiceEditAuthorizeNetCimChargerResult as Result;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\CimCharge\Internal\Load\DataProviderCreateTrait;

class AdminInvoiceEditAuthorizeNetCimCharger extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function charge(
        Invoice $invoice,
        float $amount,
        bool $isReadOnlyDb = false,
    ): Result {
        $dataProvider = $this->createDataProvider();
        $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $isReadOnlyDb);
        $authNetCpi = $dataProvider->decryptValue($userBilling->AuthNetCpi);
        $authNetCppi = $dataProvider->decryptValue($userBilling->AuthNetCppi);
        $authNetCai = $dataProvider->decryptValue($userBilling->AuthNetCai);
        log_debug(composeSuffix(['Charging invoice using cim' => $authNetCpi]));

        $description = 'Charging Invoice ' . $invoice->InvoiceNo;
        $authNetCimManager = $dataProvider->createAuthNetCimManager($invoice->AccountId);
        $authNetCimManager->setParameter('order_description', $description);
        $authNetCimManager->setParameter('order_invoiceNumber', $invoice->InvoiceNo);
        $transactionAmount = sprintf("%1.2F", $amount);
        $authNetCimManager->setParameter('transaction_amount', $transactionAmount);
        $authNetCimManager->setParameter('transactionType', 'profileTransAuthCapture');
        $authNetCimManager->setParameter('customerProfileId', $authNetCpi);
        $authNetCimManager->setParameter('customerPaymentProfileId', $authNetCppi);
        if ($authNetCai !== '') {
            $authNetCimManager->setParameter('customerShippingAddressId', $authNetCai);
        }
        $authNetCimManager->createCustomerProfileTransactionRequest();

        if (!$authNetCimManager->isSuccessful()) {
            $errorMessage = 'Problem encountered bidder CIM payment. <br>' . $authNetCimManager->code . ': '
                . ($authNetCimManager->directResponse ? $authNetCimManager->directResponseErr : '');
            $errorMessage .= $authNetCimManager->text;
            return Result::new()->construct()->addError(Result::ERR_CHARGE, $errorMessage);
        }

        if ($authNetCimManager->isTransactionInReview()) {
            $note = 'Payment with Trans.: ' . $authNetCimManager->getTransactionId() . ' was marked as "Held for Review"';
            return Result::new()->construct($note)->addSuccess(Result::OK_HELD_FOR_REVIEW);
        }

        $note = 'Trans.:' . $authNetCimManager->getTransactionId();
        return Result::new()->construct($note)->addSuccess(Result::OK_CHARGED);
    }
}
