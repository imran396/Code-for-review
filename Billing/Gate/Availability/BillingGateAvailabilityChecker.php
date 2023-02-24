<?php
/**
 * SAM-8787: Credit card payment gateway availability checker
 * SAM-9388: ACH Payment option for different payment gateways
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 24, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Billing\Gate\Availability;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class BillingGateAvailabilityChecker
 * @package Sam\Billing\Gate\Availability
 */
class BillingGateAvailabilityChecker extends CustomizableClass
{
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check, if Authorize.Net billing gateway is enabled for payment transactions
     * @param int $accountId
     * @return bool
     */
    public function isAuthorizeNetPaymentEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT, $accountId);
    }

    /**
     * Check, if PayTrace billing gateway is enabled for payment transactions
     * @param int $accountId
     * @return bool
     */
    public function isPayTracePaymentEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_PAY_TRACE, $accountId);
    }

    /**
     * Check, if EWay billing gateway is enabled for payment transactions
     * @param int $accountId
     * @return bool
     */
    public function isEwayPaymentEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_EWAY, $accountId);
    }

    /**
     * Check, if NMI billing gateway is enabled for payment transactions
     * @param int $accountId
     * @return bool
     */
    public function isNmiPaymentEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_NMI, $accountId);
    }

    /**
     * Check, if Opayo billing gateway is enabled for payment transactions
     * @param int $accountId
     * @return bool
     */
    public function isOpayoPaymentEnabled(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::CC_PAYMENT_OPAYO, $accountId);
    }

    /**
     * Check if any of gateways for Credit Card processing is available and enabled.
     * @param int $accountId
     * @return bool
     */
    public function isCcProcessingAvailable(int $accountId): bool
    {
        $isAvailable = $this->isAuthorizeNetPaymentEnabled($accountId)
            || $this->isPayTracePaymentEnabled($accountId)
            || $this->isEwayPaymentEnabled($accountId)
            || $this->isNmiPaymentEnabled($accountId)
            || $this->isOpayoPaymentEnabled($accountId);
        return $isAvailable;
    }

    /**
     * Check if CC tokenization is enabled for any payment gateway that has this option.
     * @param int $accountId
     * @return bool
     */
    public function isCcTokenizationEnabled(int $accountId): bool
    {
        $sm = $this->getSettingsManager();
        $isEnabled = $sm->get(Constants\Setting::AUTH_NET_CIM, $accountId)
            || $sm->get(Constants\Setting::NMI_VAULT, $accountId)
            || $sm->get(Constants\Setting::PAY_TRACE_CIM, $accountId)
            || $sm->get(Constants\Setting::OPAYO_TOKEN, $accountId);
        return $isEnabled;
    }

    public function isAuthNetCim(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::AUTH_NET_CIM, $accountId);
    }

    public function isNmiVault(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::NMI_VAULT, $accountId);
    }

    public function isPayTraceCim(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::PAY_TRACE_CIM, $accountId);
    }

    public function isOpayoToken(int $accountId): bool
    {
        return (bool)$this->getSettingsManager()->get(Constants\Setting::OPAYO_TOKEN, $accountId);
    }

    /**
     * Check if ACH option is enabled for any payment gateway that has this option.
     * @param int $accountId
     * @return bool
     */
    public function isAchPaymentEnabled(int $accountId): bool
    {
        $sm = $this->getSettingsManager();
        $isAchInAuthorizeNet = (bool)$sm->get(Constants\Setting::ACH_PAYMENT, $accountId);
        $isAchInNmi = (bool)$sm->get(Constants\Setting::ACH_PAYMENT_NMI, $accountId);
        $isAchInPayTrace = (bool)$sm->get(Constants\Setting::ACH_PAYMENT_PAY_TRACE, $accountId);
        $isAchInOpayo = (bool)$sm->get(Constants\Setting::ACH_PAYMENT_OPAYO, $accountId);
        $isEnabled = $isAchInAuthorizeNet
            || $isAchInNmi
            || $isAchInPayTrace
            || $isAchInOpayo;
        $logData = [
            'acc' => $accountId,
            'ACH in AuthorizeNet' => $isAchInAuthorizeNet,
            'ACH in NMI' => $isAchInNmi,
            'ACH in PayTrace' => $isAchInPayTrace,
            'ACH in Opayo' => $isAchInOpayo,
        ];
        log_trace('ACH Payment ' . ($isEnabled ? 'enabled' : 'disabled') . composeSuffix($logData));
        return $isEnabled;
    }
}
