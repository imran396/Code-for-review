<?php
/**
 * SAM-4799: Refactor User Account Statistic loader and saver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/23/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Account\Statistic\Load;


/**
 * Trait UserAccountStatisticLoaderCreateTrait
 * @package Sam\User\Account\Statistic\Load
 */
trait UserAccountStatisticLoaderCreateTrait
{
    protected ?UserAccountStatisticLoader $userAccountStatisticLoader = null;

    /**
     * @return UserAccountStatisticLoader
     */
    protected function createUserAccountStatisticLoader(): UserAccountStatisticLoader
    {
        $userAccountStatisticLoader = $this->userAccountStatisticLoader ?: UserAccountStatisticLoader::new();
        return $userAccountStatisticLoader;
    }

    /**
     * @param UserAccountStatisticLoader $userAccountStatisticLoader
     * @return static
     * @noinspection PhpUnused
     * @internal
     */
    public function setUserAccountStatisticLoader(UserAccountStatisticLoader $userAccountStatisticLoader): static
    {
        $this->userAccountStatisticLoader = $userAccountStatisticLoader;
        return $this;
    }
}
