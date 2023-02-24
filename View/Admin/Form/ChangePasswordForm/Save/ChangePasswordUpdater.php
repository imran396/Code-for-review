<?php
/**
 * Change Password Updater
 *
 * SAM-6022: Refactor change password form at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 27, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\ChangePasswordForm\Save;

use Sam\Core\Save\AwareTrait\EditorUserAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Date\CurrentDateTrait;
use Sam\Storage\WriteRepository\Entity\User\UserWriteRepositoryAwareTrait;
use Sam\Storage\WriteRepository\Entity\UserAuthentication\UserAuthenticationWriteRepositoryAwareTrait;
use Sam\User\Auth\Identity\AuthIdentityManagerCreateTrait;
use Sam\User\Password;
use User;

/**
 * Class ChangePasswordUpdater
 */
class ChangePasswordUpdater extends CustomizableClass
{
    use AuthIdentityManagerCreateTrait;
    use CurrentDateTrait;
    use EditorUserAwareTrait;
    use UserAuthenticationWriteRepositoryAwareTrait;
    use UserWriteRepositoryAwareTrait;

    protected string $currentPassword = '';
    protected string $newPassword = '';

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $currentPassword
     * @return static
     */
    public function setCurrentPassword(string $currentPassword): static
    {
        $this->currentPassword = $currentPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    /**
     * @param string $newPassword
     * @return static
     */
    public function setNewPassword(string $newPassword): static
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * Change User Password
     */
    public function update(): void
    {
        $this->updateUserAuthentication();
        $this->createAuthIdentityManager()->requirePasswordChange(false);
        $this->updateEditorUser();
    }

    /**
     * Set null User's Authentication temporary password
     */
    protected function updateUserAuthentication(): void
    {
        $userAuthentication = $this->getEditorUserAuthenticationOrCreate();
        Password\History::new()->addPassword($this->getCurrentPassword(), $userAuthentication);
        $userAuthentication->TmpPword = '';
        $userAuthentication->TmpPwordTs = null;
        $userAuthentication->PwordDate = $this->getCurrentDateUtc();
        $this->getUserAuthenticationWriteRepository()->saveWithSelfModifier($userAuthentication);
    }

    /**
     * Save User's new password
     */
    protected function updateEditorUser(): void
    {
        /** @var User $editorUser checked in validation */
        $editorUser = $this->getEditorUser();
        $editorUser->Pword = Password\HashHelper::new()->normalizeAndEncrypt($this->getNewPassword());
        $this->getUserWriteRepository()->saveWithSelfModifier($editorUser);
    }
}
