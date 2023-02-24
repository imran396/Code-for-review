<?php
/**
 * Access check to account page at responsive site.
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

namespace Sam\Account\Access\Responsive;

use Account;
use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;

/**
 * Class ResponsiveAccountPageAccessChecker
 * @package Sam\Account\Access\Responsive
 */
class ResponsiveAccountPageAccessChecker extends CustomizableClass
{
    use ConfigRepositoryAwareTrait;
    use CrossAccountTransparencyCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check access "Accounts" page at responsive site.
     *
     * @param Account $account
     * @param int|null $editorUserId
     * @return bool
     */
    public function isAvailableByAccount(Account $account, ?int $editorUserId): bool
    {
        return $this->isPageForEditor($editorUserId)
            && $this->createCrossAccountTransparencyChecker()->isAvailableByAccount($account);
    }

    /**
     * Check access "Accounts" page at responsive site.
     *
     * @param int $accountId
     * @param int|null $editorUserId
     * @param bool $isReadOnlyDb
     * @return bool
     */
    public function isAvailableByAccountId(int $accountId, ?int $editorUserId, bool $isReadOnlyDb = false): bool
    {
        return $this->isPageForEditor($editorUserId)
            && $this->createCrossAccountTransparencyChecker()->isAvailableByAccountId($accountId, $isReadOnlyDb);
    }

    protected function isPageForEditor(?int $editorUserId): bool
    {
        return $this->cfg()->get('core->portal->enabled')
            && $editorUserId;
    }

}
