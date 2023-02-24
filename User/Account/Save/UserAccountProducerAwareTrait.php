<?php
/**
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Aleksandar Srejic
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           2018-12-27
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Account\Save;

/**
 * Trait UserAccountProducerAwareTrait
 * @package Sam\User\Account\Save
 */
trait UserAccountProducerAwareTrait
{
    protected ?UserAccountProducer $userAccountProducer = null;

    /**
     * @return UserAccountProducer
     */
    protected function getUserAccountProducer(): UserAccountProducer
    {
        if ($this->userAccountProducer === null) {
            $this->userAccountProducer = UserAccountProducer::new();
        }
        return $this->userAccountProducer;
    }

    /**
     * @param UserAccountProducer $userAccountProducer
     * @return static
     * @internal
     */
    public function setUserAccountProducer(UserAccountProducer $userAccountProducer): static
    {
        $this->userAccountProducer = $userAccountProducer;
        return $this;
    }
}
