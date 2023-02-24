<?php
/**
 * SAM-4740: Avoid calling of load functions from data class
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Log\Load;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserLog\UserLogReadRepositoryCreateTrait;
use UserLog;

/**
 * Class UserLogLoader
 * @package Sam\User\Log\Load
 */
class UserLogLoader extends CustomizableClass
{
    use UserLogReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int|null $id
     * @param bool $isReadOnlyDb
     * @return UserLog|null
     */
    public function load(?int $id, bool $isReadOnlyDb = false): ?UserLog
    {
        if (!$id) {
            return null;
        }

        return $this->createUserLogReadRepository()
            ->enableReadOnlyDb($isReadOnlyDb)
            ->filterId($id)
            ->loadEntity();
    }
}
