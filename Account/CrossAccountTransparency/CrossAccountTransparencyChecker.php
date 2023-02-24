<?php
/**
 * Higher layer service for detecting availability of cross-account transparency condition,
 * when other account entities are accessible from another owner's account.
 *
 * SAM-6068: Issue related to "Show content from all account" on a portal account
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\CrossAccountTransparency;

use Account;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Core\Account\Transparency\CrossAccountTransparencyPureChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class CrossDomainTransparencyChecker
 * @package Sam\Account\CrossDomainTransparency
 */
class CrossAccountTransparencyChecker extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isAvailableByAccountId(int $accountId, bool $isReadOnlyDb = false): bool
    {
        $account = $this->getAccountLoader()->load($accountId, $isReadOnlyDb);
        if (!$account) {
            log_error('Available account not found, when detecting cross-domain transparency' . composeSuffix(['acc' => $accountId]));
            return false;
        }

        return $this->isAvailableByAccount($account);
    }

    public function isAvailableByAccount(Account $account): bool
    {
        return $this->isAvailable($account->Id, $account->ShowAll);
    }

    public function isAvailable(int $accountId, bool $isShowAll): bool
    {
        return CrossAccountTransparencyPureChecker::new()->isAvailable(
            $accountId,
            $isShowAll,
            $this->cfg()->get('core->portal->enabled'),
            $this->cfg()->get('core->portal->mainAccountId'),
            $this->cfg()->get('core->portal->domainAuctionVisibility')
        );
    }

}
