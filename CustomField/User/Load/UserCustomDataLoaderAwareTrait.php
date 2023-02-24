<?php

namespace Sam\CustomField\User\Load;

/**
 * Trait UserCustomDataLoaderAwareTrait
 * @package Sam\CustomField\User\Load
 */
trait UserCustomDataLoaderAwareTrait
{
    /**
     * @var UserCustomDataLoader|null
     */
    protected ?UserCustomDataLoader $userCustomDataLoader = null;

    /**
     * @return UserCustomDataLoader
     */
    protected function getUserCustomDataLoader(): UserCustomDataLoader
    {
        if ($this->userCustomDataLoader === null) {
            $this->userCustomDataLoader = UserCustomDataLoader::new();
        }
        return $this->userCustomDataLoader;
    }

    /**
     * @param UserCustomDataLoader $loader
     * @return static
     * @internal
     */
    public function setUserCustomDataLoader(UserCustomDataLoader $loader): static
    {
        $this->userCustomDataLoader = $loader;
        return $this;
    }
}
