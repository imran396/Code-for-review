<?php
/**
 * SAM-1637: Portal - new account default settings
 * SAM-10551: Adjust SettingsManager for reading and caching data from different tables
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 26, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settings\Save\Internal;

use InvalidArgumentException;
use RuntimeException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use SettingShippingAuctionInc;

/**
 * Class SettingDefaultValueApplier
 * @package Sam\Settings\Save\Internal
 */
class SettingDefaultValueApplier extends CustomizableClass
{
    protected const DEFAULT_VALUES = [
        Constants\Setting::ACH_PAYMENT => false,
        Constants\Setting::AUC_INC_ACCOUNT_ID => '',
        Constants\Setting::AUC_INC_BUSINESS_ID => '',
        Constants\Setting::AUC_INC_DHL => false,
        Constants\Setting::AUC_INC_DHL_ACCESS_KEY => null,
        Constants\Setting::AUC_INC_DHL_POSTAL_CODE => null,
        Constants\Setting::AUC_INC_DIMENSION_TYPE => SettingShippingAuctionInc::AUC_INC_DIMENSION_TYPE_DEFAULT,
        Constants\Setting::AUC_INC_FEDEX => false,
        Constants\Setting::AUC_INC_HEIGHT_CUST_FIELD_ID => null,
        Constants\Setting::AUC_INC_LENGTH_CUST_FIELD_ID => null,
        Constants\Setting::AUC_INC_METHOD => '',
        Constants\Setting::AUC_INC_PICKUP => false,
        Constants\Setting::AUC_INC_UPS => false,
        Constants\Setting::AUC_INC_USPS => false,
        Constants\Setting::AUC_INC_WEIGHT_CUST_FIELD_ID => null,
        Constants\Setting::AUC_INC_WEIGHT_TYPE => SettingShippingAuctionInc::AUC_INC_WEIGHT_TYPE_DEFAULT,
        Constants\Setting::AUC_INC_WIDTH_CUST_FIELD_ID => null,
        Constants\Setting::AUCTION_LINKS_TO => Constants\Url::P_AUCTIONS_INFO,
        Constants\Setting::AUTH_NET_ACCOUNT_TYPE => Constants\Billing::PAY_ACC_TYPE_DEVELOPER,
        Constants\Setting::AUTH_NET_CIM => false,
        Constants\Setting::AUTH_NET_LOGIN => '',
        Constants\Setting::AUTH_NET_MODE => '',
        Constants\Setting::AUTH_NET_TRANKEY => '',
        Constants\Setting::AUTHORIZATION_USE => Constants\SettingUser::PAY_NO_AUTHORIZATION,
        Constants\Setting::CC_PAYMENT => false,
        Constants\Setting::ENABLE_PAYPAL_PAYMENTS => false,
        Constants\Setting::ENABLE_SMART_PAYMENTS => 0,
        Constants\Setting::PAYPAL_ACCOUNT_TYPE => Constants\Billing::PAY_ACC_TYPE_MERCHANT,
        Constants\Setting::PAYPAL_EMAIL => '',
        Constants\Setting::PAYPAL_IDENTITY_TOKEN => '',
        Constants\Setting::SMART_PAY_ACCOUNT_TYPE => Constants\Billing::PAY_ACC_TYPE_DEVELOPER,
        Constants\Setting::SMART_PAY_MERCHANT_ACCOUNT => '',
        Constants\Setting::SMART_PAY_MERCHANT_MODE => Constants\BillingSmartPay::MM_AUTOMATIC,
        Constants\Setting::SMART_PAY_MODE => '',
        Constants\Setting::SMART_PAY_SHARED_SECRET => '',
        Constants\Setting::SMART_PAY_SKIN_CODE => '',
        Constants\Setting::SMTP_AUTH => false,
        Constants\Setting::SMTP_PASSWORD => '',
        Constants\Setting::SMTP_PORT => 25,
        Constants\Setting::SMTP_SERVER => 'localhost',
        Constants\Setting::SMTP_USERNAME => '',
        Constants\Setting::VIEW_LANGUAGE => null,
    ];

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function apply(array $settingObjects): array
    {
        foreach (self::DEFAULT_VALUES as $setting => $value) {
            $settingEntityClassName = $this->getSettingEntityClassName($setting);
            $settingObject = $settingObjects[$settingEntityClassName];
            if (!$settingObject) {
                throw new RuntimeException("Can't apply default value for '{$setting}', setting object is absent.");
            }
            $property = Constants\Setting::$typeMap[$setting]['property'];
            $settingObject->{$property} = $value;
        }
        return $settingObjects;
    }

    protected function getSettingEntityClassName(string $setting): string
    {
        $entityClassName = Constants\Setting::$typeMap[$setting]['entity'] ?? null;
        if (!$entityClassName) {
            throw new InvalidArgumentException("Unknown setting '{$setting}'");
        }
        return $entityClassName;
    }
}
