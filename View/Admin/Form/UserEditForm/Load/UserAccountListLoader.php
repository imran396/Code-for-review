<?php
/**
 * SAM-7974: Consignor commission and fees extension
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;
use Sam\Storage\ReadRepository\Entity\UserAccount\UserAccountReadRepositoryCreateTrait;

/**
 * Class UserAccountListLoader
 * @package Sam\View\Admin\Form\UserEditForm\Load
 */
class UserAccountListLoader extends CustomizableClass
{
    use UserAccountReadRepositoryCreateTrait;
    use UserReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Load list of all user accounts
     *
     * @param int $userId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function load(int $userId, bool $isReadOnlyDb = false): array
    {
        $collateralAccounts = $this->createUserAccountReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterUserId($userId)
            ->joinAccountFilterActive(true)
            ->select(['acc.id', 'acc.name'])
            ->loadRows();

        $mainAccount = $this->createUserReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($userId)
            ->joinAccountFilterActive(true)
            ->select(['acc.id', 'acc.name'])
            ->loadRow();

        $accounts = array_merge([], [$mainAccount], $collateralAccounts);
        $indexedAccounts = [];
        foreach ($accounts as $account) {
            $indexedAccounts[$account['id']] = $account['name'];
        }
        return $indexedAccounts;
    }
}
