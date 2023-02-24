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

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Update;

use Invoice;
use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Update\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\AuthorizeNet\Internal\Update\CcInfoUpdatingResult as Result;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\User\Load\Exception\CouldNotFindUser;
use User;

class CcInfoUpdater extends CustomizableClass
{
    use DataProviderCreateTrait;
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;
    use CcExpiryDateBuilderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(
        int $invoiceId,
        string $ccNumber,
        int $ccType,
        string $expMonth,
        string $expYear,
        string $ccCode,
        bool $isReplaceOldCard,
        string $chargeOption,
        int $editorUserId,
        bool $isReadOnlyDb = false,
    ): Result {
        $dataProvider = $this->createDataProvider();
        $result = Result::new()->construct();

        $invoice = $dataProvider->loadInvoice($invoiceId, $isReadOnlyDb);
        if (!$invoice) {
            throw CouldNotFindInvoice::withId($invoiceId);
        }

        $user = $this->createDataProvider()->loadUser($invoice->BidderId, $isReadOnlyDb);
        if (!$user) {
            throw CouldNotFindUser::withId($invoice->BidderId);
        }


        if ($chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE) {
            $userBilling = $dataProvider->loadUserBillingOrCreate($invoice->BidderId, $isReadOnlyDb);
            if (
                $dataProvider->isAuthNetCim($invoice->AccountId)
                && $dataProvider->decryptValue($userBilling->AuthNetCpi) === ''
                && !$dataProvider->isCcInfoExists($userBilling)
            ) {
                return $result->addError(Result::ERR_NO_CC_INFO);
            }

            [$expMonth, $expYear] = $this->createCcExpiryDateBuilder()->explode($userBilling->CcExpDate);
            $ccType = (int)$userBilling->CcType;
            $ccNumber = $dataProvider->decryptValue($userBilling->CcNumber);
        }

        if (
            $chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC
            && $isReplaceOldCard
        ) {
            $this->createInvoiceUserCcInfoUpdater()->update(
                $invoice->AccountId,
                $editorUserId,
                $user->Id,
                trim($ccNumber),
                $ccType,
                $expYear,
                $expMonth
            );
        }

        $errorMessage = $this->updateCim(
            $invoice,
            $user,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $ccCode,
            $editorUserId
        );

        if ($errorMessage !== '') {
            return $result->addError(Result::ERR_CC_INFO_UPDATE, $errorMessage);
        }

        return $result->addSuccess(Result::OK_UPDATED);
    }

    protected function updateCim(
        Invoice $invoice,
        User $user,
        string $ccNumber,
        int $ccType,
        string $expMonth,
        string $expYear,
        string $ccCode,
        int $editorUserId
    ): string {
        $chargingHelper = $this->createAdminStackedTaxInvoicePaymentChargingHelper();
        $params = $chargingHelper->getParams(
            $invoice,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $ccCode
        );

        $errorMessage = $chargingHelper->updateCimInfo(
            $params,
            $invoice,
            $user,
            $editorUserId
        );

        return $errorMessage;
    }
}

