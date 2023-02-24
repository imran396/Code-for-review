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

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet;

use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Math\Floating;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\AdminStackedTaxInvoicePaymentAuthorizeNetChargingInput as Input;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\AdminStackedTaxInvoicePaymentAuthorizeNetChargingResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\CimCharge\CimChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\ProfileCcCharge\ProfileCcChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\InputCcCharge\InputCcChargerCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Update\CcInfoUpdaterCreateTrait;
use Sam\Transform\Number\NumberFormatterAwareTrait;
use Sam\User\Load\Exception\CouldNotFindUser;

class AdminStackedTaxInvoicePaymentAuthorizeNetCharger extends CustomizableClass
{
    use CimChargerCreateTrait;
    use InputCcChargerCreateTrait;
    use ProfileCcChargerCreateTrait;
    use CcInfoUpdaterCreateTrait;
    use DataProviderCreateTrait;
    use NumberFormatterAwareTrait;

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

        $updateCcInfoResult = $this->createCcInfoUpdater()->update(
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
            if ($dataProvider->isAuthNetCim($invoice->AccountId)) {
                $chargeResult = $this->createCimCharger()->charge(
                    $invoice,
                    $amount,
                    $input->isReadOnlyDb
                );
            } else {
                $chargeResult = $this->createProfileCcCharger()->charge(
                    $invoice,
                    $amount,
                    $input->ccCode,
                    $input->isReadOnlyDb
                );
            }
        } else {
            $chargeResult = $this->createInputCcCharger()->charge(
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

        return $result
            ->enableHeldForReview($chargeResult->hasTransactionStatusHeldForReview())
            ->setNoteInfo($chargeResult->noteInfo)
            ->addSuccess(Result::OK_CHARGED);
    }
}
