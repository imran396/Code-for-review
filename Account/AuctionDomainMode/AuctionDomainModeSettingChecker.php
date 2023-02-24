<?php
/**
 * Helper class for account.auction_domain_mode field
 *
 * SAM-3521: Portal auction destination options (Propstore)
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis, Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $Id$
 * @since           Jul 29, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\AuctionDomainMode;

use Account;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\Core\Constants;

/**
 * Class SettingChecker
 * @package Sam\Account\AuctionDomainMode
 */
class AuctionDomainModeSettingChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
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
     * Check if "Account domain mode" setting is available for passed account
     * @param Account $account
     * @return bool
     */
    public function isAvailableSettingAccountDomainMode(Account $account): bool
    {
        $isAvailable = $this->cfg()->get('core->portal->enabled')
            && (
                $account->Id === $this->cfg()->get('core->portal->mainAccountId')
                || !$this->isEnabledForceMainAccountDomainMode()
            );
        return $isAvailable;
    }

    /**
     * Check, if "Always the main domain" option is available.
     *
     * Option is available in case of main account and in case of sub account,
     * if "Force main account domain mode setting" is disabled
     * and if "Always the auction account (sub) domain" of main account is not selected.
     * @param Account $account
     * @return bool
     */
    public function isAvailableOptionAlwaysMainDomain(Account $account): bool
    {
        $sm = $this->getSettingsManager();
        $forceMainAccountDomainMode = $sm->getForMain(Constants\Setting::FORCE_MAIN_ACCOUNT_DOMAIN_MODE);
        $auctionDomainMode = $sm->getForMain(Constants\Setting::AUCTION_DOMAIN_MODE);
        $isMain = $account->Id === $this->cfg()->get('core->portal->mainAccountId');
        $isAvailable = $isMain
            || (
                !$forceMainAccountDomainMode
                && $auctionDomainMode !== Constants\AuctionDomainMode::ALWAYS_SUB_DOMAIN
            );
        return $isAvailable;
    }

    /**
     * Check if "Force main account auction domain mode setting" is enabled in main account
     * @return bool
     */
    public function isEnabledForceMainAccountDomainMode(): bool
    {
        $isEnabled = $this->getSettingsManager()->getForMain(Constants\Setting::FORCE_MAIN_ACCOUNT_DOMAIN_MODE);
        return $isEnabled;
    }
}
