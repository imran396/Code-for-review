<?php
/**
 * SAM-5716: Extract auction bidder validation and building logic from "Add New Bidder" form
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AddNewBidderForm\Common;

use Sam\Billing\CreditCard\Build\CcNumberEncrypterAwareTrait;
use Sam\Billing\CreditCard\Validate\CreditCardValidatorAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class AddNewBidderCreditCardHelper
 * @package Sam\View\Admin\Form\AddNewBidderForm
 */
class AddNewBidderCreditCardHelper extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;
    use CcNumberEncrypterAwareTrait;
    use CreditCardValidatorAwareTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $number
     * @param int $accountId
     * @return string
     */
    public function makeNumber(string $number, int $accountId): string
    {
        if ($this->isLast4DigitsCCNumberNeeded($accountId)) {
            $encryptedCcNumber = $this->getCcNumberEncrypter()->encryptLastFourDigits($number);
            return $encryptedCcNumber;
        }
        return $this->createBlockCipherProvider()->construct()->encrypt($number);
    }

    /**
     * @param string $number
     * @return int|null
     */
    public function detectType(string $number): ?int
    {
        return $this->getCreditCardValidator()->detectType($number);
    }

    /**
     * @param string $inputExpDate
     * @return string
     */
    public function formatExpDate(string $inputExpDate): string
    {
        return substr($inputExpDate, 0, 2) . '-20' . substr($inputExpDate, 2, 2);
    }

    /**
     * @param int $accountId
     * @return bool
     */
    private function isLast4DigitsCCNumberNeeded(int $accountId): bool
    {
        $sm = $this->getSettingsManager();
        return
            $sm->get(Constants\Setting::AUTH_NET_CIM, $accountId)
            || $sm->get(Constants\Setting::PAY_TRACE_CIM, $accountId)
            || $sm->get(Constants\Setting::NMI_VAULT, $accountId)
            || $sm->get(Constants\Setting::OPAYO_TOKEN, $accountId);
    }
}
