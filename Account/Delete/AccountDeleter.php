<?php
/**
 * Auction deleter
 *
 * SAM-3943: Account deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 24, 2018
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Account\Delete;

use Account;
use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Date\CurrentDateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\Account\AccountWriteRepositoryAwareTrait;

/**
 * Class Deleter
 * @package Sam\Account\Delete
 */
class AccountDeleter extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AccountWriteRepositoryAwareTrait;
    use CurrentDateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param Account $account
     * @param int $editorUserId
     */
    public function delete(Account $account, int $editorUserId): void
    {
        $account->Active = false;
        $this->getAccountWriteRepository()->saveWithModifier($account, $editorUserId);
    }

    /**
     * Delete by id
     * @param int $accountId
     * @param int $editorUserId
     */
    public function deleteById(int $accountId, int $editorUserId): void
    {
        $account = $this->getAccountLoader()->load($accountId);
        if ($account) {
            $this->delete($account, $editorUserId);
        }
    }
}
