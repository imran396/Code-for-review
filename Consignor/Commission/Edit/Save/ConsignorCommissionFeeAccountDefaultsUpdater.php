<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 05, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Consignor\Commission\Edit\Save;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\Save\SettingsProducerCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class ConsignorCommissionFeeAccountDefaultsUpdater
 * @package Sam\Consignor\Commission\Edit\Save
 */
class ConsignorCommissionFeeAccountDefaultsUpdater extends CustomizableClass
{
    use SettingsManagerAwareTrait;
    use SettingsProducerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Set account-level default commission and sold/unsold fee
     *
     * @param int $consignorCommissionFeeId
     * @param int $accountId
     * @param bool $defaultCommission
     * @param bool $defaultSoldFee
     * @param bool $defaultUnsoldFee
     * @param int $editorUserId
     * @return array
     */
    public function update(
        int $consignorCommissionFeeId,
        int $accountId,
        bool $defaultCommission,
        bool $defaultSoldFee,
        bool $defaultUnsoldFee,
        int $editorUserId
    ): array {
        $settingManager = $this->getSettingsManager();
        $settings = [];

        if ($defaultCommission) {
            $settings[Constants\Setting::CONSIGNOR_COMMISSION_ID] = $consignorCommissionFeeId;
        } elseif ($settingManager->get(Constants\Setting::CONSIGNOR_COMMISSION_ID, $accountId) === $consignorCommissionFeeId) {
            $settings[Constants\Setting::CONSIGNOR_COMMISSION_ID] = null;
        }

        if ($defaultSoldFee) {
            $settings[Constants\Setting::CONSIGNOR_SOLD_FEE_ID] = $consignorCommissionFeeId;
        } elseif ($settingManager->get(Constants\Setting::CONSIGNOR_SOLD_FEE_ID, $accountId) === $consignorCommissionFeeId) {
            $settings[Constants\Setting::CONSIGNOR_SOLD_FEE_ID] = null;
        }

        if ($defaultUnsoldFee) {
            $settings[Constants\Setting::CONSIGNOR_UNSOLD_FEE_ID] = $consignorCommissionFeeId;
        } elseif ($settingManager->get(Constants\Setting::CONSIGNOR_UNSOLD_FEE_ID, $accountId) === $consignorCommissionFeeId) {
            $settings[Constants\Setting::CONSIGNOR_UNSOLD_FEE_ID] = null;
        }

        if ($settings) {
            $this->createSettingsProducer()->update($settings, $accountId, $editorUserId);
        }
        return $settings;
    }
}
