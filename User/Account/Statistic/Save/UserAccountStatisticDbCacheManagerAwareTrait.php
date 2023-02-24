<?php
/**
 * SAM-5259: User account stats invalidation
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           7/8/2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Statistic\Save;

/**
 * Trait UserAccountStatisticDbCacheManagerAwareTrait
 * @package Sam\User\Account\Statistic\Save
 */
trait UserAccountStatisticDbCacheManagerAwareTrait
{
    protected ?UserAccountStatisticDbCacheManager $userAccountStatisticDbCacheManager = null;

    /**
     * @return UserAccountStatisticDbCacheManager
     */
    protected function createUserAccountStatisticDbCacheManager(): UserAccountStatisticDbCacheManager
    {
        $userAccountStatisticDbCacheManager = $this->userAccountStatisticDbCacheManager
            ?: UserAccountStatisticDbCacheManager::new();
        return $userAccountStatisticDbCacheManager;
    }

    /**
     * @param UserAccountStatisticDbCacheManager $userAccountStatisticDbCacheManager
     * @return static
     * @internal
     * @noinspection PhpUnused
     */
    public function setUserAccountStatisticDbCacheManager(UserAccountStatisticDbCacheManager $userAccountStatisticDbCacheManager): static
    {
        $this->userAccountStatisticDbCacheManager = $userAccountStatisticDbCacheManager;
        return $this;
    }
}
