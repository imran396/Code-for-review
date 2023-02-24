<?php
/**
 * Payment gateway factory
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           July 7, 2020
 */

namespace Sam\PaymentGateway;

use Payment_AuthorizeNet;
use Payment_Eway;
use Payment_NmiDirectPost;
use Payment_PayTrace;
use Payment_Opayo;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class PaymentGatewayFactory
 * @package Sam\PaymentGateway
 */
class PaymentGatewayFactory extends CustomizableClass
{
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
     * @param int $accountId
     * @return PaymentGatewayInterface|null
     */
    public function getActivePaymentGateway(int $accountId): ?PaymentGatewayInterface
    {
        $sm = $this->getSettingsManager();
        $authorizationUse = $sm->get(Constants\Setting::AUTHORIZATION_USE, $accountId);
        $isAuthNetCim = (bool)$sm->get(Constants\Setting::AUTH_NET_CIM, $accountId);
        $isPayTraceCim = (bool)$sm->get(Constants\Setting::PAY_TRACE_CIM, $accountId);
        $isCim = $isAuthNetCim || $isPayTraceCim;

        if ($authorizationUse === Constants\SettingUser::PAY_AUTHORIZE_NET || $isAuthNetCim) {
            return new Payment_AuthorizeNet($accountId);
        }
        if ($authorizationUse === Constants\SettingUser::PAY_PAY_TRACE || $isPayTraceCim) {
            return new Payment_PayTrace($accountId);
        }
        if ($authorizationUse === Constants\SettingUser::PAY_NMI) {
            return new Payment_NmiDirectPost($accountId);
        }
        if ($authorizationUse === Constants\SettingUser::PAY_EWAY) {
            return Payment_Eway::new()->init($accountId);
        }
        if ($authorizationUse === Constants\SettingUser::PAY_OPAYO) {
            return Payment_Opayo::new()->init($accountId);
        }
        return null;
    }
}
