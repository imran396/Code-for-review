<?php
/**
 * Stacked Tax. Admin - Add to Stacked Tax Payment page (Invoice) the functionality from Pay Invoice page
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 17, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Update;

use Invoice;
use Sam\Core\Constants\Admin\InvoiceItemFormConstants;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Common\AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Update\Internal\Load\DataProviderCreateTrait;
use Sam\Invoice\StackedTax\Charge\Admin\PaymentEdit\Gate\Opayo\Internal\Update\AdminInvoiceEditOpayoCcInfoUpdaterResult as Result;
use Sam\Invoice\Common\Charge\Common\CcInfo\Update\InvoiceUserCcInfoUpdaterCreateTrait;
use Sam\Invoice\Common\Load\Exception\CouldNotFindInvoice;
use Sam\User\Load\Exception\CouldNotFindUser;
use User;

class AdminInvoiceEditOpayoCcInfoUpdater extends CustomizableClass
{
    use DataProviderCreateTrait;
    use AdminStackedTaxInvoicePaymentChargingHelperCreateTrait;
    use InvoiceUserCcInfoUpdaterCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function update(
        int $userId,
        int $invoiceId,
        string $ccNumber,
        int $ccType,
        string $expMonth,
        string $expYear,
        string $ccCode,
        bool $isReplaceOldCard,
        string $chargeOption,
        int $editorUserId,
        bool $hasOpayoCustomerProfile,
        bool $isOpayoToken,
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

        if ($chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC) {
            log_debug(composeLogData(['Other CC Number' => substr($ccNumber, -4)]));
            if (!$isReplaceOldCard) {
                return $result->addSuccess(Result::OK_UPDATED);
            }

            $this->createInvoiceUserCcInfoUpdater()->update(
                $invoice->AccountId,
                $editorUserId,
                $userId,
                $ccNumber,
                $ccType,
                $expYear,
                $expMonth,
                '',
                $isReadOnlyDb
            );
        }

        if (
            $isOpayoToken
            && (
                $chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_OTHER_CC
                || (
                    $chargeOption === InvoiceItemFormConstants::CHARGE_OPTION_CC_ON_PROFILE
                    && !$hasOpayoCustomerProfile
                )
            )
        ) {
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
        $dataProvider = $this->createDataProvider();
        $params = $dataProvider->getParams(
            $invoice,
            $ccNumber,
            $ccType,
            $expMonth,
            $expYear,
            $ccCode
        );

        $errorMessage = $this->createAdminStackedTaxInvoicePaymentChargingHelper()->updateCimInfo(
            $params,
            $invoice,
            $user,
            $editorUserId
        );

        return $errorMessage;
    }
}
