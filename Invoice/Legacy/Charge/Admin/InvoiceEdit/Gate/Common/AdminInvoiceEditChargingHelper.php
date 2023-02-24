<?php
/**
 * SAM-10912: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract Eway invoice charging
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common;

use Invoice;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Invoice\Common\Bidder\Load\InvoiceUserLoaderAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use Sam\User\Render\UserRendererAwareTrait;
use User;
use User_CIM;

/**
 * Class InvoiceEditChargingHelper
 * @package Sam\Invoice\Legacy\Charge\Admin\InvoiceEdit\Gate\Common
 */
class AdminInvoiceEditChargingHelper extends CustomizableClass
{
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use InvoiceUserLoaderAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserRendererAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }


    /**
     * @param Invoice $invoice
     * @param string $ccNumber
     * @param int $ccType
     * @param string $expMonth
     * @param string $expYear
     * @param string|null $ccCv2 null means that ccCv2 is not passed
     * @return array
     * TODO: Replace Invoice $invoice argument with int $invoiceId and int $invoiceAccountId
     */
    public function getParams(
        Invoice $invoice,
        string $ccNumber,
        int $ccType,
        string $expMonth,
        string $expYear,
        ?string $ccCv2 = null
    ): array {
        $params = [];
        $params[Constants\BillingParam::ACCOUNT_ID] = $invoice->AccountId;
        $invoiceUserShipping = $this->getInvoiceUserLoader()->loadInvoiceUserShippingOrCreate($invoice->Id);
        $params[Constants\BillingParam::SHIPPING_COMPANY_NAME] = $invoiceUserShipping->CompanyName;
        $params[Constants\BillingParam::SHIPPING_FIRST_NAME] = $invoiceUserShipping->FirstName;
        $params[Constants\BillingParam::SHIPPING_LAST_NAME] = $invoiceUserShipping->LastName;
        $params[Constants\BillingParam::SHIPPING_ADDRESS] = $invoiceUserShipping->Address;
        $params[Constants\BillingParam::SHIPPING_CITY] = $invoiceUserShipping->City;
        $params[Constants\BillingParam::SHIPPING_STATE] = $invoiceUserShipping->State;
        $params[Constants\BillingParam::SHIPPING_COUNTRY] = $invoiceUserShipping->Country;
        $params[Constants\BillingParam::SHIPPING_ZIP] = $invoiceUserShipping->Zip;
        $params[Constants\BillingParam::SHIPPING_PHONE] = $invoiceUserShipping->Phone;
        $params[Constants\BillingParam::CC_TYPE] = $ccType;
        $params[Constants\BillingParam::CC_NUMBER] = $ccNumber;
        $params[Constants\BillingParam::CC_NUMBER_HASH] = $ccNumber;
        $params[Constants\BillingParam::CC_EXP_MONTH] = $expMonth;
        $params[Constants\BillingParam::CC_EXP_YEAR] = $expYear;
        $params[Constants\BillingParam::CC_CODE] = $ccCv2;
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoice->Id);
        $params[Constants\BillingParam::BILLING_COMPANY_NAME] = $invoiceUserBilling->CompanyName;
        $params[Constants\BillingParam::BILLING_FIRST_NAME] = $invoiceUserBilling->FirstName;
        $params[Constants\BillingParam::BILLING_LAST_NAME] = $invoiceUserBilling->LastName;
        $params[Constants\BillingParam::BILLING_ADDRESS] = $invoiceUserBilling->Address;
        $params[Constants\BillingParam::BILLING_CITY] = $invoiceUserBilling->City;
        $params[Constants\BillingParam::BILLING_STATE] = $invoiceUserBilling->State;
        $params[Constants\BillingParam::BILLING_COUNTRY] = $invoiceUserBilling->Country;
        $params[Constants\BillingParam::BILLING_ZIP] = $invoiceUserBilling->Zip;
        $params[Constants\BillingParam::BILLING_PHONE] = $invoiceUserBilling->Phone;
        $params[Constants\BillingParam::BILLING_EMAIL] = $invoiceUserBilling->Email;
        return $params;
    }

    /**
     * Update Customer information on auth.net/paytrace/nmi/sagepay
     * @param array $params
     * @param Invoice $invoice
     * @param User $user
     * @param int $editorUserId
     * @return string
     */
    public function updateCimInfo(
        array $params,
        Invoice $invoice,
        User $user,
        int $editorUserId
    ): string {
        $error = '';
        $errors = [];

        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($user->Id);

        $authNet = $payTrace = $nmi = $opayo = [];
        $generalErrorCim = 'Error creating customer information';

        $billingChecker = $this->createBillingGateAvailabilityChecker();
        $isAuthNetCim = $billingChecker->isAuthNetCim($invoice->AccountId);
        $isPayTraceCim = $billingChecker->isPayTraceCim($invoice->AccountId);
        $isNmiVault = $billingChecker->isNmiVault($invoice->AccountId);
        $isOpayoToken = $billingChecker->isOpayoToken($invoice->AccountId);

        $blockCipher = $this->createBlockCipherProvider()->construct();
        if ($isAuthNetCim) {
            $params[Constants\BillingAuthNet::P_AUTH_NET_CPI] = $blockCipher->decrypt($userBilling->AuthNetCpi);
            $params[Constants\BillingAuthNet::P_AUTH_NET_CPPI] = $blockCipher->decrypt($userBilling->AuthNetCppi);
            $params[Constants\BillingAuthNet::P_AUTH_NET_CAI] = $blockCipher->decrypt($userBilling->AuthNetCai);
            $authNet = User_CIM::recordAuthNetCim($invoice->AccountId, $user, $params);
            if ($authNet['success']) {
                $result = $authNet['result'];
                if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPI])) {
                    $userBilling->AuthNetCpi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPI]);
                }
                if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI])) {
                    $userBilling->AuthNetCppi = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CPPI]);
                }
                if (isset($result[Constants\BillingAuthNet::P_AUTH_NET_CAI])) {
                    $userBilling->AuthNetCai = $blockCipher->encrypt($result[Constants\BillingAuthNet::P_AUTH_NET_CAI]);
                }
            }
        } elseif ($isPayTraceCim) {
            $params[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID] = $blockCipher->decrypt($userBilling->PayTraceCustId);
            // When recording customer for paytrace we use main account paytrace settings
            $payTrace = User_CIM::recordPayTraceCim($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
            if ($payTrace['success']) {
                $result = $payTrace['result'];
                if (isset($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID])) {
                    $userBilling->PayTraceCustId = $blockCipher->encrypt($result[Constants\BillingPayTrace::P_PAY_TRACE_CUST_ID]);
                }
            }
        }

        if ($isNmiVault) {
            $params[Constants\BillingNmi::P_NMI_VAULT_ID] = $blockCipher->decrypt($userBilling->NmiVaultId);
            $nmi = User_CIM::recordNmiVault($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
            if ($nmi['success']) {
                $result = $nmi['result'];
                if (isset($result[Constants\BillingNmi::P_NMI_VAULT_ID])) {
                    $userBilling->NmiVaultId = $blockCipher->encrypt($result[Constants\BillingNmi::P_NMI_VAULT_ID]);
                }
            }
        }

        if ($isOpayoToken) {
            $params[Constants\BillingOpayo::P_OPAYO_TOKEN_ID] = $blockCipher->decrypt($userBilling->OpayoTokenId);
            $opayo = User_CIM::recordOpayoToken($this->cfg()->get('core->portal->mainAccountId'), $user, $params);
            if ($opayo['success']) {
                $result = $opayo['result'];
                if (isset($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID])) {
                    $userBilling->OpayoTokenId = $blockCipher->encrypt($result[Constants\BillingOpayo::P_OPAYO_TOKEN_ID]);
                }
            }
        }

        if (isset($authNet['errorMessage']) && $authNet['errorMessage'] !== '') {
            $errors[] = $authNet['errorMessage'];
        }
        if (isset($payTrace['errorMessage']) && $payTrace['errorMessage'] !== '') {
            $errors[] = $payTrace['errorMessage'];
        }
        if (isset($nmi['errorMessage']) && $nmi['errorMessage'] !== '') {
            $errors[] = $nmi['errorMessage'];
        }
        if (isset($opayo['errorMessage']) && $opayo['errorMessage'] !== '') {
            $errors[] = $opayo['errorMessage'];
        }

        if (count($errors) === 0) { // Has no error save cim info
            $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
        } else {
            log_debug($errors);
            $error = $generalErrorCim . ' ' . implode('<br />', $errors);
        }
        return $error;// No Error
    }

    public function getAccountingEmail(int $userId, int $invoiceId): string
    {
        $user = $this->getUserLoader()->load($userId);
        $invoiceUserBilling = $this->getInvoiceUserLoader()->loadInvoiceUserBillingOrCreate($invoiceId);
        $email = $this->getUserRenderer()->renderAccountingEmail(
            $user,
            null,
            $invoiceUserBilling
        );
        return $email;
    }
}
