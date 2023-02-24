<?php
/**
 * Db data manager for User Deleter
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Apr 04, 2017
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Delete\Storage;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Installation\Config\Repository\ConfigRepositoryAwareTrait;
use Sam\Storage\ReadRepository\Entity\Admin\AdminReadRepositoryCreateTrait;

/**
 * Class DataManager
 * @package Sam\User\Delete\Storage
 */
class DataManager extends CustomizableClass implements DataManagerInterface
{
    use AdminReadRepositoryCreateTrait;
    use ConfigRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Return count of admin users linked to main account
     * @return int
     */
    public function countMainAccountAdmins(): int
    {
        $adminCount = $this->createAdminReadRepository()
            ->joinAccountFilterId($this->cfg()->get('core->portal->mainAccountId'))
            ->joinUserFilterUserStatusId(Constants\User::US_ACTIVE)
            ->count();
        return $adminCount;
    }
}
