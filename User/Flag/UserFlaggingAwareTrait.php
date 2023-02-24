<?php

namespace Sam\User\Flag;

/**
 * Trait UserFlaggingAwareTrait
 * @package Sam\User
 */
trait UserFlaggingAwareTrait
{
    protected ?UserFlagging $userFlagging = null;

    /**
     * @return UserFlagging
     */
    protected function getUserFlagging(): UserFlagging
    {
        if ($this->userFlagging === null) {
            $this->userFlagging = UserFlagging::new();
        }
        return $this->userFlagging;
    }

    /**
     * @param UserFlagging $userFlagging
     * @return static
     * @internal
     */
    public function setUserFlagging(UserFlagging $userFlagging): static
    {
        $this->userFlagging = $userFlagging;
        return $this;
    }
}
