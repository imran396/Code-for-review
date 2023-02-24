<?php
/**
 * SAM-10915: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Authorize.Net invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 12, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet;

use Sam\Core\Constants;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\DataSource\DbConnectionTrait;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Invoice\Common\AdditionalCharge\InvoiceAdditionalChargeManagerAwareTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\AdminInvoiceEditAuthorizeNetChargingInput as Input;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\AdminInvoiceEditAuthorizeNetChargingResult as Result;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\CimCharge\AdminInvoiceEditAuthorizeNetCimChargerCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\Notify\NotifierCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\OnProfileCharge\AdminInvoiceEditAuthorizeNetOnProfileChargerCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\OtherCcCharge\AdminInvoiceEditAuthorizeNetOtherCcChargerCreateTrait;
use Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\AuthorizeNet\Internal\Update\AdminInvoiceEditAuthorizeNetCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Charge\Common\Total\InvoiceTotalsUpdaterCreateTrait;
use Sam\Invoice\Common\Payment\InvoicePaymentManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\Invoice\InvoiceWriteRepositoryAwareTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\Exception\CouldNotFindUser;

class AdminInvoiceEditAuthorizeNetCharger extends CustomizableClass
{
    use AdminInvoiceEditAuthorizeNetCimChargerCreateTrait;
    use AdminInvoiceEditAuthorizeNetOtherCcChargerCreateTrait;
    use AdminInvoiceEditAuthorizeNetOnProfileChargerCreateTrait;
    use AdminInvoiceEditAuthorizeNetCcInfoUpdaterCreateTrait;
    use CurrentDateTrait;
    use DbConnectionTrait;
    use InvoiceAdditionalChargeManagerAwareTrait;
    use InvoicePaymentManagerAwareTrait;
    use InvoiceTotalsUpdaterCreateTrait;
    use InvoiceWriteRepositoryAwareTrait;
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;
    use NotifierCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function charge(Input $input): Result
    {
        $result = Result::new()->construct();
        $dataProvider = $this->createDataProvider();
        $invoice = $input->invoice;

        $isFound = $dataProvider->existUser($invoice->BidderId, $input->isReadOnlyDb);
        if (!$isFound) {
            log_error(
                "Unable to charge invoice of deleted winning bidder"
                . composeSuffix(['u' => $invoice->BidderId])
            );
            return $result->addError(Result::ERR_INVOICE_USER_DELETED);
        }

        $user = $dataProvider->loadUser($invoice->BidderId, $input->isReadOnlyDb);
        if (!$user) {
            throw CouldNotFindUser::withId($invoice->BidderId);
        }

        $updateCcInfoResult = $this->createAdminInvoiceEditAuthorizeNetCcInfoUpdater()->update(
            $invoice->Id,
            trim($input->ccNumber),
            $input->ccType,
            $input->expMonth,
            $input->expYear,
            trim($input->ccCode),
            $input->isReplaceOldCard,
            $input->chargeOption,
            $input->editorUserId
        );

        if ($updateCcInfoResult->hasError()) {
            return $result->addError(Result::ERR_UPDATE_CC_INFO, $updateCcInfoResult->errorMessage());
        }

        $nf = $this->getNumberFormatter()->constructForInvoice($invoice->AccountId);
        $amount = $nf->parse($input->amountFormatted, 2, $invoice->AccountId);
        log_trace(composeLogData(['Charge amount' => $amount]));

        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE) {
            $userBilling = $dataProvider->loadUserBillingOrCreate($input->invoice->BidderId, $input->isReadOnlyDb);
            $ccType = $userBilling->CcType;
        } else {
            $ccType = $input->ccType;
        }

        $amount = $this->getInvoiceAdditionalChargeManager()->addCcSurchargeToTheAmount(
            $ccType,
            $amount,
            $invoice,
            $input->editorUserId,
        );


        if ($input->chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE) {
            if ($dataProvider->isAuthNetCim($invoice->AccountId)) {
                $chargeResult = $this->createAdminInvoiceEditAuthorizeNetCimCharger()->charge(
                    $invoice,
                    $amount,
                    $input->isReadOnlyDb
                );
            } else {
                $chargeResult = $this->createAdminInvoiceEditAuthorizeNetOnProfileCharger()->charge(
                    $invoice,
                    $amount,
                    $input->ccCode,
                    $input->isReadOnlyDb
                );
            }
        } else {
            $chargeResult = $this->createAdminInvoiceEditAuthorizeNetOtherCcCharger()->charge(
                $invoice,
                trim($input->firstName),
                trim($input->lastName),
                trim($input->expMonth),
                trim($input->expYear),
                $input->ccType,
                $input->ccNumber,
                $input->ccCode,
                $amount,
                $input->isReadOnlyDb
            );
        }

        if ($chargeResult->hasError()) {
            return $result->addError(Result::ERR_CHARGE, $chargeResult->errorMessage());
        }

        if ($input->onlyCharge) {
            return $result->addSuccess(Result::OK_CHARGED);
        }

        $this->getDb()->TransactionBegin();

        $this->getInvoicePaymentManager()->add(
            $input->invoice->Id,
            Constants\Payment::PM_CC,
            $amount,
            $input->editorUserId,
            $chargeResult->note,
            $this->getCurrentDateUtc(),
            null,
            $ccType
        );

        $balanceDue = $dataProvider->calculateBalanceDue($invoice);
        log_trace(composeLogData(['Balance due' => $balanceDue]));
        if (
            !$chargeResult->hasTransactionStatusHeldForReview()
            && Floating::lteq($balanceDue, 0.)
        ) {
            $invoice->toPaid();
        }
        $invoice = $this->createInvoiceTotalsUpdater()->calcAndAssign($invoice);
        $this->getInvoiceWriteRepository()->saveWithModifier($invoice, $input->editorUserId);

        $this->getDb()->TransactionCommit();

        $this->createNotifier()->notify($invoice, $input->editorUserId);

        return $result->addSuccess(Result::OK_CHARGED);
    }
}
