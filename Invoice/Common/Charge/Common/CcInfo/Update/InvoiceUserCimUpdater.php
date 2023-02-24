<?php
/**
 * SAM-10967: Stacked Tax. Public My Invoice pages. Extract Opayo invoice charging.
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Oleh Kovalov
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 20, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Common\CcInfo\Update;

use Invoice;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use User;
use User_CIM;

class InvoiceUserCimUpdater extends CustomizableClass
{
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use ConfigRepositoryAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    // TODO Remove updateCimInfo from AdminInvoiceEditCharging helper and use this method everywhere(public/admin)
    public function update(
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
}
