<?php
/**
 *
 *
 * @copyright       2016 SAM
 * @author          Boanerge Regidor
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           17 March, 2016
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 *
 */

namespace Sam\User;

use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserBilling;

/**
 * Class Billing
 * @package Sam\User
 */
class Billing extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use SettingsManagerAwareTrait;
    use UserBillingWriteRepositoryAwareTrait;
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
     * @param int $editorUserId
     * @param array $params
     * @return UserBilling
     */
    public static function update(int $userId, int $editorUserId, array $params): UserBilling
    {
        $instance = self::new();
        return $instance->_update($userId, $editorUserId, $params);
    }

    /**
     * @param int $userId
     * @param int $editorUserId
     * @param array $params
     * @return UserBilling
     */
    protected function _update(int $userId, int $editorUserId, array $params): UserBilling
    {
        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($userId, true);
        if (isset($params[Constants\BillingParam::BILLING_COMPANY_NAME])) {
            $userBilling->CompanyName = $params[Constants\BillingParam::BILLING_COMPANY_NAME];
        }
        if (isset($params[Constants\BillingParam::BILLING_FIRST_NAME])) {
            $userBilling->FirstName = trim($params[Constants\BillingParam::BILLING_FIRST_NAME]);
        }
        if (isset($params[Constants\BillingParam::BILLING_LAST_NAME])) {
            $userBilling->LastName = trim($params[Constants\BillingParam::BILLING_LAST_NAME]);
        }
        if (isset($params[Constants\BillingParam::BILLING_PHONE])) {
            $userBilling->Phone = trim($params[Constants\BillingParam::BILLING_PHONE]);
        }
        if (isset($params[Constants\BillingParam::BILLING_FAX])) {
            $userBilling->Fax = trim($params[Constants\BillingParam::BILLING_FAX]);
        }
        if (isset($params[Constants\BillingParam::BILLING_COUNTRY])) {
            $userBilling->Country = trim($params[Constants\BillingParam::BILLING_COUNTRY]);
        }
        if (isset($params[Constants\BillingParam::BILLING_ADDRESS])) {
            $userBilling->Address = trim($params[Constants\BillingParam::BILLING_ADDRESS]);
        }
        if (isset($params[Constants\BillingParam::BILLING_ADDRESS_2])) {
            $userBilling->Address2 = trim($params[Constants\BillingParam::BILLING_ADDRESS_2]);
        }
        if (isset($params[Constants\BillingParam::BILLING_ADDRESS_3])) {
            $userBilling->Address3 = trim($params[Constants\BillingParam::BILLING_ADDRESS_3]);
        }
        if (isset($params[Constants\BillingParam::BILLING_CITY])) {
            $userBilling->City = trim($params[Constants\BillingParam::BILLING_CITY]);
        }
        if (isset($params[Constants\BillingParam::BILLING_STATE])) {
            $userBilling->State = trim($params[Constants\BillingParam::BILLING_STATE]);
        }
        if (isset($params[Constants\BillingParam::BILLING_ZIP])) {
            $userBilling->Zip = trim($params[Constants\BillingParam::BILLING_ZIP]);
        }
        if (isset($params[Constants\BillingParam::BILLING_EMAIL])) {
            $userBilling->Email = trim($params[Constants\BillingParam::BILLING_EMAIL]);
        }
        if (isset($params[Constants\BillingParam::CC_NUMBER])) {
            $accountId = (int)$params[Constants\BillingParam::ACCOUNT_ID];
            $isOpayoToken = (bool)$this->getSettingsManager()->get(Constants\Setting::OPAYO_TOKEN, $accountId);
            if ($isOpayoToken) {
                $userBilling->CcNumber = $this->getCcNumberEncrypter()
                    ->encryptLastFourDigits($params[Constants\BillingParam::CC_NUMBER]);
            } elseif (
                $this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_EWAY, $accountId)
                && (string)$this->getSettingsManager()->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $accountId) !== ''
            ) {
                $userBilling->CcNumber = $this->getCcNumberEncrypter()
                    ->encryptLastFourDigits($params[Constants\BillingParam::CC_NUMBER]);
            } else {
                $userBilling->CcNumber = $this->createBlockCipherProvider()
                    ->construct()
                    ->encrypt($params[Constants\BillingParam::CC_NUMBER]);
            }
        }

        if (isset($params[Constants\BillingParam::CC_NUMBER_HASH])) {
            $userBilling->CcNumberHash = trim($params[Constants\BillingParam::CC_NUMBER_HASH]);
        }

        if (isset($params[Constants\BillingEway::P_CC_NUMBER_EWAY])) {
            $userBilling->CcNumberEway = trim($params[Constants\BillingEway::P_CC_NUMBER_EWAY]);
        }

        if (isset($params[Constants\BillingParam::CC_EXP_MONTH]) && isset($params[Constants\BillingParam::CC_EXP_YEAR])) {
            $userBilling->CcExpDate = $this->createCcExpiryDateBuilder()
                ->implode($params[Constants\BillingParam::CC_EXP_MONTH], $params[Constants\BillingParam::CC_EXP_YEAR]); // MM-YYYY
        }
        $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
        return $userBilling;
    }

}
