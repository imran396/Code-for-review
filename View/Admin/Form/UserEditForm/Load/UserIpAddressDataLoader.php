<?php
/**
 * User Ip Address Data Loader
 *
 * SAM-6286: Refactor User Edit page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 14, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\UserEditForm\Load;

use Sam\Core\Filter\Common\SortInfoAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\Entity\AwareTrait\UserAwareTrait;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;

/**
 * Class UserIpAddressDataLoader
 */
class UserIpAddressDataLoader extends CustomizableClass
{
    use SortInfoAwareTrait;
    use UserAwareTrait;
    use UserLoginReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return array - return values for User Logins
     */
    public function load(): array
    {
        return $this->createUserLoginReadRepository()
            ->filterUserId($this->getUserId())
            ->orderByIpAddress($this->isAscendingOrder())
            ->loadEntities();
    }
}
