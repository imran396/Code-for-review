<?php

namespace Sam\User\Validate;

/**
 * Trait UserExistenceCheckerAwareTrait
 * @package Sam\User\Validate
 */
trait UserExistenceCheckerAwareTrait
{
    protected ?UserExistenceChecker $userExistenceChecker = null;

    /**
     * @return UserExistenceChecker
     */
    protected function getUserExistenceChecker(): UserExistenceChecker
    {
        if ($this->userExistenceChecker === null) {
            $this->userExistenceChecker = UserExistenceChecker::new();
        }
        return $this->userExistenceChecker;
    }

    /**
     * @param UserExistenceChecker $userExistenceChecker
     * @return static
     * @internal
     */
    public function setUserExistenceChecker(UserExistenceChecker $userExistenceChecker): static
    {
        $this->userExistenceChecker = $userExistenceChecker;
        return $this;
    }
}
