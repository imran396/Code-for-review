<?php

namespace Sam\User\Info;

/**
 * Trait UserInfoNoteManagerAwareTrait
 * @package Sam\User\Info
 */
trait UserInfoNoteManagerAwareTrait
{
    protected ?UserInfoNoteManager $userInfoNoteManager = null;

    /**
     * @return UserInfoNoteManager
     */
    protected function getUserInfoNoteManager(): UserInfoNoteManager
    {
        if ($this->userInfoNoteManager === null) {
            $this->userInfoNoteManager = UserInfoNoteManager::new();
        }
        return $this->userInfoNoteManager;
    }

    /**
     * @param UserInfoNoteManager $userInfoNoteManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserInfoNoteManager(UserInfoNoteManager $userInfoNoteManager): static
    {
        $this->userInfoNoteManager = $userInfoNoteManager;
        return $this;
    }
}
