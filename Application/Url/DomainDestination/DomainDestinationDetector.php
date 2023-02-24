<?php
/**
 * SAM-9355: Refactor Domain Detector and Domain Redirector for unit testing
 * SAM-3521: Portal auction destination options (Propstore)
 * SAM-5139: Domain Detector class
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           5/25/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Application\Url\DomainDestination;

use Account;
use Sam\Account\Validate\MultipleTenantAccountSimpleChecker;
use Sam\Application\Url\Domain\AccountDomainDetectorCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;

/**
 * Class UrlDomainDetector
 * @package Sam\Application\Url
 */
class DomainDestinationDetector extends CustomizableClass
{
    use AccountDomainDetectorCreateTrait;
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
     * Return domain according to SAM-3521 requirements.
     * It depends on AuctionParameters->AuctionDomainMode, AuctionParameters->ForceMainAccountDomainMode
     * and core->portal->urlHandling
     * @param Account $account
     * @return string
     */
    public function detect(Account $account): string
    {
        $sm = $this->getSettingsManager();
        $isTargetEntityFromPortal = $this->isPortalAccount($account->Id);
        $mainAccountAuctionDomainMode = $sm->getForMain(Constants\Setting::AUCTION_DOMAIN_MODE);

        if ($isTargetEntityFromPortal) {
            $isForceMainAccountDomainMode = $sm->getForMain(Constants\Setting::FORCE_MAIN_ACCOUNT_DOMAIN_MODE);
            if ($isForceMainAccountDomainMode) {
                // We should use main account's settings
                return $this->detectByDomainMode($mainAccountAuctionDomainMode, $account);
            }

            if ($mainAccountAuctionDomainMode === Constants\AuctionDomainMode::ALWAYS_SUB_DOMAIN) {
                // We should use ALWAYS_SUB_DOMAIN option to prevent infinite loop,
                // when portal account's value is ALWAYS_MAIN_DOMAIN, but we skip checking this condition for optimization.
                return $this->detectByDomainMode($mainAccountAuctionDomainMode, $account);
            }

            // Use portal account's settings
            $portalAccountAuctionDomainMode = $sm->get(Constants\Setting::AUCTION_DOMAIN_MODE, $account->Id);
            return $this->detectByDomainMode($portalAccountAuctionDomainMode, $account);
        }

        return $this->detectByDomainMode($mainAccountAuctionDomainMode, $account);
    }

    /**
     * @param string $auctionDomainMode
     * @param Account $account
     * @return string
     */
    protected function detectByDomainMode(string $auctionDomainMode, Account $account): string
    {
        if ($auctionDomainMode === Constants\AuctionDomainMode::ALWAYS_MAIN_DOMAIN) {
            return $this->cfg()->get('core->app->httpHost');
        }

        if ($auctionDomainMode === Constants\AuctionDomainMode::ALWAYS_SUB_DOMAIN) {
            return $this->createAccountDomainDetector()->detectByAccount($account);
        }

        return '';
    }

    /**
     * Check, if current system account is portal account in multiple tenant installation.
     * @param int $systemAccountId
     * @return bool
     */
    public function isPortalAccount(int $systemAccountId): bool
    {
        return MultipleTenantAccountSimpleChecker::new()->isPortalAccount(
            $systemAccountId,
            $this->cfg()->get('core->portal->enabled'),
            $this->cfg()->get('core->portal->mainAccountId')
        );
    }
}
