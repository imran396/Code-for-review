<?php

namespace Sam\User\Load;

/**
 * Trait UserLoaderAwareTrait
 * @package Sam\User\Load
 */
trait UserLoaderAwareTrait
{
    protected ?UserLoader $userLoader = null;

    /**
     * @return UserLoader
     */
    protected function getUserLoader(): UserLoader
    {
        if ($this->userLoader === null) {
            $this->userLoader = UserLoader::new();
        }
        return $this->userLoader;
    }

    /**
     * @param UserLoader $userLoader
     * @return static
     * @internal
     */
    public function setUserLoader(UserLoader $userLoader): static
    {
        $this->userLoader = $userLoader;
        return $this;
    }
}
