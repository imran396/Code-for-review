<?php
/**
 * SAM-9137: Auction-Inc Shipping Calculator feature adjustments for v3-5
 * SAM-1539: AuctionInc Shipping calculator integration (PBA)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 12, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Common\Shipping\AuctionInc\Feature;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManager;

/**
 * Class AuctionIncShippingFeatureAvailabilityChecker
 * @package Sam\Invoice\Common\Shipping\AuctionInc\Feature
 */
class AuctionIncShippingFeatureAvailabilityChecker extends CustomizableClass
{
    public const OP_AUC_INC_DHL = 'aucIncDhl';
    public const OP_AUC_INC_FEDEX = 'aucIncFedex';
    public const OP_AUC_INC_PICKUP = 'aucIncPickup';
    public const OP_AUC_INC_UPS = 'aucIncUps';
    public const OP_AUC_INC_USPS = 'aucIncUsps';

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check if Carrier Method/Service field assignment is available on user management.
     * It is considered as available in bounds of service account, when at least one carrier method is enabled.
     * @param int $serviceAccountId
     * @param array $optionals
     * @return bool
     */
    public function isAvailableForUserManagement(int $serviceAccountId, array $optionals = []): bool
    {
        $isAucIncPickup = $this->fetchOptionalAucIncPickup($serviceAccountId, $optionals);
        $isAucIncUps = $this->fetchOptionalAucIncUps($serviceAccountId, $optionals);
        $isAucIncDhl = $this->fetchOptionalAucIncDhl($serviceAccountId, $optionals);
        $isAucIncFedex = $this->fetchOptionalAucIncFedex($serviceAccountId, $optionals);
        $isAucIncUsps = $this->fetchOptionalAucIncUsps($serviceAccountId, $optionals);
        $isAvailable = $isAucIncPickup
            || $isAucIncUps
            || $isAucIncDhl
            || $isAucIncFedex
            || $isAucIncUsps;
        return $isAvailable;
    }

    protected function fetchOptionalAucIncPickup(int $serviceAccountId, array $optionals): bool
    {
        return $optionals[self::OP_AUC_INC_PICKUP]
            ?? (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_PICKUP, $serviceAccountId);
    }

    protected function fetchOptionalAucIncUps(int $serviceAccountId, array $optionals): bool
    {
        return $optionals[self::OP_AUC_INC_UPS]
            ?? (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_UPS, $serviceAccountId);
    }

    protected function fetchOptionalAucIncDhl(int $serviceAccountId, array $optionals): bool
    {
        return $optionals[self::OP_AUC_INC_DHL]
            ?? (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_DHL, $serviceAccountId);
    }

    protected function fetchOptionalAucIncFedex(int $serviceAccountId, array $optionals): bool
    {
        return $optionals[self::OP_AUC_INC_FEDEX]
            ?? (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_FEDEX, $serviceAccountId);
    }

    protected function fetchOptionalAucIncUsps(int $serviceAccountId, array $optionals): bool
    {
        return $optionals[self::OP_AUC_INC_USPS]
            ?? (bool)SettingsManager::new()->get(Constants\Setting::AUC_INC_USPS, $serviceAccountId);
    }
}
