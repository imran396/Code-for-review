<?php
/**
 * User Ip Address Unblock Editor
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

namespace Sam\View\Admin\Form\UserEditForm\Save;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepository;
use Sam\Storage\ReadRepository\Entity\UserLogin\UserLoginReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\UserLogin\UserLoginWriteRepositoryAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;

/**
 * Class UserIpAddressUnblockEditor
 */
class UserIpAddressUnblockEditor extends CustomizableClass
{
    use UserLoaderAwareTrait;
    use UserLoginReadRepositoryCreateTrait;
    use UserLoginWriteRepositoryAwareTrait;

    protected ?int $userLoginId = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $id
     * @return static
     */
    public function setUserLoginId(int $id): static
    {
        $this->userLoginId = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserLoginId(): int
    {
        return $this->userLoginId;
    }

    /**
     * @return bool
     */
    public function exist(): bool
    {
        return $this->prepareUserLoginRepository()->exist();
    }

    /**
     * Update User Login
     * @param int $editorUserId
     */
    public function save(int $editorUserId): void
    {
        $repo = $this->prepareUserLoginRepository();
        $userLogins = $repo->loadEntities();
        foreach ($userLogins as $userLogin) {
            $userLogin->Blocked = false;
            $this->getUserLoginWriteRepository()->saveWithModifier($userLogin, $editorUserId);
        }
    }

    /**
     * @return UserLoginReadRepository
     */
    protected function prepareUserLoginRepository(): UserLoginReadRepository
    {
        $userLoginInfo = $this->getUserLoader()->loadUserLogin($this->getUserLoginId(), true);
        if (!$userLoginInfo) {
            log_error("Available UserLogin not found, when unblocking user IP address" . composeSuffix(['ulid' => $this->getUserLoginId()]));
        }
        return $this->createUserLoginReadRepository()
            ->filterIpAddress($userLoginInfo->IpAddress ?? '')
            ->filterBlocked(true);
    }
}
