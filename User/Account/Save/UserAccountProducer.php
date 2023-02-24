<?php
/**
 *
 * SAM-4738: UserAccount management modules
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Save;

use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAccount\UserAccountWriteRepositoryAwareTrait;
use Sam\User\Account\Load\UserAccountLoaderAwareTrait;
use Sam\User\Flag\UserFlaggingAwareTrait;
use UserAccount;

/**
 * Class UserAccountProducer
 * @package Sam\User\Account\Save
 */
class UserAccountProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use ConfigRepositoryAwareTrait;
    use UserAccountLoaderAwareTrait;
    use UserAccountWriteRepositoryAwareTrait;
    use UserFlaggingAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $userId
     * @param int $accountId
     * @param int $editorUserId
     * @return UserAccount
     */
    public function add(int $userId, int $accountId, int $editorUserId): UserAccount
    {
        $userAccount = $this->getUserAccountLoader()->load($userId, $accountId);
        if (!$userAccount) {
            $userAccount = $this->createEntityFactory()->userAccount();
            $userAccount->UserId = $userId;
            $userAccount->AccountId = $accountId;
            $this->getUserAccountWriteRepository()->saveWithModifier($userAccount, $editorUserId);
        }
        if ($this->cfg()->get('core->portal->enabled')) {
            $flag = $this->getUserFlagging()->checkUserAccountFlag($userAccount);
            $userAccount->Flag = $flag;
            $this->getUserAccountWriteRepository()->saveWithModifier($userAccount, $editorUserId);
        }
        return $userAccount;
    }
}
