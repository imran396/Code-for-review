<?php
/**
 * SAM-10904: Stacked Tax. Invoice Management pages. Prepare the Invoice Edit page. Extract User CC Info updating after charge operation
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 30, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Charge\Common\CcInfo\Update;

use Sam\Billing\CreditCard\Build\CcExpiryDateBuilderCreateTrait;
use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Billing\Gate\Availability\BillingGateAvailabilityCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserBilling\UserBillingWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class InvoiceUserCcInfoUpdater
 * @package Sam\Invoice\Common\Charge\Common\CcInfo\Update
 */
class InvoiceUserCcInfoUpdater extends CustomizableClass
{
    use BillingGateAvailabilityCheckerCreateTrait;
    use BlockCipherProviderCreateTrait;
    use CcExpiryDateBuilderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use SettingsManagerAwareTrait;
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

    /**
     * @param int $invoiceAccountId
     * @param int $editorUserId
     * @param int $targetUserId
     * @param string $ccNumber
     * @param int $ccType
     * @param string $expYear
     * @param string $expMonth
     * @param string $ewayCardnumber
     * @param bool $isReadOnlyDb
     * @return void
     */
    public function update(
        int $invoiceAccountId,
        int $editorUserId,
        int $targetUserId,
        string $ccNumber,
        int $ccType,
        string $expYear,
        string $expMonth,
        string $ewayCardnumber = '',
        bool $isReadOnlyDb = false
    ): void {
        $expDate = $this->createCcExpiryDateBuilder()->implode($expMonth, $expYear);
        $isCcTokenization = $this->createBillingGateAvailabilityChecker()->isCcTokenizationEnabled($invoiceAccountId);

        $userBilling = $this->getUserLoader()->loadUserBillingOrCreate($targetUserId, $isReadOnlyDb);
        if ($isCcTokenization) {
            $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
        } else {
            $userBilling->CcNumber = $this->createBlockCipherProvider()->construct()->encrypt($ccNumber);
        }

        $sm = $this->getSettingsManager();

        if ($sm->get(Constants\Setting::NMI_VAULT, $invoiceAccountId)) {
            $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
        } else {
            $userBilling->CcNumber = $this->createBlockCipherProvider()->construct()->encrypt($ccNumber);
        }
        $userBilling->CcNumberHash = $this->getCcNumberEncrypter()->createHash($ccNumber);
        $userBilling->CcExpDate = $expDate;
        $userBilling->CcType = $ccType;

        $isCcPaymentEway = (bool)$sm->get(Constants\Setting::CC_PAYMENT_EWAY, $invoiceAccountId);
        if ($isCcPaymentEway) {
            $ewayEncryptionKey = $sm->get(Constants\Setting::EWAY_ENCRYPTION_KEY, $invoiceAccountId);
            $ewayEncryptionKey = $this->createBlockCipherProvider()->construct()->decrypt($ewayEncryptionKey);
            if ($ewayEncryptionKey !== '') {
                $userBilling->CcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($ccNumber);
                $userBilling->CcNumberEway = $ewayCardnumber;
            }
        }

        $this->getUserBillingWriteRepository()->saveWithModifier($userBilling, $editorUserId);
    }

}
