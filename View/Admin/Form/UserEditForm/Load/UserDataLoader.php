<?php
/**
 * User Data Loader
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

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\User\UserReadRepositoryCreateTrait;

/**
 * Class UserDataLoader
 */
class UserDataLoader extends CustomizableClass
{
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
     * @return array - return values for active Users
     */
    public function load(): array
    {
        $userRows = $this->createUserReadRepository()
            ->filterUserStatusId(Constants\User::US_ACTIVE)
            ->filterSubqueryIsAgentGreater(0)
            ->orderByUsername()
            ->select(['id', 'username'])
            ->loadRows();
        $dtos = [];
        foreach ($userRows as $row) {
            $dtos[] = UserDto::new()->fromDbRow($row);
        }
        return $dtos;
    }
}
