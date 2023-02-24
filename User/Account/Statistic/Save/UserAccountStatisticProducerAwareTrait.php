<?php
/**
 * SAM-4799: Refactor User Account Statistic loader and saver
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Alexander Kramarenko
 * @package         com.swb.sam2
 * @version         SVN: : $
 * @since           1/19/2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */


namespace Sam\User\Account\Statistic\Save;


/**
 * Trait UserAccountStatisticProducerAwareTrait
 * @package Sam\User\Account\Statistic\Save
 */
trait UserAccountStatisticProducerAwareTrait
{
    protected ?UserAccountStatisticProducer $userAccountStatisticProducer = null;

    /**
     * @return UserAccountStatisticProducer
     */
    protected function getUserAccountStatisticProducer(): UserAccountStatisticProducer
    {
        if ($this->userAccountStatisticProducer === null) {
            $this->userAccountStatisticProducer = UserAccountStatisticProducer::new();
        }
        return $this->userAccountStatisticProducer;
    }

    /**
     * @param UserAccountStatisticProducer $userAccountStatisticProducer
     * @return static
     * @internal
     */
    public function setUserAccountStatisticProducer(UserAccountStatisticProducer $userAccountStatisticProducer): static
    {
        $this->userAccountStatisticProducer = $userAccountStatisticProducer;
        return $this;
    }
}
