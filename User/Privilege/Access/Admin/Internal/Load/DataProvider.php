<?php
/**
 * SAM-9520: Important Security user privilege issue
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 03, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Privilege\Access\Admin\Internal\Load;

use Admin;
use Sam\Core\Service\CustomizableClass;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class DataProvider
 * @package Sam\User\Privilege
 */
class DataProvider extends CustomizableClass
{
    use UserLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function loadAdmin(int $userId, bool $isReadOnlyDb = false): ?Admin
    {
        return $this->getUserLoader()->loadAdmin($userId, $isReadOnlyDb);
    }
}
