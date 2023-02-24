<?php
/**
 * SAM-4498: User billing and cc info validator
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 18, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Validate;

use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserBilling\UserBillingReadRepositoryCreateTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserBilling;

/**
 * Class UserBillingChecker
 * @package Sam\User\Validate
 */
class UserBillingChecker extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use CreditCardValidatorAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingReadRepositoryCreateTrait;
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function hasCcExpired(int $userId): bool
    {
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userId);
        if ($userBilling->CcExpDate !== '') {
            $hasExpired = !$this->getCreditCardValidator()->validateExpiredDateFormatted($userBilling->CcExpDate);
        } else {
            // flag CC not expired if there is no expiry date entered
            $hasExpired = false;
        }
        return $hasExpired;
    }

    /**
     * Check whether a user has existing cc info based on settings of passed system account
     * @param int $userId
     * @param int $accountId
     * @return bool
     */
    public function hasCcInfo(int $userId, int $accountId): bool
    {
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userId);

        $authNetCpi = $userBilling->AuthNetCpi;
        $payTraceCustId = $userBilling->PayTraceCustId;
        $nmiVaultId = $userBilling->NmiVaultId;
        $opayoTokenId = $userBilling->OpayoTokenId;

        $sm = $this->getSettingsManager();
        $isAuthNetCim = (bool)$sm->get(Constants\Setting::AUTH_NET_CIM, $accountId);
        $isPayTraceCim = (bool)$sm->get(Constants\Setting::PAY_TRACE_CIM, $accountId);
        $isNmiVault = (bool)$sm->get(Constants\Setting::NMI_VAULT, $accountId);
        $isOpayoToken = (bool)$sm->get(Constants\Setting::OPAYO_TOKEN, $accountId);
        $isEwayEnabled = (bool)$sm->get(Constants\Setting::CC_PAYMENT_EWAY, $accountId);
        $isEwayEncryptionKey = $sm->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $accountId);
        $isCim = $isAuthNetCim || $isPayTraceCim || $isNmiVault || $isOpayoToken;

        $row = $this->createUserBillingReadRepository()
            ->select(
                [
                    'cc_number',
                    'cc_exp_date',
                    'cc_type'
                ]
            )
            ->filterUserId($userId)
            ->loadRow();
        if (!$row) {
            return false;
        }

        $isValidFormatCcExpDate = $this->getCreditCardValidator()->validateFormatOfExpiredDate((string)$row['cc_exp_date']);

        $ccNumber = (string)$row['cc_number'] !== ''
            ? $this->createBlockCipherProvider()->construct()->decrypt($row['cc_number'])
            : '';
        if (
            !$isValidFormatCcExpDate
            && (string)$row['cc_number'] === ''
        ) {
            return false;
        }

        if (
            !$isCim
            && !(
                $isEwayEnabled
                && $isEwayEncryptionKey !== ''
            )
            && strlen($ccNumber) === 4
        ) {
            return false;
        }

        if ($isCim) { // CIM Enabled
            if ($isAuthNetCim && $authNetCpi === '') {
                return false;
            }

            if ($isPayTraceCim && $payTraceCustId === '') {
                return false;
            }

            if ($isNmiVault && $nmiVaultId === '') {
                return false;
            }

            if ($isOpayoToken && $opayoTokenId === '') {
                return false;
            }
        } elseif (
            !$isValidFormatCcExpDate
            || !$row['cc_number']
            || !$row['cc_type']
        ) {
            return false;
        }
        return true;
    }

    /**
     * Check if necessary data for credit card transaction is ready for use.
     * Credit card and necessary for transaction billing info is entered and CC is not expired.
     *
     * @param int $userId
     * @param int $accountId
     * @return bool
     */
    public function hasReadyCcTransactionInfo(int $userId, int $accountId): bool
    {
        $hasCcInfo = $this->hasCcInfo($userId, $accountId);
        $isCcExpired = $this->hasCcExpired($userId);
        $hasTransactionBillingInfo = $this->hasRequiredTransactionBillingInfo($userId);
        $hasReadyCcTransInfo = $hasCcInfo
            && !$isCcExpired
            && $hasTransactionBillingInfo;
        return $hasReadyCcTransInfo;
    }

    /**
     * Check if all required fields for billing info are set for user
     *
     * @param int $userId
     * @return bool
     */
    public function hasRequiredTransactionBillingInfo(int $userId): bool
    {
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userId);
        $has = $userBilling->FirstName
            && $userBilling->LastName
            && $userBilling->Address
            && $userBilling->City
            && $userBilling->State
            && $userBilling->Zip
            && $userBilling->Country;
        return $has;
    }

    /**
     * @param UserBilling $userBilling
     * @return bool
     */
    public function isCcInfoExists(UserBilling $userBilling): bool
    {
        $exist = $userBilling->CcExpDate !== ''
            && $this->createBlockCipherProvider()->construct()->decrypt($userBilling->CcNumber) !== '';
        return $exist;
    }
}
